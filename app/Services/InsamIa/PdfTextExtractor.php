<?php

namespace App\Services\InsamIa;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;
use Throwable;

/**
 * Extraction du texte d'un PDF déposé par un étudiant.
 *
 * Un PDF scanné (photo de copie) est un PDF valide mais sans couche texte :
 * l'extraction y renvoie une chaîne vide. Le distinguer est indispensable,
 * sinon on enverrait une copie vide au correcteur, qui noterait dans le vide.
 *
 * L'OCR a été écarté : sur du texte imprimé déjà net, Tesseract rend
 * « S = n(n+1)/2 » par « vauts= nin+1}2 ». Une note assise sur une lecture
 * fausse est pire que pas de correction du tout, alors on refuse et on le dit.
 */
class PdfTextExtractor
{
    /**
     * En deçà, on considère qu'il n'y a pas de texte exploitable : un PDF
     * scanné rend zéro caractère, et quelques caractères isolés (numéro de
     * page, filigrane) ne font pas une copie.
     */
    private const MIN_USEFUL_CHARS = 50;

    public function __construct(private readonly Parser $parser = new Parser())
    {
    }

    /**
     * Texte du PDF, ou null s'il n'en contient pas d'exploitable.
     *
     * @throws InsamIaException si le fichier est illisible
     */
    public function extract(UploadedFile $file): ?string
    {
        try {
            $text = $this->parser->parseFile($file->getRealPath())->getText();
        } catch (Throwable $e) {
            Log::warning('Lecture PDF impossible', [
                'name' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);

            throw new InsamIaException(
                'Ce PDF n\'a pas pu être lu.',
                InsamIaException::REASON_UNAVAILABLE,
            );
        }

        $text = $this->normalize($text);

        return $this->isUseful($text) ? $text : null;
    }

    /**
     * Le texte extrait est-il exploitable ?
     *
     * On compte les caractères hors espaces : un PDF scanné en rend zéro.
     */
    public function isUseful(?string $text): bool
    {
        if ($text === null) {
            return false;
        }

        return mb_strlen((string) preg_replace('/\s+/u', '', $text)) >= self::MIN_USEFUL_CHARS;
    }

    /**
     * Resserre l'espacement laissé par l'extraction.
     *
     * Les extracteurs PDF sèment les espaces et les sauts de ligne au gré du
     * positionnement des glyphes ; on garde les paragraphes, on jette le reste.
     */
    private function normalize(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        // Espaces insécables et assimilés, que les PDF utilisent abondamment.
        $text = preg_replace('/[\x{00A0}\x{2007}\x{202F}]/u', ' ', $text) ?? $text;
        $text = preg_replace('/[ \t]+/', ' ', $text) ?? $text;
        $text = preg_replace('/ *\n */', "\n", $text) ?? $text;
        // Trois sauts ou plus : on retombe sur une séparation de paragraphe.
        $text = preg_replace('/\n{3,}/', "\n\n", $text) ?? $text;

        return trim($text);
    }
}
