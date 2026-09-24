<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Complaint;
use App\Models\District;
use App\Models\DtsenCertificate;
use App\Models\InformationPage;
use App\Models\PbiReactivation;
use App\Models\RehabilitationCase;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\Village;
use App\Models\WorkUnit;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_runs_successfully_and_populates_all_modules(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertGreaterThan(0, WorkUnit::count());
        $this->assertGreaterThan(0, District::count());
        $this->assertGreaterThan(0, Village::count());
        $this->assertGreaterThan(0, User::count());
        $this->assertGreaterThan(0, ServiceRequest::count());
        $this->assertGreaterThan(0, DtsenCertificate::count());
        $this->assertGreaterThan(0, PbiReactivation::count());
        $this->assertGreaterThan(0, Complaint::count());
        $this->assertGreaterThan(0, Client::count());
        $this->assertGreaterThan(0, RehabilitationCase::count());
        $this->assertGreaterThan(0, InformationPage::count());

        // Pastikan akun admin dan pimpinan tersedia
        $this->assertDatabaseHas('users', [
            'email' => 'admin@dinsos.blitarkab.go.id',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'kadis@dinsos.blitarkab.go.id',
        ]);

        // Pastikan jenis layanan utama tersedia
        $this->assertDatabaseHas('service_types', [
            'code' => 'DTSEN',
            'handler' => 'dtsen',
        ]);
        $this->assertDatabaseHas('service_types', [
            'code' => 'PBI',
            'handler' => 'pbi',
        ]);
    }
}
