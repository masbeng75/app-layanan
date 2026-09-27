<?php

namespace Tests\Feature;

use App\Enums\ComplaintStatus;
use App\Enums\PbiReason;
use App\Enums\ServiceRequestStatus;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\DtsenCertificate;
use App\Models\DtsenPurpose;
use App\Models\PbiReactivation;
use App\Models\ServiceRequest;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\Village;
use App\Services\CertificatePdfService;
use App\Services\DtsenDuplicateCheckService;
use App\Services\NumberSequenceService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BusinessFeaturesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! User::first()) {
            $this->seed();
        }
    }

    public function test_automatic_number_sequence_generates_formatted_sequential_numbers(): void
    {
        $num1 = NumberSequenceService::generate('TEST');
        $num2 = NumberSequenceService::generate('TEST');

        $period = Carbon::now()->format('Ym');
        $this->assertStringStartsWith("TEST-{$period}-", $num1);
        $this->assertStringStartsWith("TEST-{$period}-", $num2);
        $this->assertNotEquals($num1, $num2);

        // Test model auto-generation
        $serviceType = ServiceType::firstOrFail();
        $village = Village::firstOrFail();

        $req = ServiceRequest::create([
            'service_type_id' => $serviceType->id,
            'applicant_name' => 'Pemohon Auto No',
            'applicant_nik' => '3505081111220001',
            'family_card_number' => '3505081111220002',
            'address' => 'Jl. Merdeka No. 1',
            'village_id' => $village->id,
            'phone' => '081234567811',
            'status' => ServiceRequestStatus::SUBMITTED,
        ]);

        $this->assertNotNull($req->request_number);
        $this->assertStringStartsWith("REQ-{$period}-", $req->request_number);

        $category = ComplaintCategory::firstOrFail();

        $complaint = Complaint::create([
            'complaint_category_id' => $category->id,
            'reporter_name' => 'Pelapor Auto No',
            'reporter_phone' => '081234567822',
            'location_detail' => 'Dusun Krajan RT 01',
            'village_id' => $village->id,
            'description' => 'Laporan pengaduan jalan desa',
            'status' => ComplaintStatus::RECEIVED,
        ]);

        $this->assertNotNull($complaint->complaint_number);
        $this->assertStringStartsWith("ADU-{$period}-", $complaint->complaint_number);
    }

    public function test_dtsen_certificate_pdf_generation_with_qr_code(): void
    {
        $serviceRequest = ServiceRequest::firstOrFail();
        $purpose = DtsenPurpose::firstOrFail();

        $certificate = DtsenCertificate::updateOrCreate(
            ['service_request_id' => $serviceRequest->id],
            [
                'dtsen_purpose_id' => $purpose->id,
                'purpose_description' => 'Persyaratan Beasiswa Pendidikan',
                'subject_name' => 'Ahmad Rabbani',
                'subject_nik' => '3505081203050001',
                'relationship_to_applicant' => 'Anak Kandung',
                'is_registered' => true,
                'decile' => 2,
                'certificate_number' => NumberSequenceService::generateDtsenCertificateNumber(),
                'issued_at' => now(),
                'valid_until' => now()->addDays(30),
                'verification_code' => strtoupper(bin2hex(random_bytes(6))),
            ]
        );

        $filePath = CertificatePdfService::generateDtsenPdf($certificate);

        $this->assertTrue(Storage::disk('local')->exists($filePath));
        $content = Storage::disk('local')->get($filePath);
        $this->assertStringStartsWith('%PDF-', $content);

        Storage::disk('local')->delete($filePath);
    }

    public function test_pbi_recommendation_pdf_generation_with_qr_code(): void
    {
        $serviceRequest = ServiceRequest::firstOrFail();

        $pbi = PbiReactivation::updateOrCreate(
            ['service_request_id' => $serviceRequest->id],
            [
                'participant_name' => 'Budi Santoso',
                'participant_nik' => '3505081507780003',
                'bpjs_card_number' => '0001234567890',
                'reason' => PbiReason::CHRONIC,
                'health_facility_name' => 'RSUD Ngudi Waluyo Wlingi',
                'health_letter_number' => '445/789/RSUD/2026',
                'decile' => 1,
                'recommendation_number' => NumberSequenceService::generatePbiRecommendationNumber(),
                'recommendation_issued_at' => now(),
            ]
        );

        $filePath = CertificatePdfService::generatePbiPdf($pbi);

        $this->assertTrue(Storage::disk('local')->exists($filePath));
        $content = Storage::disk('local')->get($filePath);
        $this->assertStringStartsWith('%PDF-', $content);

        Storage::disk('local')->delete($filePath);
    }

    public function test_public_certificate_verification_page(): void
    {
        $serviceRequest = ServiceRequest::firstOrFail();
        $purpose = DtsenPurpose::firstOrFail();
        $verifCode = 'VERIF-TEST-'.time();

        $certificate = DtsenCertificate::updateOrCreate(
            ['service_request_id' => $serviceRequest->id],
            [
                'dtsen_purpose_id' => $purpose->id,
                'purpose_description' => 'Persyaratan KIP Kuliah',
                'subject_name' => 'Zahra Maharani',
                'subject_nik' => '3505085501060002',
                'relationship_to_applicant' => 'Diri Sendiri',
                'is_registered' => true,
                'decile' => 1,
                'certificate_number' => 'SK-TEST-'.time(),
                'issued_at' => now(),
                'valid_until' => now()->addDays(30),
                'verification_code' => $verifCode,
            ]
        );

        // 1. Valid Certificate
        $responseValid = $this->get(route('verification.show', ['verification_code' => $verifCode]));
        $responseValid->assertSuccessful();
        $responseValid->assertSeeText('DOKUMEN RESMI & TERVERIFIKASI SAH');
        $responseValid->assertSee('Zahra Maharani');
        $responseValid->assertSee('Desil 1');

        // 2. Invalid / Non-existent Certificate
        $responseInvalid = $this->get(route('verification.show', ['verification_code' => 'KODE-PALSU-999']));
        $responseInvalid->assertSuccessful();
        $responseInvalid->assertSeeText('DOKUMEN TIDAK TERDAFTAR / TIDAK VALID');
    }

    public function test_dtsen_duplicate_detection_detects_active_certificate(): void
    {
        $serviceRequest = ServiceRequest::firstOrFail();
        $purpose = DtsenPurpose::firstOrFail();
        $nik = '3505082005990008';

        DtsenCertificate::updateOrCreate(
            ['service_request_id' => $serviceRequest->id],
            [
                'dtsen_purpose_id' => $purpose->id,
                'subject_name' => 'Subjek Duplikasi Test',
                'subject_nik' => $nik,
                'relationship_to_applicant' => 'Diri Sendiri',
                'decile' => 2,
                'certificate_number' => 'SK-DUP-001',
                'issued_at' => now(),
                'valid_until' => now()->addDays(15),
                'verification_code' => 'DUP-CODE-001',
            ]
        );

        // Should detect duplicate
        $found = DtsenDuplicateCheckService::checkDuplicate($nik, $purpose->id);
        $this->assertNotNull($found);
        $this->assertEquals('SK-DUP-001', $found->certificate_number);

        // Should return null for different unused NIK
        $notFound = DtsenDuplicateCheckService::checkDuplicate('3505089999999999', $purpose->id);
        $this->assertNull($notFound);
    }

    public function test_stalled_tickets_console_command_flags_priority(): void
    {
        $serviceType = ServiceType::firstOrFail();
        $village = Village::firstOrFail();

        $req = ServiceRequest::create([
            'service_type_id' => $serviceType->id,
            'applicant_name' => 'Warga PBI Tertahan',
            'applicant_nik' => '3505081100990001',
            'family_card_number' => '3505081100990002',
            'address' => 'Jl. Stalled No. 1',
            'village_id' => $village->id,
            'phone' => '081234567855',
            'status' => ServiceRequestStatus::PROPOSED_TO_MINISTRY,
            'is_priority' => false,
        ]);

        $pbi = PbiReactivation::create([
            'service_request_id' => $req->id,
            'participant_name' => 'Warga PBI Tertahan',
            'participant_nik' => '3505081100990001',
            'bpjs_card_number' => '0009998887771',
            'reason' => PbiReason::CHRONIC,
            'proposed_to_ministry_at' => now()->subDays(20), // > 14 days
            'ministry_decision' => 'pending',
            'ministry_decided_at' => null,
        ]);

        $exitCode = Artisan::call('app:check-stalled-tickets', ['--days' => 14]);
        $this->assertEquals(0, $exitCode);

        $req->refresh();
        $this->assertTrue($req->is_priority);
    }

    public function test_pbi_emergency_reason_automatically_flags_service_request_priority(): void
    {
        $serviceType = ServiceType::firstOrFail();
        $village = Village::firstOrFail();

        $req = ServiceRequest::create([
            'service_type_id' => $serviceType->id,
            'applicant_name' => 'Pasien Darurat Medis',
            'applicant_nik' => '3505083344550001',
            'family_card_number' => '3505083344550002',
            'address' => 'Jl. Darurat No. 9',
            'village_id' => $village->id,
            'phone' => '081234567899',
            'status' => ServiceRequestStatus::SUBMITTED,
            'is_priority' => false,
        ]);

        $this->assertFalse($req->is_priority);

        PbiReactivation::create([
            'service_request_id' => $req->id,
            'participant_name' => 'Pasien Darurat Medis',
            'participant_nik' => '3505083344550001',
            'bpjs_card_number' => '0004445556661',
            'reason' => PbiReason::EMERGENCY, // Reason: Emergency
            'health_facility_name' => 'ICU RSUD Ngudi Waluyo',
        ]);

        $req->refresh();
        $this->assertTrue($req->is_priority);
    }
}
