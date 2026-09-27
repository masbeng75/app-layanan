<?php

namespace Tests\Feature;

use App\Filament\Pages\Reports\ComplaintsReportPage;
use App\Filament\Pages\Reports\DtsenReportPage;
use App\Filament\Pages\Reports\PbiReportPage;
use App\Filament\Pages\Reports\RehabilitationReportPage;
use App\Filament\Pages\Reports\ServiceRequestsReportPage;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class ReportsAndExportsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! User::first()) {
            $this->seed();
        }
    }

    public function test_admin_can_access_all_fase_4_report_pages(): void
    {
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->first() ?? User::first();
        $this->assertNotNull($admin);

        $pages = [
            '/admin/dtsen-report-page' => 'Laporan Rekapitulasi SK DTSEN',
            '/admin/pbi-report-page' => 'Laporan Rekapitulasi Reaktivasi PBI-JK',
            '/admin/rehabilitation-report-page' => 'Laporan Rekapitulasi Kasus & Rujukan',
            '/admin/service-requests-report-page' => 'Laporan Rekapitulasi Seluruh Pengajuan Pelayanan',
            '/admin/complaints-report-page' => 'Laporan Rekapitulasi Pengaduan Masyarakat',
        ];

        foreach ($pages as $url => $titleSnippet) {
            $response = $this->actingAs($admin)->get($url);
            $response->assertSuccessful();
            $response->assertSee($titleSnippet);
        }
    }

    public function test_report_pages_livewire_tables_render_successfully(): void
    {
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->first() ?? User::first();
        $this->actingAs($admin);

        Livewire::test(DtsenReportPage::class)->assertSuccessful();
        Livewire::test(PbiReportPage::class)->assertSuccessful();
        Livewire::test(RehabilitationReportPage::class)->assertSuccessful();
        Livewire::test(ServiceRequestsReportPage::class)->assertSuccessful();
        Livewire::test(ComplaintsReportPage::class)->assertSuccessful();
    }

    public function test_pdf_export_action_generates_streamed_pdf(): void
    {
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->first() ?? User::first();
        $this->actingAs($admin);

        $dtsenPage = Livewire::test(DtsenReportPage::class);
        $dtsenResponse = $dtsenPage->instance()->exportPdf();
        $this->assertEquals(200, $dtsenResponse->getStatusCode());
        $this->assertEquals('application/pdf', $dtsenResponse->headers->get('content-type'));

        $pbiPage = Livewire::test(PbiReportPage::class);
        $pbiResponse = $pbiPage->instance()->exportPdf();
        $this->assertEquals(200, $pbiResponse->getStatusCode());
        $this->assertEquals('application/pdf', $pbiResponse->headers->get('content-type'));

        $rehabPage = Livewire::test(RehabilitationReportPage::class);
        $rehabResponse = $rehabPage->instance()->exportPdf();
        $this->assertEquals(200, $rehabResponse->getStatusCode());
        $this->assertEquals('application/pdf', $rehabResponse->headers->get('content-type'));

        $servicePage = Livewire::test(ServiceRequestsReportPage::class);
        $serviceResponse = $servicePage->instance()->exportPdf();
        $this->assertEquals(200, $serviceResponse->getStatusCode());
        $this->assertEquals('application/pdf', $serviceResponse->headers->get('content-type'));

        $complaintPage = Livewire::test(ComplaintsReportPage::class);
        $complaintResponse = $complaintPage->instance()->exportPdf();
        $this->assertEquals(200, $complaintResponse->getStatusCode());
        $this->assertEquals('application/pdf', $complaintResponse->headers->get('content-type'));
    }
}
