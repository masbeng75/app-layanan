<?php

namespace Tests\Feature;

use App\Enums\ComplaintStatus;
use App\Enums\DocumentVerificationStatus;
use App\Enums\HandlingType;
use App\Enums\PbiReason;
use App\Enums\PublishStatus;
use App\Enums\ReferralStatus;
use App\Enums\RehabilitationCaseStatus;
use App\Enums\ServiceRequestStatus;
use App\Models\Assessment;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintCategory;
use App\Models\Disposition;
use App\Models\District;
use App\Models\DownloadableForm;
use App\Models\DtsenCertificate;
use App\Models\DtsenPurpose;
use App\Models\Faq;
use App\Models\InformationPage;
use App\Models\MonitoringRecord;
use App\Models\NumberSequence;
use App\Models\PageVisit;
use App\Models\PbiReactivation;
use App\Models\Referral;
use App\Models\ReferralInstitution;
use App\Models\RehabilitationCase;
use App\Models\SearchLog;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestDocument;
use App\Models\ServiceRequirement;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\Village;
use App\Models\WorkUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_master_wilayah_and_work_units(): void
    {
        $unit = WorkUnit::create([
            'name' => 'Bidang Perlindungan dan Jaminan Sosial',
            'is_active' => true,
        ]);

        $district = District::create([
            'code' => '35.05.01',
            'name' => 'Kanigoro',
        ]);

        $village = Village::create([
            'district_id' => $district->id,
            'code' => '35.05.01.2001',
            'name' => 'Satreyan',
        ]);

        $user = User::create([
            'name' => 'Operator Satreyan',
            'email' => 'operator.satreyan@blitarkab.go.id',
            'password' => 'secret123',
            'phone' => '081234567890',
            'nik' => '3505011234560001',
            'work_unit_id' => $unit->id,
            'district_id' => $district->id,
            'village_id' => $village->id,
            'is_active' => true,
        ]);

        $this->assertEquals('Kanigoro', $village->district->name);
        $this->assertEquals('Satreyan', $user->village->name);
        $this->assertEquals('Bidang Perlindungan dan Jaminan Sosial', $user->workUnit->name);
    }

    public function test_can_create_service_request_with_dtsen_certificate(): void
    {
        $district = District::create(['code' => '35.05.02', 'name' => 'Garum']);
        $village = Village::create(['district_id' => $district->id, 'code' => '35.05.02.2001', 'name' => 'Tawangsari']);

        $serviceType = ServiceType::create([
            'code' => 'DTSEN',
            'name' => 'Surat Keterangan DTSEN',
            'category' => 'Layanan Kependudukan & DTKS',
            'handler' => 'dtsen',
        ]);

        $req = ServiceRequirement::create([
            'service_type_id' => $serviceType->id,
            'name' => 'Kartu Keluarga (KK)',
            'is_mandatory' => true,
        ]);

        $ticketNo = NumberSequence::nextFormattedNumber('DTSEN');

        $request = ServiceRequest::create([
            'request_number' => $ticketNo,
            'service_type_id' => $serviceType->id,
            'applicant_name' => 'Budi Santoso',
            'applicant_nik' => '3505021234560001',
            'family_card_number' => '3505021234560002',
            'address' => 'Jl. Merdeka No. 10',
            'village_id' => $village->id,
            'phone' => '081298765432',
            'status' => ServiceRequestStatus::SUBMITTED,
        ]);

        $doc = ServiceRequestDocument::create([
            'service_request_id' => $request->id,
            'service_requirement_id' => $req->id,
            'file_path' => 'documents/kk_sample.pdf',
            'original_name' => 'kk_sample.pdf',
            'verification_status' => DocumentVerificationStatus::VALID,
        ]);

        $purpose = DtsenPurpose::create([
            'code' => 'spmb',
            'name' => 'SPMB Jalur Afirmasi',
            'max_decile' => 5,
        ]);

        $cert = DtsenCertificate::create([
            'service_request_id' => $request->id,
            'dtsen_purpose_id' => $purpose->id,
            'subject_name' => 'Ahmad Santoso',
            'subject_nik' => '3505021234560003',
            'relationship_to_applicant' => 'Anak Kandung',
            'is_registered' => true,
            'decile' => 2,
            'verification_code' => 'DTSEN-VERIFY-123456',
        ]);

        $this->assertEquals($ticketNo, $request->request_number);
        $this->assertEquals(ServiceRequestStatus::SUBMITTED, $request->status);
        $this->assertEquals('spmb', $request->dtsenCertificate->purpose->code);
        $this->assertCount(1, $request->documents);
    }

    public function test_can_create_pbi_reactivation(): void
    {
        $district = District::create(['code' => '35.05.03', 'name' => 'Sutojayan']);
        $village = Village::create(['district_id' => $district->id, 'code' => '35.05.03.2001', 'name' => 'Kalipang']);

        $serviceType = ServiceType::create([
            'code' => 'PBI',
            'name' => 'Reaktivasi KIS / PBI-JK',
            'category' => 'Jaminan Kesehatan',
            'handler' => 'pbi',
        ]);

        $request = ServiceRequest::create([
            'request_number' => NumberSequence::nextFormattedNumber('PBI'),
            'service_type_id' => $serviceType->id,
            'applicant_name' => 'Siti Aminah',
            'applicant_nik' => '3505031234560001',
            'family_card_number' => '3505031234560002',
            'address' => 'Dusun Krajan',
            'village_id' => $village->id,
            'phone' => '081234567800',
            'status' => ServiceRequestStatus::DOCUMENT_CHECK,
            'is_priority' => true,
        ]);

        $pbi = PbiReactivation::create([
            'service_request_id' => $request->id,
            'participant_name' => 'Siti Aminah',
            'participant_nik' => '3505031234560001',
            'bpjs_card_number' => '0001234567890',
            'reason' => PbiReason::EMERGENCY,
            'decile' => 1,
        ]);

        $this->assertEquals(PbiReason::EMERGENCY, $request->pbiReactivation->reason);
        $this->assertTrue($request->is_priority);
    }

    public function test_can_create_rehabilitation_case_with_assessment_and_referral(): void
    {
        $district = District::create(['code' => '35.05.04', 'name' => 'Talun']);
        $village = Village::create(['district_id' => $district->id, 'code' => '35.05.04.2001', 'name' => 'Duren']);

        $category = ClientCategory::create(['name' => 'Lansia Terlantar']);

        $client = Client::create([
            'name' => 'Mbah Sukirno',
            'client_category_id' => $category->id,
            'gender' => 'male',
            'address' => 'RT 01 RW 02 Duren',
            'village_id' => $village->id,
        ]);

        $officer = User::create([
            'name' => 'Petugas Rehsos',
            'email' => 'rehsos@blitarkab.go.id',
            'password' => 'secret123',
        ]);

        $case = RehabilitationCase::create([
            'case_number' => NumberSequence::nextFormattedNumber('RHS'),
            'client_id' => $client->id,
            'officer_id' => $officer->id,
            'handling_type' => HandlingType::REFERRAL,
            'status' => RehabilitationCaseStatus::ASSESSMENT,
            'received_at' => now(),
        ]);

        $assessment = Assessment::create([
            'rehabilitation_case_id' => $case->id,
            'officer_id' => $officer->id,
            'assessment_date' => now(),
            'result' => 'Lansia sebatang kara membutuhkan perawatan panti',
            'service_needs' => 'Perawatan panti lansia',
            'recommendation' => 'Rujuk ke Balai Pelayanan Sosial Tresna Werdha',
            'needs_referral' => true,
        ]);

        $institution = ReferralInstitution::create([
            'name' => 'Balai Pelayanan Sosial Tresna Werdha',
            'type' => 'balai',
            'address' => 'Blitar',
        ]);

        $referral = Referral::create([
            'referral_number' => NumberSequence::nextFormattedNumber('RJK'),
            'rehabilitation_case_id' => $case->id,
            'assessment_id' => $assessment->id,
            'referral_institution_id' => $institution->id,
            'officer_id' => $officer->id,
            'referral_date' => now(),
            'status' => ReferralStatus::SENT,
        ]);

        $monitoring = MonitoringRecord::create([
            'rehabilitation_case_id' => $case->id,
            'referral_id' => $referral->id,
            'officer_id' => $officer->id,
            'monitoring_date' => now(),
            'progress' => 'Klien telah diterima dan menempati asrama dengan baik',
        ]);

        $this->assertEquals('Lansia Terlantar', $case->client->category->name);
        $this->assertCount(1, $case->assessments);
        $this->assertCount(1, $case->referrals);
        $this->assertCount(1, $case->monitoringRecords);
        $this->assertEquals('Balai Pelayanan Sosial Tresna Werdha', $case->referrals->first()->institution->name);
    }

    public function test_can_create_complaint_and_polymorphic_relations(): void
    {
        $district = District::create(['code' => '35.05.05', 'name' => 'Wlingi']);
        $village = Village::create(['district_id' => $district->id, 'code' => '35.05.05.2001', 'name' => 'Beru']);

        $category = ComplaintCategory::create(['name' => 'Penyandang Masalah Kesejahteraan Sosial (PMKS)']);

        $complaint = Complaint::create([
            'complaint_number' => NumberSequence::nextFormattedNumber('ADU'),
            'complaint_category_id' => $category->id,
            'reporter_name' => 'Warga Peduli',
            'reporter_phone' => '081234567899',
            'location_detail' => 'Dekat Pasar Wlingi',
            'village_id' => $village->id,
            'description' => 'Ditemukan seorang terlantar tanpa identitas',
            'reported_at' => now(),
            'status' => ComplaintStatus::RECEIVED,
        ]);

        ComplaintAttachment::create([
            'complaint_id' => $complaint->id,
            'file_path' => 'attachments/foto_lokasi.jpg',
            'type' => 'photo',
        ]);

        $officer = User::create([
            'name' => 'Petugas Verifikasi',
            'email' => 'verifikator@blitarkab.go.id',
            'password' => 'secret123',
        ]);

        $unit = WorkUnit::create(['name' => 'Seksi Rehabilitasi Sosial']);

        // Polymorphic Status History
        $complaint->statusHistories()->create([
            'from_status' => null,
            'to_status' => ComplaintStatus::RECEIVED->value,
            'notes' => 'Laporan masuk dari portal publik',
            'user_id' => null,
        ]);

        // Polymorphic Disposition
        $complaint->dispositions()->create([
            'from_user_id' => $officer->id,
            'to_work_unit_id' => $unit->id,
            'instructions' => 'Mohon ditindaklanjuti untuk assessment ke lokasi',
        ]);

        $this->assertEquals(ComplaintStatus::RECEIVED, $complaint->status);
        $this->assertCount(1, $complaint->attachments);
        $this->assertCount(1, $complaint->statusHistories);
        $this->assertCount(1, $complaint->dispositions);
        $this->assertEquals('Seksi Rehabilitasi Sosial', $complaint->dispositions->first()->toWorkUnit->name);
    }

    public function test_can_create_information_page_with_forms_and_faqs(): void
    {
        $manager = User::create([
            'name' => 'Admin Publikasi',
            'email' => 'publikasi@blitarkab.go.id',
            'password' => 'secret123',
        ]);

        $info = InformationPage::create([
            'title' => 'Panduan Pengajuan SK DTSEN',
            'slug' => 'panduan-pengajuan-sk-dtsen',
            'category' => 'program',
            'publish_status' => PublishStatus::PUBLISHED,
            'published_at' => now(),
            'manager_id' => $manager->id,
        ]);

        $form = DownloadableForm::create([
            'information_page_id' => $info->id,
            'name' => 'Formulir Permohonan SK DTSEN',
            'file_path' => 'forms/form_dtsen.pdf',
            'version' => '1.0',
            'is_current' => true,
        ]);

        $faq = Faq::create([
            'information_page_id' => $info->id,
            'question' => 'Berapa lama proses penerbitan SK DTSEN?',
            'answer' => 'Maksimal 3 hari kerja setelah berkas diverifikasi.',
            'sort_order' => 1,
        ]);

        PageVisit::create([
            'information_page_id' => $info->id,
            'visit_date' => now()->toDateString(),
            'visit_count' => 10,
        ]);

        SearchLog::create([
            'keyword' => 'DTSEN',
            'result_count' => 5,
            'searched_at' => now(),
        ]);

        $this->assertCount(1, $info->downloadableForms);
        $this->assertCount(1, $info->faqs);
        $this->assertCount(1, $info->pageVisits);
        $this->assertEquals(10, $info->pageVisits->first()->visit_count);
    }
}
