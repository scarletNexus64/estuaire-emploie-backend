<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Process;
use Throwable;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database {--mail : Envoyer immédiatement le mail avec la sauvegarde} {--no-mail : Ne pas envoyer de mail pour cette exécution}';
    protected $description = 'Export SQL compressé quotidien, conservation des deux derniers exports et mail chaque jour (Europe/Paris).';

    public function handle(): int
    {
        $previousUmask = umask(0077);
        $root = storage_path('backups/daily');
        File::ensureDirectoryExists($root, 0700);
        $lock = fopen($root.'/.lock', 'c');
        if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
            $this->warn('Une sauvegarde est déjà en cours.');
            umask($previousUmask);
            return self::FAILURE;
        }

        $now = now('Europe/Paris');
        $name = $now->format('Y-m-d_H-i-s').'_'.bin2hex(random_bytes(4));
        $partial = $root.'/.pending-'.$name;
        $target = $root.'/'.$name;
        $credentials = $partial.'/client.cnf';
        $archive = null;
        $completed = false;

        try {
            $connection = DB::connection();
            if ($connection->getDriverName() !== 'mysql') {
                throw new \RuntimeException('Cette commande nécessite une base MySQL/MariaDB.');
            }
            $settings = $connection->getConfig();
            File::makeDirectory($partial, 0700);
            $quote = static fn ($value) => '"'.str_replace(["\\", '"', "\n", "\r"], ["\\\\", '\\"', '\\n', '\\r'], (string) $value).'"';
            $options = "[client]\n";
            foreach (['host', 'port', 'username' => 'user', 'password', 'unix_socket' => 'socket'] as $key => $option) {
                $key = is_int($key) ? $option : $key;
                if (!empty($settings[$key])) {
                    $options .= $option.'='.$quote($settings[$key])."\n";
                }
            }
            file_put_contents($credentials, $options);
            chmod($credentials, 0600);
            $filename = 'estuaire-emploi.sql.gz';
            $archive = gzopen($partial.'/'.$filename, 'wb6');
            if (!$archive) {
                throw new \RuntimeException('Impossible de créer le fichier compressé.');
            }
            $bytes = 0;
            $process = new Process([
                '/usr/bin/mysqldump', '--defaults-extra-file='.$credentials,
                '--single-transaction', '--quick', '--skip-lock-tables', '--no-tablespaces',
                '--routines', '--events', '--triggers', '--hex-blob', '--default-character-set=utf8mb4',
                '--databases', $connection->getDatabaseName(),
            ]);
            $process->setTimeout(3600);
            $process->run(function ($type, $buffer) use (&$archive, &$bytes, $process) {
                if ($type === Process::OUT) {
                    $length = strlen($buffer);
                    if (gzwrite($archive, $buffer) !== $length) {
                        throw new \RuntimeException('Écriture de la sauvegarde incomplète (espace disque ?).');
                    }
                    $bytes += $length;
                    $process->clearOutput();
                }
            });
            if (!$process->isSuccessful()) {
                throw new \RuntimeException('Échec mysqldump : '.$process->getErrorOutput());
            }
            if (!gzclose($archive)) {
                throw new \RuntimeException('Impossible de finaliser la compression.');
            }
            $archive = null;
            unlink($credentials);
            if ($bytes < 100) {
                throw new \RuntimeException('Export SQL vide ou incomplet.');
            }
            (new Process(['/usr/bin/gzip', '-t', $partial.'/'.$filename]))->setTimeout(3600)->mustRun();
            $size = filesize($partial.'/'.$filename);
            $metadata = [
                'created_at' => $now->toIso8601String(),
                'database' => $connection->getDatabaseName(),
                'sql_bytes' => $bytes,
                'compressed_bytes' => $size,
                'sha256' => hash_file('sha256', $partial.'/'.$filename),
            ];
            file_put_contents($partial.'/manifest.json', json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
            if (!rename($partial, $target)) {
                throw new \RuntimeException('Impossible de publier la sauvegarde validée.');
            }
            $completed = true;
            // Ne supprimer que les dossiers créés par cette commande, après validation du nouvel export.
            $directories = array_values(array_filter(File::directories($root), static fn ($path) =>
                !is_link($path) && preg_match('/^\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}_[a-f0-9]{8}$/', basename($path))
                && is_file($path.'/manifest.json')
            ));
            rsort($directories, SORT_STRING);
            foreach (array_slice($directories, 2) as $old) {
                if (!File::deleteDirectory($old)) {
                    throw new \RuntimeException('Impossible de supprimer une ancienne sauvegarde.');
                }
            }
            $this->info('Sauvegarde validée : '.$target.'/'.$filename.' ('.$size.' octets compressés ; '.$bytes.' octets SQL).');
            Log::info('Sauvegarde SQL validée', $metadata + ['path' => $target]);

            if (!$this->option('no-mail')) {
                $attach = $size <= 15 * 1024 * 1024;
                $body = "Sauvegarde Estuaire Emploi du ".$now->format('d/m/Y H:i:s T').".\n"
                    ."Export SQL : ".number_format($bytes / 1048576, 2)." Mo ; compressé : ".number_format($size / 1048576, 2)." Mo.\n"
                    ."SHA-256 : ".$metadata['sha256']."\n"
                    .($attach ? "Le fichier SQL compressé est joint.\n" : "Pièce jointe omise : l'archive dépasse le seuil de 15 Mo.\n")
                    ."Copie serveur : ".$target.'/'.$filename."\nDeux sauvegardes quotidiennes sont conservées sur le serveur.";
                Mail::raw($body, function ($message) use ($attach, $target, $filename, $now) {
                    $message->to('steve.boussa84@gmail.com')->cc(['stephanefoyet@gmail.com', 'Shalomdev316@gmail.com'])
                        ->subject('Estuaire Emploi — sauvegarde SQL du '.$now->format('d/m/Y'));
                    if ($attach) {
                        $message->attach($target.'/'.$filename, ['as' => 'estuaire-emploi-'.$now->format('Y-m-d').'.sql.gz', 'mime' => 'application/gzip']);
                    }
                });
                $this->info('Mail transmis au transport configuré, Steve destinataire, Stéphane et Shalom en copie.');
            }
            return self::SUCCESS;
        } catch (Throwable $e) {
            Log::error('Échec sauvegarde SQL ou notification', ['backup_completed' => $completed, 'error' => $e->getMessage()]);
            $this->error($e->getMessage());
            if (!$this->option('no-mail')) {
                try {
                    Mail::raw('La tâche de sauvegarde Estuaire Emploi a rencontré une erreur. Export validé : '.($completed ? 'oui' : 'non').'. Consultez storage/logs/database-backup.log et laravel.log sur le serveur.', function ($message) {
                        $message->to('steve.boussa84@gmail.com')->cc(['stephanefoyet@gmail.com', 'Shalomdev316@gmail.com'])->subject('Estuaire Emploi — échec de sauvegarde ou notification');
                    });
                } catch (Throwable $mailError) {
                    Log::error('Échec notification de sauvegarde', ['error' => $mailError->getMessage()]);
                }
            }
            return self::FAILURE;
        } finally {
            if (is_resource($archive)) {
                gzclose($archive);
            }
            if (is_file($credentials)) {
                unlink($credentials);
            }
            if (is_dir($partial)) {
                File::deleteDirectory($partial);
            }
            flock($lock, LOCK_UN);
            fclose($lock);
            umask($previousUmask);
        }
    }
}
