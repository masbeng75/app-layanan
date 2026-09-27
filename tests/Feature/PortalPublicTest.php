<?php

namespace Tests\Feature;

use App\Enums\ComplaintStatus;
use App\Enums\PublishStatus;
use App\Enums\ServiceRequestStatus;
use App\Livewire\Portal\CertificateVerification;
use App\Livewire\Portal\ComplaintSubmission;
use App\Livewire\Portal\Home;
use App\Livewire\Portal\ServiceCatalog;
use App\Livewire\Portal\ServiceDetail;
use App\Livewire\Portal\ServiceRequestSubmission;
use App\Livewire\Portal\TicketTracking;
use App\Models\ComplaintCategory;
use App\Models\DtsenCertificate;
use App\Models\DtsenPurpose;
use App\Models\InformationPage;
use App\Models\PageVisit;
use App\Models\ServiceRequest;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PortalPublicTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! User::first()) {
            $this->seed();
        }
    }

    public function test_portal_home_page_loads_successfully(): void
    {
        $response = $this->get(route('portal.home'));
        $response->assertStatus(200);
        $response->assertSee('SAPA SOSIAL');
        $response->assertSee('Kabupaten Blitar');
        $response->assertSee('Ajukan Layanan Online');

        Livewire::test(Home::class)
            ->assertStatus(200)
            ->assertSee('Satu Pintu Layanan Sosial');
    }

    public function test_service_catalog_page_and_filtering(): void
    {
        $response = $this->get(route('portal.catalog'));
        $response->assertStatus(200);
        $response->assertSee('Katalog Informasi');

        Livewire::test(ServiceCatalog::class)
            ->assertStatus(200)
            ->set('search', 'DTSEN')
            ->assertSee('DTSEN');
    }

    public function test_service_detail_page_loads_and_increments_visits(): void
    {
        $page = InformationPage::where('publish_status', PublishStatus::PUBLISHED)->first();
        $this->assertNotNull($page, 'Published information page must exist.');

        $initialVisits = PageVisit::where('information_page_id', $page->id)
            ->whereDate('visit_date', now()->toDateString())
            ->value('visit_count') ?? 0;

        $response = $this->get(route('portal.detail', $page->slug));
        $response->assertStatus(200);
        $response->assertSee($page->title);

        $newVisits = PageVisit::where('information_page_id', $page->id)
            ->whereDate('visit_date', now()->toDateString())
            ->value('visit_count') ?? 0;

        $this->assertGreaterThan($initialVisits, $newVisits);

        Livewire::test(ServiceDetail::class, ['slug' => $page->slug])
            ->assertStatus(200)
            ->assertSee($page->title);
    }

    public function test_service_request_submission_validation_and_creation(): void
    {
        Storage::fake('public');

        $serviceType = ServiceType::with('requirements')
            ->where('code', 'DTSEN')
            ->firstOrFail();

        $purpose = DtsenPurpose::where('is_active', true)->firstOrFail();
        $village = Village::firstOrFail();

        $uploadedFiles = [];
        foreach ($serviceType->requirements as $req) {
            $uploadedFiles[$req->id] = UploadedFile::fake()->create('dokumen.pdf', 200, 'application/pdf');
        }

        $component = Livewire::test(ServiceRequestSubmission::class)
            ->set('service_type_id', $serviceType->id)
            ->set('dtsen_purpose_id', $purpose->id)
            ->set('subject_name', 'Mochammad Budianto')
            ->set('subject_nik', '3505011205900001')
            ->set('relationship_to_applicant', 'Diri Sendiri')
            ->call('nextStep')
            ->assertSet('currentStep', 2)
            ->set('applicant_name', 'Mochammad Budianto')
            ->set('applicant_nik', '3505011205900001')
            ->set('family_card_number', '3505011205900002')
            ->set('phone', '081234567890')
            ->set('district_id', $village->district_id)
            ->set('village_id', $village->id)
            ->set('address', 'Jl. Kenanga No. 12 RT 01 RW 02')
            ->call('nextStep')
            ->assertSet('currentStep', 3);

        foreach ($uploadedFiles as $reqId => $file) {
            $component->set("uploads.{$reqId}", $file);
        }

        $component->call('nextStep')
            ->assertSet('currentStep', 4)
            ->set('agreement', true)
            ->call('submit')
            ->assertHasNoErrors();

        $ticket = $component->get('submittedTicket');
        $this->assertNotNull($ticket);
        $this->assertMatchesRegularExpression('/^[A-Z]+-\d{6}-\d{5}$/', $ticket);

        $this->assertDatabaseHas('service_requests', [
            'request_number' => $ticket,
            'applicant_name' => 'Mochammad Budianto',
            'applicant_nik' => '3505011205900001',
            'status' => ServiceRequestStatus::SUBMITTED->value,
        ]);

        $this->assertDatabaseHas('dtsen_certificates', [
            'subject_name' => 'Mochammad Budianto',
            'subject_nik' => '3505011205900001',
        ]);
    }

    public function test_complaint_submission_validation_and_creation(): void
    {
        Storage::fake('public');

        $category = ComplaintCategory::where('is_active', true)->firstOrFail();
        $village = Village::firstOrFail();

        $file = UploadedFile::fake()->image('bukti_kasus.jpg');

        $component = Livewire::test(ComplaintSubmission::class)
            ->set('complaint_category_id', $category->id)
            ->set('is_anonymous', false)
            ->set('reporter_name', 'Siti Rahmawati')
            ->set('reporter_phone', '085712345678')
            ->set('district_id', $village->district_id)
            ->set('village_id', $village->id)
            ->set('location_detail', 'Dekat Balai Desa RT 03')
            ->set('description', 'Ditemukan seorang lansia terlantar membutuhkan pertolongan evakuasi dan perawatan darurat.')
            ->set('attachments', [$file])
            ->set('agreement', true)
            ->call('submit')
            ->assertHasNoErrors();

        $ticket = $component->get('submittedTicket');
        $this->assertNotNull($ticket);
        $this->assertStringStartsWith('ADU-', $ticket);

        $this->assertDatabaseHas('complaints', [
            'complaint_number' => $ticket,
            'reporter_name' => 'Siti Rahmawati',
            'status' => ComplaintStatus::RECEIVED->value,
        ]);
    }

    public function test_ticket_tracking_with_security_verification(): void
    {
        $village = Village::firstOrFail();
        $serviceType = ServiceType::firstOrFail();

        $sr = ServiceRequest::create([
            'service_type_id' => $serviceType->id,
            'applicant_name' => 'Ahmad Santoso',
            'applicant_nik' => '3505012304850005',
            'family_card_number' => '3505012304850001',
            'address' => 'Jl. Mawar 4',
            'village_id' => $village->id,
            'phone' => '081298765432',
            'submitted_at' => now(),
            'status' => ServiceRequestStatus::SUBMITTED,
        ]);

        // Incorrect security digits (should fail)
        Livewire::test(TicketTracking::class)
            ->set('ticketNumber', $sr->request_number)
            ->set('securityDigits', '9999')
            ->call('trackTicket')
            ->assertSet('errorMessage', '4 digit verifikasi pengaman tidak cocok dengan data NIK atau Nomor HP pemohon.');

        // Correct security digits matching NIK (last 4 digits '0005')
        Livewire::test(TicketTracking::class)
            ->set('ticketNumber', $sr->request_number)
            ->set('securityDigits', '0005')
            ->call('trackTicket')
            ->assertSet('errorMessage', null)
            ->assertSet('trackingType', 'service_request')
            ->assertSee($sr->request_number);
    }

    public function test_certificate_verification_portal(): void
    {
        $village = Village::firstOrFail();
        $serviceType = ServiceType::where('code', 'DTSEN')->firstOrFail();

        $sr = ServiceRequest::create([
            'service_type_id' => $serviceType->id,
            'applicant_name' => 'Dewi Lestari',
            'applicant_nik' => '3505024401920002',
            'family_card_number' => '3505024401920001',
            'address' => 'Jl. Dahlia No. 5',
            'village_id' => $village->id,
            'phone' => '081234000111',
            'submitted_at' => now(),
            'status' => ServiceRequestStatus::ISSUED,
        ]);

        $purpose = DtsenPurpose::firstOrFail();

        $cert = DtsenCertificate::create([
            'service_request_id' => $sr->id,
            'dtsen_purpose_id' => $purpose->id,
            'subject_name' => 'Dewi Lestari',
            'subject_nik' => '3505024401920002',
            'relationship_to_applicant' => 'Diri Sendiri',
            'purpose_description' => 'Persyaratan Beasiswa Pendidikan',
            'decile' => 2,
            'issued_at' => now(),
            'valid_until' => now()->addDays(90),
        ]);

        // Search valid certificate
        Livewire::test(CertificateVerification::class)
            ->set('code', $cert->verification_code)
            ->call('verifyCode')
            ->assertSet('isValid', true)
            ->assertSet('isExpired', false)
            ->assertSee($cert->certificate_number)
            ->assertSee('Dewi Lestari');

        // Search invalid code
        Livewire::test(CertificateVerification::class)
            ->set('code', 'KODE-PALSU-99999')
            ->call('verifyCode')
            ->assertSet('isValid', false)
            ->assertSee('DOKUMEN TIDAK VALID / TIDAK TERDAFTAR');
    }
}
