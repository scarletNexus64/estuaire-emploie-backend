<?php

namespace Tests\Feature;

use App\Console\Commands\UpdateVerifiedCompanyLinks;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UpdateVerifiedCompanyLinksTest extends TestCase
{
    private string $temporary;
    private string $manifest;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'link_test', 'database.connections.link_test' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']]);
        DB::purge('link_test');
        $this->temporary = sys_get_temp_dir().'/company-links-test-'.bin2hex(random_bytes(6));
        File::makeDirectory($this->temporary, 0700, true);
        $this->app->useStoragePath($this->temporary);
        $this->manifest = $this->temporary.'/manifest.json';
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            foreach (['name', 'city', 'phone', 'website', 'facebook', 'tiktok'] as $field) $table->string($field)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        DB::table('companies')->insert(['id' => 1, 'name' => 'Example', 'city' => 'Douala', 'phone' => '699000001', 'website' => 'https://existing.example/', 'updated_at' => '2020-01-01 00:00:00']);
    }

    protected function tearDown(): void
    {
        DB::disconnect('link_test');
        File::deleteDirectory($this->temporary);
        parent::tearDown();
    }

    private function writeManifest(array $overrides = []): void
    {
        $entry = ['company_id' => 1, 'name' => 'Example', 'city' => 'Douala', 'phone' => '699000001', 'links' => [
            'website' => ['url' => 'https://example.org/', 'verified_at' => '2026-10-26', 'evidence' => ['https://example.org/contact'], 'reason' => 'Identité et téléphone recoupés.'],
            'facebook' => ['url' => 'https://www.facebook.com/examplecompany/', 'verified_at' => '2026-10-26', 'evidence' => ['https://example.org/contact'], 'reason' => 'Lien publié par le site officiel.'],
        ]];
        File::put($this->manifest, json_encode(['schema_version' => 1, 'entries' => [array_replace($entry, $overrides)]]));
    }

    public function test_simulation_then_apply_preserves_existing_links_and_is_idempotent(): void
    {
        $this->writeManifest();
        $this->assertSame(0, Artisan::call('companies:update-verified-links', ['file' => $this->manifest]));
        $this->assertNull(DB::table('companies')->value('facebook'));
        $this->assertSame(0, Artisan::call('companies:update-verified-links', ['file' => $this->manifest, '--apply' => true]));
        $row = DB::table('companies')->first();
        $this->assertSame('https://existing.example/', $row->website);
        $this->assertSame('https://www.facebook.com/examplecompany/', $row->facebook);
        $this->assertSame(1, DB::table('companies')->count());
        $reports = glob($this->temporary.'/app/private/company-link-updates/*.json');
        $this->assertCount(2, $reports);
        $backup = json_decode(File::get($reports[0]), true);
        $this->assertSame(['facebook' => null], $backup['changes'][0]['before']);
        $this->assertSame(0600, fileperms($reports[0]) & 0777);
        $this->assertSame(0, Artisan::call('companies:update-verified-links', ['file' => $this->manifest, '--apply' => true]));
        $this->assertEquals($row, DB::table('companies')->first());
    }

    public function test_identity_mismatch_prevents_any_write(): void
    {
        $this->writeManifest(['phone' => 'wrong']);
        $this->assertSame(1, Artisan::call('companies:update-verified-links', ['file' => $this->manifest, '--apply' => true]));
        $this->assertNull(DB::table('companies')->value('facebook'));
    }

    public function test_search_links_and_wrong_platforms_are_rejected(): void
    {
        foreach ([['website', 'https://www.google.com/search?q=company'], ['facebook', 'https://www.facebook.com/search/top?q=company'], ['tiktok', 'https://www.tiktok.com/search?q=company'], ['tiktok', 'https://www.tiktok.com/@company/video/123'], ['facebook', 'https://facebook.com.evil.example/company'], ['website', 'https://company.business.site/']] as [$field, $url]) {
            $this->assertFalse(UpdateVerifiedCompanyLinks::validLink($field, $url), $url);
        }
        $this->writeManifest(['links' => ['facebook' => ['url' => 'https://www.facebook.com/search/top?q=company', 'verified_at' => '2026-10-26', 'evidence' => ['https://example.org/'], 'reason' => 'Test']]]);
        $this->assertSame(1, Artisan::call('companies:update-verified-links', ['file' => $this->manifest, '--apply' => true]));
        $this->assertNull(DB::table('companies')->value('facebook'));
    }
}
