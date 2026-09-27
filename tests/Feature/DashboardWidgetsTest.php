<?php

namespace Tests\Feature;

use App\Filament\Widgets\DtsenAwaitingSignature;
use App\Filament\Widgets\DtsenIssuedOverview;
use App\Filament\Widgets\IncomingRequestsChart;
use App\Filament\Widgets\PbiEmergencyPriority;
use App\Filament\Widgets\PbiReactivationByStage;
use App\Filament\Widgets\RegionalDistribution;
use App\Filament\Widgets\RehabilitationActiveCases;
use App\Filament\Widgets\RequestsByStatus;
use App\Filament\Widgets\TopInformationPages;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardWidgetsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! User::first()) {
            $this->seed();
        }
    }

    public function test_admin_dashboard_renders_with_filters_form(): void
    {
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->first() ?? User::first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertSuccessful();
        $response->assertSee('Dashboard SAPA SOSIAL');
        $response->assertSee('Filter Data Dashboard');
        $response->assertSee('Dari Tanggal');
        $response->assertSee('Sampai Tanggal');
        $response->assertSee('Jenis Layanan');
        $response->assertSee('Kecamatan');
        $response->assertSee('Desa / Kelurahan');
        $response->assertSee('1 / -1');
    }

    public function test_stats_overview_widgets_can_render(): void
    {
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->first() ?? User::first();
        $this->actingAs($admin);

        Livewire::test(DtsenIssuedOverview::class)->assertSuccessful();
        Livewire::test(DtsenAwaitingSignature::class)->assertSuccessful();
        Livewire::test(PbiReactivationByStage::class)->assertSuccessful();
        Livewire::test(RehabilitationActiveCases::class)->assertSuccessful();
    }

    public function test_chart_widgets_can_render(): void
    {
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->first() ?? User::first();
        $this->actingAs($admin);

        Livewire::test(IncomingRequestsChart::class)->assertSuccessful();
        Livewire::test(RequestsByStatus::class)->assertSuccessful();
        Livewire::test(RegionalDistribution::class)->assertSuccessful();
    }

    public function test_table_widgets_can_render(): void
    {
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->first() ?? User::first();
        $this->actingAs($admin);

        Livewire::test(PbiEmergencyPriority::class)->assertSuccessful();
        Livewire::test(TopInformationPages::class)->assertSuccessful();
    }

    public function test_operator_can_access_dashboard_with_scope(): void
    {
        $operator = User::where('email', 'like', 'operator.%')->first();
        if (! $operator) {
            $operator = User::first();
        }

        $response = $this->actingAs($operator)->get('/admin');
        $response->assertSuccessful();
        $response->assertSee('Dashboard SAPA SOSIAL');
    }
}
