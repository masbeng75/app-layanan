<?php

namespace Database\Seeders;

use App\Enums\ApprovalDecision;
use App\Enums\ComplaintStatus;
use App\Enums\DocumentVerificationStatus;
use App\Enums\HandlingType;
use App\Enums\PbiReason;
use App\Enums\ReferralStatus;
use App\Enums\RehabilitationCaseStatus;
use App\Enums\ServiceRequestStatus;
use App\Models\Approval;
use App\Models\Assessment;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintCategory;
use App\Models\Disposition;
use App\Models\DtsenCertificate;
use App\Models\DtsenPurpose;
use App\Models\MonitoringRecord;
use App\Models\NumberSequence;
use App\Models\PbiReactivation;
use App\Models\Referral;
use App\Models\ReferralInstitution;
use App\Models\RehabilitationCase;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestDocument;
use App\Models\ServiceType;
use App\Models\StatusHistory;
use App\Models\User;
use App\Models\Village;
use App\Models\WorkUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentPeriod = Carbon::now()->format('Ym');

        // Master Data References
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->first();
        $kadis = User::where('email', 'kadis@dinsos.blitarkab.go.id')->first();
        $kabidLinjamsos = User::where('email', 'kabid.linjamsos@dinsos.blitarkab.go.id')->first();
        $kabidRehsos = User::where('email', 'kabid.rehsos@dinsos.blitarkab.go.id')->first();

        $petugasDtsen = User::where('email', 'petugas.dtsen@dinsos.blitarkab.go.id')->first();
        $petugasPbi = User::where('email', 'petugas.pbi@dinsos.blitarkab.go.id')->first();
        $petugasRehsos = User::where('email', 'petugas.rehsos@dinsos.blitarkab.go.id')->first();
        $petugasLayanan = User::where('email', 'petugas.layanan@dinsos.blitarkab.go.id')->first();

        $operatorSatreyan = User::where('email', 'operator.satreyan@blitarkab.go.id')->first();
        $operatorSawentar = User::where('email', 'operator.sawentar@blitarkab.go.id')->first();

        $wargaSiti = User::where('email', 'siti.aminah@gmail.com')->first();
        $wargaBambang = User::where('email', 'bambang.wijaya@gmail.com')->first();
        $wargaAgus = User::where('email', 'agus.santoso@gmail.com')->first();

        $linjamsosUnit = WorkUnit::where('name', 'like', '%Linjamsos%')->first();
        $rehsosUnit = WorkUnit::where('name', 'like', '%Rehsos%')->first();
        $sekretariatUnit = WorkUnit::where('name', 'like', '%Sekretariat%')->first();

        $satreyanVillage = Village::where('code', '35.05.08.1002')->first();
        $sawentarVillage = Village::where('code', '35.05.08.2003')->first();
        $beruVillage = Village::where('code', '35.05.12.1002')->first();
        $wlingiVillage = Village::where('code', '35.05.12.1001')->first();
        $dandongVillage = Village::where('code', '35.05.19.1003')->first();

        $dtsenType = ServiceType::where('code', 'DTSEN')->first();
        $pbiType = ServiceType::where('code', 'PBI')->first();
        $rehsosReqType = ServiceType::where('code', 'REHSOS_REQ')->first();

        $spmbPurpose = DtsenPurpose::where('code', 'spmb')->first();
        $kipPurpose = DtsenPurpose::where('code', 'kip_kuliah')->first();
        $pipPurpose = DtsenPurpose::where('code', 'pip')->first();

        $lutCategory = ClientCategory::where('name', 'like', '%Lanjut Usia Terlantar%')->first();
        $odgjCategory = ClientCategory::where('name', 'like', '%ODGJ%')->first();
        $disabilitasCategory = ClientCategory::where('name', 'like', '%Disabilitas%')->first();

        $pstwInstitution = ReferralInstitution::where('type', 'panti')->first();
        $rsudWlingiInstitution = ReferralInstitution::where('name', 'like', '%RSUD Ngudi Waluyo%')->first();

        $complaintCatOdgj = ComplaintCategory::where('name', 'like', '%ODGJ%')->first();
        $complaintCatLansia = ComplaintCategory::where('name', 'like', '%Lansia%')->first();
        $complaintCatBansos = ComplaintCategory::where('name', 'like', '%Bantuan Sosial%')->first();

        // 1. Number Sequences
        NumberSequence::firstOrCreate(
            ['prefix' => 'DTSEN', 'period' => $currentPeriod],
            ['last_number' => 4]
        );
        NumberSequence::firstOrCreate(
            ['prefix' => 'PBI', 'period' => $currentPeriod],
            ['last_number' => 3]
        );
        NumberSequence::firstOrCreate(
            ['prefix' => 'RHS', 'period' => $currentPeriod],
            ['last_number' => 3]
        );
        NumberSequence::firstOrCreate(
            ['prefix' => 'ADU', 'period' => $currentPeriod],
            ['last_number' => 4]
        );
        NumberSequence::firstOrCreate(
            ['prefix' => 'RJK', 'period' => $currentPeriod],
            ['last_number' => 2]
        );

        // ==========================================
        // 2. LAYANAN 1: SURAT KETERANGAN DTSEN
        // ==========================================

        // Kasus 1: Selesai / Terbit (Completed / Issued)
        $req1Number = "DTSEN-{$currentPeriod}-00001";
        $req1 = ServiceRequest::firstOrCreate(
            ['request_number' => $req1Number],
            [
                'service_type_id' => $dtsenType->id,
                'submitter_id' => $wargaSiti->id,
                'applicant_name' => 'Siti Aminah',
                'applicant_nik' => '3505084501900001',
                'family_card_number' => '3505080102030001',
                'address' => 'Jl. Brantas No. 14, RT 02 RW 01, Kelurahan Satreyan',
                'village_id' => $satreyanVillage->id,
                'phone' => '081298765001',
                'submitted_at' => now()->subDays(3),
                'officer_id' => $petugasDtsen->id,
                'work_unit_id' => $linjamsosUnit->id,
                'status' => ServiceRequestStatus::COMPLETED,
                'is_priority' => false,
                'verification_result' => 'Berkas KTP dan KK lengkap dan jelas. Pengecekan SIKS-NG menunjukkan pemohon terdaftar pada Desil 2.',
                'officer_notes' => 'Memenuhi syarat penerbitan SK DTSEN untuk keperluan SPMB Afirmasi (Batas desil maksimal 5).',
                'service_result' => 'Surat Keterangan DTSEN Nomor 400.9/101/409.105/2026 telah terbit dan dapat diunduh pemohon.',
                'completed_at' => now()->subDays(1),
            ]
        );

        // Dokumen Kasus 1
        foreach ($dtsenType->requirements as $req) {
            ServiceRequestDocument::firstOrCreate(
                [
                    'service_request_id' => $req1->id,
                    'service_requirement_id' => $req->id,
                ],
                [
                    'file_path' => 'documents/dtsen_req1_'.$req->id.'.pdf',
                    'original_name' => $req->name.'.pdf',
                    'verification_status' => DocumentVerificationStatus::VALID,
                    'notes' => 'Dokumen asli dan terbaca jelas.',
                ]
            );
        }

        // Sertifikat Kasus 1
        $cert1 = DtsenCertificate::firstOrCreate(
            ['service_request_id' => $req1->id],
            [
                'dtsen_purpose_id' => $spmbPurpose->id,
                'purpose_description' => 'Persyaratan pendaftaran SPMB Jalur Afirmasi SMAN 1 Talun',
                'subject_name' => 'Muhammad Rizki Pratama',
                'subject_nik' => '3505081203080002',
                'relationship_to_applicant' => 'Anak Kandung',
                'is_registered' => true,
                'decile' => 2,
                'checked_at' => now()->subDays(2),
                'checker_id' => $petugasDtsen->id,
                'certificate_number' => '400.9/101/409.105/2026',
                'issued_at' => now()->subDays(1),
                'valid_until' => now()->addDays(89)->toDateString(),
                'signer_id' => $kadis->id,
                'file_path' => 'certificates/sk_dtsen_400_9_101.pdf',
                'verification_code' => 'DTSEN-2026-9A8B7C',
            ]
        );

        // Approvals Berjenjang Kasus 1
        Approval::firstOrCreate(
            [
                'approvable_type' => DtsenCertificate::class,
                'approvable_id' => $cert1->id,
                'step' => 1,
            ],
            [
                'approver_id' => $kabidLinjamsos->id,
                'decision' => ApprovalDecision::APPROVED,
                'notes' => 'Hasil pengecekan SIKS-NG valid desil 2. Draf surat disetujui (paraf).',
                'decided_at' => now()->subDays(2)->addHours(2),
            ]
        );
        Approval::firstOrCreate(
            [
                'approvable_type' => DtsenCertificate::class,
                'approvable_id' => $cert1->id,
                'step' => 2,
            ],
            [
                'approver_id' => $kadis->id,
                'decision' => ApprovalDecision::APPROVED,
                'notes' => 'Surat disetujui dan ditandatangani secara digital.',
                'decided_at' => now()->subDays(1),
            ]
        );

        // Status History Kasus 1
        $req1Histories = [
            ['from' => null, 'to' => 'submitted', 'notes' => 'Pengajuan dibuat oleh pemohon secara online.', 'user' => $wargaSiti->id, 'time' => now()->subDays(3)],
            ['from' => 'submitted', 'to' => 'document_check', 'notes' => 'Pemeriksaan kelengkapan berkas KTP & KK.', 'user' => $petugasDtsen->id, 'time' => now()->subDays(2)->subHours(5)],
            ['from' => 'document_check', 'to' => 'data_verification', 'notes' => 'Berkas lengkap. Pengecekan data SIKS-NG oleh petugas.', 'user' => $petugasDtsen->id, 'time' => now()->subDays(2)->subHours(3)],
            ['from' => 'data_verification', 'to' => 'awaiting_approval', 'notes' => 'Hasil SIKS-NG Desil 2. Draf surat diajukan ke Kabid & Kadis.', 'user' => $petugasDtsen->id, 'time' => now()->subDays(2)],
            ['from' => 'awaiting_approval', 'to' => 'issued', 'notes' => 'Surat telah disetujui dan ditandatangani Kepala Dinas.', 'user' => $kadis->id, 'time' => now()->subDays(1)],
            ['from' => 'issued', 'to' => 'completed', 'notes' => 'Surat selesai diterbitkan dengan kode verifikasi DTSEN-2026-9A8B7C.', 'user' => $petugasDtsen->id, 'time' => now()->subDays(1)->addMinutes(10)],
        ];
        foreach ($req1Histories as $h) {
            StatusHistory::firstOrCreate(
                [
                    'statusable_type' => ServiceRequest::class,
                    'statusable_id' => $req1->id,
                    'to_status' => $h['to'],
                ],
                [
                    'from_status' => $h['from'],
                    'notes' => $h['notes'],
                    'user_id' => $h['user'],
                    'created_at' => $h['time'],
                ]
            );
        }

        // Kasus 2: Menunggu Persetujuan / Tanda Tangan (Awaiting Approval)
        $req2Number = "DTSEN-{$currentPeriod}-00002";
        $req2 = ServiceRequest::firstOrCreate(
            ['request_number' => $req2Number],
            [
                'service_type_id' => $dtsenType->id,
                'submitter_id' => $wargaBambang->id,
                'applicant_name' => 'Bambang Wijaya',
                'applicant_nik' => '3505121508820003',
                'family_card_number' => '3505120102030002',
                'address' => 'Lingkungan Beru RT 01 RW 04, Kelurahan Beru',
                'village_id' => $beruVillage->id,
                'phone' => '081298765002',
                'submitted_at' => now()->subDays(1),
                'officer_id' => $petugasDtsen->id,
                'work_unit_id' => $linjamsosUnit->id,
                'status' => ServiceRequestStatus::AWAITING_APPROVAL,
                'is_priority' => false,
                'verification_result' => 'Berkas lengkap. Terdaftar di SIKS-NG pada Desil 3.',
                'officer_notes' => 'Memenuhi syarat untuk pengajuan beasiswa KIP Kuliah (Desil 3 <= 4). Menunggu tanda tangan Kepala Dinas.',
            ]
        );

        $cert2 = DtsenCertificate::firstOrCreate(
            ['service_request_id' => $req2->id],
            [
                'dtsen_purpose_id' => $kipPurpose->id,
                'purpose_description' => 'Pengajuan Beasiswa KIP Kuliah Universitas Brawijaya',
                'subject_name' => 'Ahmad Dani Wijaya',
                'subject_nik' => '3505122104050001',
                'relationship_to_applicant' => 'Anak Kandung',
                'is_registered' => true,
                'decile' => 3,
                'checked_at' => now()->subHours(10),
                'checker_id' => $petugasDtsen->id,
                'verification_code' => 'DTSEN-2026-8B3C1F',
            ]
        );

        Approval::firstOrCreate(
            [
                'approvable_type' => DtsenCertificate::class,
                'approvable_id' => $cert2->id,
                'step' => 1,
            ],
            [
                'approver_id' => $kabidLinjamsos->id,
                'decision' => ApprovalDecision::APPROVED,
                'notes' => 'Verifikasi data valid. Rekomendasi disetujui, diteruskan ke Kepala Dinas.',
                'decided_at' => now()->subHours(4),
            ]
        );
        Approval::firstOrCreate(
            [
                'approvable_type' => DtsenCertificate::class,
                'approvable_id' => $cert2->id,
                'step' => 2,
            ],
            [
                'approver_id' => $kadis->id,
                'decision' => ApprovalDecision::PENDING,
                'notes' => null,
            ]
        );

        // Kasus 3: Pengecekan SIKS-NG (Data Verification)
        $req3Number = "DTSEN-{$currentPeriod}-00003";
        $req3 = ServiceRequest::firstOrCreate(
            ['request_number' => $req3Number],
            [
                'service_type_id' => $dtsenType->id,
                'submitter_id' => $wargaAgus->id,
                'applicant_name' => 'Agus Santoso',
                'applicant_nik' => '3505191206780004',
                'family_card_number' => '3505190102030003',
                'address' => 'Kelurahan Dandong RT 03 RW 02, Srengat',
                'village_id' => $dandongVillage->id,
                'phone' => '081298765003',
                'submitted_at' => now()->subHours(5),
                'officer_id' => $petugasDtsen->id,
                'work_unit_id' => $linjamsosUnit->id,
                'status' => ServiceRequestStatus::DATA_VERIFICATION,
                'is_priority' => false,
                'verification_result' => 'Berkas valid. Sedang dilakukan pencocokan data NIK di aplikasi SIKS-NG.',
            ]
        );

        DtsenCertificate::firstOrCreate(
            ['service_request_id' => $req3->id],
            [
                'dtsen_purpose_id' => $pipPurpose->id,
                'purpose_description' => 'Pencairan Program Indonesia Pintar (PIP) SMP',
                'subject_name' => 'Dewi Lestari Santoso',
                'subject_nik' => '3505194508110002',
                'relationship_to_applicant' => 'Anak Kandung',
                'is_registered' => false,
                'verification_code' => 'DTSEN-2026-1C2D3E',
            ]
        );

        // Kasus 4: Ditolak (Rejected - Desil Melebihi Batas)
        $req4Number = "DTSEN-{$currentPeriod}-00004";
        $req4 = ServiceRequest::firstOrCreate(
            ['request_number' => $req4Number],
            [
                'service_type_id' => $dtsenType->id,
                'submitter_id' => $operatorSawentar->id,
                'applicant_name' => 'Joko Prasetyo',
                'applicant_nik' => '3505081005850005',
                'family_card_number' => '3505080102030004',
                'address' => 'Desa Sawentar RT 04 RW 02, Kec. Kanigoro',
                'village_id' => $sawentarVillage->id,
                'phone' => '081399887766',
                'submitted_at' => now()->subDays(2),
                'officer_id' => $petugasDtsen->id,
                'work_unit_id' => $linjamsosUnit->id,
                'status' => ServiceRequestStatus::REJECTED,
                'is_priority' => false,
                'verification_result' => 'Hasil cek SIKS-NG: NIK terdaftar pada Desil 7.',
                'officer_notes' => 'Desil 7 masuk kategori keluarga mampu. Batas maksimal desil untuk SPMB Afirmasi adalah Desil 5.',
                'rejection_reason' => 'Berdasarkan data SIKS-NG Kemensos RI, pemohon terdaftar pada Desil 7 (mampu), melebihi batas ketentuan SPMB Afirmasi (maksimal Desil 5). Pemohon disarankan mengusulkan pemutakhiran data ekonomi lewat Musdes jika terjadi perubahan kondisi riil.',
                'completed_at' => now()->subDays(1),
            ]
        );

        DtsenCertificate::firstOrCreate(
            ['service_request_id' => $req4->id],
            [
                'dtsen_purpose_id' => $spmbPurpose->id,
                'purpose_description' => 'SPMB Jalur Afirmasi',
                'subject_name' => 'Bima Arya Prasetyo',
                'subject_nik' => '3505081909100003',
                'relationship_to_applicant' => 'Anak Kandung',
                'is_registered' => true,
                'decile' => 7,
                'checked_at' => now()->subDays(1),
                'checker_id' => $petugasDtsen->id,
                'verification_code' => 'DTSEN-2026-REJ001',
            ]
        );

        // ==========================================
        // 3. LAYANAN 2: REAKTIVASI KIS / PBI-JK
        // ==========================================

        // Kasus PBI 1: Selesai / Aktif Kembali (Completed / Reactivated)
        $pbi1Number = "PBI-{$currentPeriod}-00001";
        $pbi1 = ServiceRequest::firstOrCreate(
            ['request_number' => $pbi1Number],
            [
                'service_type_id' => $pbiType->id,
                'submitter_id' => $wargaSiti->id,
                'applicant_name' => 'Siti Aminah',
                'applicant_nik' => '3505084501900001',
                'family_card_number' => '3505080102030001',
                'address' => 'Jl. Brantas No. 14, RT 02 RW 01, Kelurahan Satreyan',
                'village_id' => $satreyanVillage->id,
                'phone' => '081298765001',
                'submitted_at' => now()->subDays(14),
                'officer_id' => $petugasPbi->id,
                'work_unit_id' => $linjamsosUnit->id,
                'status' => ServiceRequestStatus::COMPLETED,
                'is_priority' => false,
                'verification_result' => 'Surat keterangan medis penyakit kronis valid dari RSUD Ngudi Waluyo Wlingi. Desil 2 di DTKS.',
                'officer_notes' => 'Usulan telah diinput ke SIKS-NG dan telah disetujui Kemensos RI.',
                'service_result' => 'Kepesertaan KIS PBI-JK nomor 0001827364819 telah aktif kembali per tanggal '.now()->subDays(2)->format('d/m/Y').'.',
                'completed_at' => now()->subDays(2),
            ]
        );

        $reactivation1 = PbiReactivation::firstOrCreate(
            ['service_request_id' => $pbi1->id],
            [
                'participant_name' => 'Supriyanto',
                'participant_nik' => '3505081011880001',
                'bpjs_card_number' => '0001827364819',
                'deactivated_date' => now()->subMonths(2)->toDateString(),
                'reason' => PbiReason::CHRONIC,
                'health_facility_name' => 'RSUD Ngudi Waluyo Wlingi',
                'health_letter_number' => '445/782/RSUD/2026',
                'decile' => 2,
                'eligibility_notes' => 'Pasien membutuhkan hemodialisa (cuci darah) rutin 2 kali seminggu. Kondisi mendesak.',
                'recommendation_number' => '400.9/102/409.105/2026',
                'recommendation_issued_at' => now()->subDays(11),
                'signer_id' => $kadis->id,
                'proposed_to_ministry_at' => now()->subDays(10),
                'ministry_decision' => 'approved',
                'ministry_decided_at' => now()->subDays(3),
                'reactivated_date' => now()->subDays(2)->toDateString(),
            ]
        );

        Approval::firstOrCreate(
            [
                'approvable_type' => PbiReactivation::class,
                'approvable_id' => $reactivation1->id,
                'step' => 1,
            ],
            [
                'approver_id' => $kabidLinjamsos->id,
                'decision' => ApprovalDecision::APPROVED,
                'notes' => 'Rekomendasi reaktivasi disetujui karena indikasi medis cuci darah.',
                'decided_at' => now()->subDays(12),
            ]
        );
        Approval::firstOrCreate(
            [
                'approvable_type' => PbiReactivation::class,
                'approvable_id' => $reactivation1->id,
                'step' => 2,
            ],
            [
                'approver_id' => $kadis->id,
                'decision' => ApprovalDecision::APPROVED,
                'notes' => 'Surat rekomendasi ditandatangani.',
                'decided_at' => now()->subDays(11),
            ]
        );

        // Kasus PBI 2: Diusulkan ke Kemensos - Prioritas Darurat Medis (Proposed to Ministry)
        $pbi2Number = "PBI-{$currentPeriod}-00002";
        $pbi2 = ServiceRequest::firstOrCreate(
            ['request_number' => $pbi2Number],
            [
                'service_type_id' => $pbiType->id,
                'submitter_id' => $wargaBambang->id,
                'applicant_name' => 'Bambang Wijaya',
                'applicant_nik' => '3505121508820003',
                'family_card_number' => '3505120102030002',
                'address' => 'Lingkungan Beru RT 01 RW 04, Kelurahan Beru',
                'village_id' => $beruVillage->id,
                'phone' => '081298765002',
                'submitted_at' => now()->subDays(4),
                'officer_id' => $petugasPbi->id,
                'work_unit_id' => $linjamsosUnit->id,
                'status' => ServiceRequestStatus::PROPOSED_TO_MINISTRY,
                'is_priority' => true, // DARURAT MEDIS!
                'verification_result' => 'Pasien serangan jantung akut di ICU RSUD Srengat. Desil 1 valid di DTSEN.',
                'officer_notes' => 'PRIORITAS TINGGI. Rekomendasi Kadis telah terbit dan telah diinput ke SIKS-NG Kemensos RI.',
            ]
        );

        PbiReactivation::firstOrCreate(
            ['service_request_id' => $pbi2->id],
            [
                'participant_name' => 'Siti Maryam',
                'participant_nik' => '3505126107600002',
                'bpjs_card_number' => '0001928374650',
                'deactivated_date' => now()->subMonths(1)->toDateString(),
                'reason' => PbiReason::EMERGENCY,
                'health_facility_name' => 'RSUD Srengat',
                'health_letter_number' => '445/112/RSUDS/2026',
                'decile' => 1,
                'eligibility_notes' => 'Pasien rawat inap darurat di ruang ICU RSUD Srengat.',
                'recommendation_number' => '400.9/108/409.105/2026',
                'recommendation_issued_at' => now()->subDays(3),
                'signer_id' => $kadis->id,
                'proposed_to_ministry_at' => now()->subDays(2),
                'ministry_decision' => 'pending',
            ]
        );

        // Kasus PBI 3: Pemeriksaan Berkas (Document Check)
        $pbi3Number = "PBI-{$currentPeriod}-00003";
        $pbi3 = ServiceRequest::firstOrCreate(
            ['request_number' => $pbi3Number],
            [
                'service_type_id' => $pbiType->id,
                'submitter_id' => $operatorSatreyan->id,
                'applicant_name' => 'Suparno',
                'applicant_nik' => '3505081104800002',
                'family_card_number' => '3505080102030008',
                'address' => 'Kelurahan Satreyan RT 01 RW 02, Kanigoro',
                'village_id' => $satreyanVillage->id,
                'phone' => '087711223344',
                'submitted_at' => now()->subHours(6),
                'officer_id' => $petugasPbi->id,
                'work_unit_id' => $linjamsosUnit->id,
                'status' => ServiceRequestStatus::DOCUMENT_CHECK,
                'is_priority' => false,
            ]
        );

        PbiReactivation::firstOrCreate(
            ['service_request_id' => $pbi3->id],
            [
                'participant_name' => 'Bayi Ny. Ririn (Bayi Baru Lahir)',
                'participant_nik' => '3505082009260001',
                'bpjs_card_number' => '0002134567890',
                'reason' => PbiReason::NEWBORN,
                'health_facility_name' => 'Puskesmas Kanigoro',
                'health_letter_number' => '445/09/PKM-KNG/2026',
            ]
        );

        // ==========================================
        // 4. LAYANAN 5: PENGADUAN & LAPORAN SOSIAL
        // ==========================================

        // Pengaduan 1: Selesai Ditangani (ODGJ Terlantar Pasar Wlingi) -> Sumber Kasus Rehsos 2!
        $adu1Number = "ADU-{$currentPeriod}-00001";
        $complaint1 = Complaint::firstOrCreate(
            ['complaint_number' => $adu1Number],
            [
                'complaint_category_id' => $complaintCatOdgj->id,
                'reporter_id' => $wargaBambang->id,
                'reporter_name' => 'Bambang Wijaya',
                'reporter_phone' => '081298765002',
                'location_detail' => 'Kompleks Pasar Sayur Wlingi, dekat jembatan rel kereta api',
                'village_id' => $wlingiVillage->id,
                'description' => 'Ada seorang pria tanpa identitas berumur sekitar 40 tahun diduga ODGJ terlantar dalam kondisi pakaian robek dan mengamuk melempar batu ke pedagang pasar.',
                'reported_at' => now()->subDays(2),
                'officer_id' => $petugasRehsos->id,
                'status' => ComplaintStatus::RESOLVED,
                'verification_result' => 'Laporan valid setelah dikonfirmasi ke Bhabinkamtibmas dan Lurah Wlingi.',
                'action_taken' => 'Tim Respon Cepat Dinsos bersama TKSK Wlingi, Satpol PP, dan Polsek telah mengamankan yang bersangkutan. Klien telah dievakuasi ke RSUD Ngudi Waluyo Wlingi untuk penanganan medis dan penstabilan kejiwaan. Kasus diteruskan ke Bagian Rehabilitasi Sosial.',
                'resolved_at' => now()->subDays(1),
            ]
        );

        ComplaintAttachment::firstOrCreate(
            ['complaint_id' => $complaint1->id, 'file_path' => 'complaints/odgj_pasar_wlingi.jpg'],
            ['type' => 'photo']
        );

        // Disposisi Pengaduan 1
        Disposition::firstOrCreate(
            [
                'dispositionable_type' => Complaint::class,
                'dispositionable_id' => $complaint1->id,
                'from_user_id' => $admin->id,
                'to_work_unit_id' => $rehsosUnit->id,
            ],
            [
                'to_user_id' => $petugasRehsos->id,
                'instructions' => 'Mohon segera koordinasikan evakuasi darurat bersama TKSK Wlingi dan Satpol PP.',
                'disposed_at' => now()->subDays(2)->addHour(),
            ]
        );

        // Pengaduan 2: Dalam Penanganan (Lansia Sebatang Kara di Satreyan)
        $adu2Number = "ADU-{$currentPeriod}-00002";
        $complaint2 = Complaint::firstOrCreate(
            ['complaint_number' => $adu2Number],
            [
                'complaint_category_id' => $complaintCatLansia->id,
                'reporter_id' => null,
                'reporter_name' => 'Warga Peduli Satreyan',
                'reporter_phone' => '085649123456',
                'location_detail' => 'Dusun Satreyan RT 02 RW 03, Kelurahan Satreyan, Kec. Kanigoro',
                'village_id' => $satreyanVillage->id,
                'description' => 'Mbah Warni (usia +/- 82 tahun) hidup sebatang kara di gubuk bambu tidak layak huni, sakit menahun tidak ada keluarga yang merawat. Membutuhkan bantuan sembako dan perawatan medis.',
                'reported_at' => now()->subDays(1),
                'officer_id' => $petugasRehsos->id,
                'status' => ComplaintStatus::IN_HANDLING,
                'verification_result' => 'Telah diverifikasi oleh Petugas Puskesos & Kelurahan Satreyan. Kondisi sangat membutuhkan bantuan.',
                'action_taken' => 'Pemberian paket sembako darurat dan penjadwalan assessment komprehensif untuk rujukan panti lansia.',
            ]
        );

        // Pengaduan 3: Duplikat dari Pengaduan 2
        $adu3Number = "ADU-{$currentPeriod}-00003";
        Complaint::firstOrCreate(
            ['complaint_number' => $adu3Number],
            [
                'complaint_category_id' => $complaintCatLansia->id,
                'reporter_id' => null,
                'reporter_name' => 'Ketua RT 02 Satreyan',
                'reporter_phone' => '087756123456',
                'location_detail' => 'RT 02 RW 03 Satreyan Kanigoro',
                'village_id' => $satreyanVillage->id,
                'description' => 'Laporan lansia terlantar atas nama Mbah Warni.',
                'reported_at' => now()->subHours(18),
                'officer_id' => $petugasLayanan->id,
                'status' => ComplaintStatus::DUPLICATE,
                'duplicate_of_id' => $complaint2->id,
                'action_taken' => "Digabungkan dengan laporan terdahulu ({$adu2Number}) yang sedang ditangani Bidang Rehsos.",
                'resolved_at' => now()->subHours(10),
            ]
        );

        // Pengaduan 4: Laporan Baru Masuk (Received)
        $adu4Number = "ADU-{$currentPeriod}-00004";
        Complaint::firstOrCreate(
            ['complaint_number' => $adu4Number],
            [
                'complaint_category_id' => $complaintCatBansos->id,
                'reporter_id' => $wargaAgus->id,
                'reporter_name' => 'Agus Santoso',
                'reporter_phone' => '081298765003',
                'location_detail' => 'Dusun Sawentar Selatan RT 03 RW 01, Kanigoro',
                'village_id' => $sawentarVillage->id,
                'description' => 'Ada penerima bansos PKH yang memiliki mobil pribadi dan rumah mewah, sementara tetangganya yang lansia miskin tidak dapat.',
                'reported_at' => now()->subHours(4),
                'officer_id' => null,
                'status' => ComplaintStatus::RECEIVED,
            ]
        );

        // ==========================================
        // 5. LAYANAN 3: REHABILITASI SOSIAL
        // ==========================================

        // Klien 1: Lansia Terlantar Mbah Supardi
        $client1 = Client::firstOrCreate(
            ['nik' => '3505081203420001'],
            [
                'name' => 'Mbah Supardi',
                'client_category_id' => $lutCategory->id,
                'birth_date' => '1942-03-12',
                'gender' => 'male',
                'address' => 'Dusun Sawentar RT 01 RW 01',
                'village_id' => $sawentarVillage->id,
                'phone' => null,
            ]
        );

        // Kasus 1: Kasus Selesai (Closed) - Pelayanan Langsung & Rujukan ke PSTW
        $case1Number = "RHS-{$currentPeriod}-00001";
        $case1 = RehabilitationCase::firstOrCreate(
            ['case_number' => $case1Number],
            [
                'client_id' => $client1->id,
                'service_request_id' => null,
                'complaint_id' => null,
                'officer_id' => $petugasRehsos->id,
                'handling_type' => HandlingType::BOTH,
                'status' => RehabilitationCaseStatus::CLOSED,
                'handling_result' => 'Klien telah di-assessment dan difasilitasi rujukan ke UPT PSTW Blitar. Kondisi klien stabil, kebutuhan dasar terpenuhi, dan mendapatkan perawatan lanjut usia secara layak.',
                'received_at' => now()->subDays(20),
                'closed_at' => now()->subDays(2),
            ]
        );

        // Assessment Kasus 1
        $assessment1 = Assessment::firstOrCreate(
            ['rehabilitation_case_id' => $case1->id],
            [
                'officer_id' => $petugasRehsos->id,
                'assessment_date' => now()->subDays(19)->toDateString(),
                'result' => 'Klien berusia 84 tahun, hidup sebatang kara tanpa sanak keluarga. Mengalami penurunan fisik dan keterbatasan mobilitas.',
                'service_needs' => 'Tempat tinggal permanen yang ramah lansia, pemenuhan gizi, pakaian, serta pemantauan kesehatan rutin.',
                'recommendation' => 'Direkomendasikan untuk dirujuk ke Panti Sosial Tresna Werdha (PSTW) Blitar.',
                'needs_referral' => true,
            ]
        );

        // Rujukan Kasus 1 ke UPT PSTW Blitar
        $ref1Number = "RJK-{$currentPeriod}-00001";
        $referral1 = Referral::firstOrCreate(
            ['referral_number' => $ref1Number],
            [
                'rehabilitation_case_id' => $case1->id,
                'assessment_id' => $assessment1->id,
                'referral_institution_id' => $pstwInstitution->id,
                'officer_id' => $petugasRehsos->id,
                'referral_date' => now()->subDays(18)->toDateString(),
                'status' => ReferralStatus::COMPLETED,
                'service_result' => 'Klien diterima dengan baik di Wisma Kenanga PSTW Blitar dan telah beradaptasi dengan lingkungan panti.',
                'completed_at' => now()->subDays(5),
            ]
        );

        // Monitoring Kasus 1
        MonitoringRecord::firstOrCreate(
            [
                'rehabilitation_case_id' => $case1->id,
                'referral_id' => $referral1->id,
            ],
            [
                'officer_id' => $petugasRehsos->id,
                'monitoring_date' => now()->subDays(10)->toDateString(),
                'progress' => 'Kunjungan monitoring pertama bersama TKSK Kanigoro ke PSTW Blitar.',
                'result_notes' => 'Kondisi klien ceria, nafsu makan baik, mengikuti kegiatan senam lansia dan bimbingan keagamaan rutin.',
            ]
        );

        // Klien 2: ODGJ Terlantar Pasar Wlingi (Dari Pengaduan ADU-202609-00001)
        $client2 = Client::firstOrCreate(
            ['name' => 'Mr. X (ODGJ Terlantar Pasar Wlingi)', 'village_id' => $wlingiVillage->id],
            [
                'client_category_id' => $odgjCategory->id,
                'nik' => null,
                'birth_date' => null,
                'gender' => 'male',
                'address' => 'Ditemukan di Kompleks Pasar Sayur Wlingi',
                'phone' => null,
            ]
        );

        // Kasus 2: Dalam Pelayanan (In Service) - Dirujuk ke RSUD Ngudi Waluyo Wlingi
        $case2Number = "RHS-{$currentPeriod}-00002";
        $case2 = RehabilitationCase::firstOrCreate(
            ['case_number' => $case2Number],
            [
                'client_id' => $client2->id,
                'service_request_id' => null,
                'complaint_id' => $complaint1->id,
                'officer_id' => $petugasRehsos->id,
                'handling_type' => HandlingType::REFERRAL,
                'status' => RehabilitationCaseStatus::IN_SERVICE,
                'received_at' => now()->subDays(1),
            ]
        );

        $assessment2 = Assessment::firstOrCreate(
            ['rehabilitation_case_id' => $case2->id],
            [
                'officer_id' => $petugasRehsos->id,
                'assessment_date' => now()->subDays(1)->toDateString(),
                'result' => 'Klien disorientasi tempat dan waktu, tidak dapat diajak komunikasi dua arah, riwayat mengamuk.',
                'service_needs' => 'Penanganan kegawatdaruratan psikiatri, penstabilan emosi, dan terapi obat.',
                'recommendation' => 'Rujukan rawat inap ke Ruang Jiwa RSUD Ngudi Waluyo Wlingi.',
                'needs_referral' => true,
            ]
        );

        $ref2Number = "RJK-{$currentPeriod}-00002";
        Referral::firstOrCreate(
            ['referral_number' => $ref2Number],
            [
                'rehabilitation_case_id' => $case2->id,
                'assessment_id' => $assessment2->id,
                'referral_institution_id' => $rsudWlingiInstitution->id,
                'officer_id' => $petugasRehsos->id,
                'referral_date' => now()->subDays(1)->toDateString(),
                'status' => ReferralStatus::IN_SERVICE,
                'service_result' => 'Klien sedang dalam perawatan intensif ruang jiwa RSUD Ngudi Waluyo Wlingi.',
            ]
        );

        // Klien 3: Disabilitas Fisik (Tahap Asesmen)
        $client3 = Client::firstOrCreate(
            ['nik' => '3505191506990002'],
            [
                'name' => 'Rahmat Hidayat',
                'client_category_id' => $disabilitasCategory->id,
                'birth_date' => '1999-06-15',
                'gender' => 'male',
                'address' => 'Kelurahan Dandong RT 01 RW 01, Srengat',
                'village_id' => $dandongVillage->id,
                'phone' => '085712349988',
            ]
        );

        $case3Number = "RHS-{$currentPeriod}-00003";
        $case3 = RehabilitationCase::firstOrCreate(
            ['case_number' => $case3Number],
            [
                'client_id' => $client3->id,
                'service_request_id' => null,
                'complaint_id' => null,
                'officer_id' => $petugasRehsos->id,
                'handling_type' => HandlingType::DIRECT,
                'status' => RehabilitationCaseStatus::ASSESSMENT,
                'received_at' => now()->subHours(12),
            ]
        );

        Assessment::firstOrCreate(
            ['rehabilitation_case_id' => $case3->id],
            [
                'officer_id' => $petugasRehsos->id,
                'assessment_date' => now()->toDateString(),
                'result' => 'Klien mengalami kelumpuhan akibat kecelakaan, memerlukan alat bantu mobilitas kursi roda.',
                'service_needs' => 'Bantuan kursi roda dan pelatihan kewirausahaan mandiri bagi disabilitas.',
                'recommendation' => 'Diusulkan pemberian bantuan kursi roda melalui APBD Dinas Sosial Kabupaten Blitar.',
                'needs_referral' => false,
            ]
        );
    }
}
