<?php

namespace App\Livewire\Portal;

use App\Enums\DocumentVerificationStatus;
use App\Enums\PbiReason;
use App\Enums\ServiceRequestStatus;
use App\Models\District;
use App\Models\DtsenCertificate;
use App\Models\DtsenPurpose;
use App\Models\PbiReactivation;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestDocument;
use App\Models\ServiceType;
use App\Models\StatusHistory;
use App\Models\Village;
use App\Services\DtsenDuplicateCheckService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.portal')]
#[Title('Form Pengajuan Layanan Online — SAPA SOSIAL Kab. Blitar')]
class ServiceRequestSubmission extends Component
{
    use WithFileUploads;

    public int $currentStep = 1;

    #[Url(as: 'type')]
    public ?int $service_type_id = null;

    // Step 1: Service details
    // DTSEN specific
    public ?int $dtsen_purpose_id = null;

    public string $subject_name = '';

    public string $subject_nik = '';

    public string $relationship_to_applicant = 'Diri Sendiri';

    public ?string $duplicateWarning = null;

    // PBI specific
    public string $participant_name = '';

    public string $participant_nik = '';

    public string $bpjs_card_number = '';

    public string $pbi_reason = 'emergency';

    public string $health_facility_name = '';

    // Step 2: Applicant & Address details
    public bool $is_same_as_subject = false;

    public string $applicant_name = '';

    public string $applicant_nik = '';

    public string $family_card_number = '';

    public string $phone = '';

    public ?int $district_id = null;

    public ?int $village_id = null;

    public string $address = '';

    // Step 3: Documents
    /** @var array<int, mixed> */
    public array $uploads = [];

    // Step 4: Agreement
    public bool $agreement = false;

    // Completed State
    public ?string $submittedTicket = null;

    public ?ServiceRequest $createdRequest = null;

    public function mount(): void
    {
        if (! $this->service_type_id) {
            $defaultType = ServiceType::where('is_active', true)->first();
            $this->service_type_id = $defaultType?->id;
        }
    }

    public function updatedServiceTypeId(): void
    {
        $this->duplicateWarning = null;
        $this->uploads = [];
    }

    public function updatedDistrictId(): void
    {
        $this->village_id = null;
    }

    public function updatedSubjectNik(): void
    {
        $this->checkDtsenDuplicate();
    }

    public function updatedDtsenPurposeId(): void
    {
        $this->checkDtsenDuplicate();
    }

    public function checkDtsenDuplicate(): void
    {
        $this->duplicateWarning = null;
        if (strlen($this->subject_nik) >= 16) {
            $duplicate = DtsenDuplicateCheckService::checkDuplicate($this->subject_nik, $this->dtsen_purpose_id);
            if ($duplicate) {
                $expiry = $duplicate->valid_until ? $duplicate->valid_until->translatedFormat('d F Y') : 'Aktif';
                $this->duplicateWarning = "Perhatian: Ditemukan Surat Keterangan DTSEN aktif (No. {$duplicate->certificate_number}) atas nama {$duplicate->subject_name} yang berlaku hingga {$expiry}. Pengajuan baru berpotensi ditolak jika keperluan identik.";
            }
        }
    }

    public function updatedIsSameAsSubject(): void
    {
        if ($this->is_same_as_subject) {
            $serviceType = ServiceType::find($this->service_type_id);
            if ($serviceType && ($serviceType->handler === 'dtsen' || $serviceType->code === 'DTSEN')) {
                $this->applicant_name = $this->subject_name;
                $this->applicant_nik = $this->subject_nik;
            } elseif ($serviceType && ($serviceType->handler === 'pbi' || $serviceType->code === 'PBI')) {
                $this->applicant_name = $this->participant_name;
                $this->applicant_nik = $this->participant_nik;
            }
        }
    }

    public function goToStep(int $step): void
    {
        if ($step > $this->currentStep) {
            $this->validateCurrentStep();
        }
        $this->currentStep = $step;
    }

    public function nextStep(): void
    {
        $this->validateCurrentStep();
        $this->currentStep++;
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function validateCurrentStep(): void
    {
        $serviceType = ServiceType::with('requirements')->findOrFail($this->service_type_id);

        if ($this->currentStep === 1) {
            $rules = [
                'service_type_id' => ['required', 'exists:service_types,id'],
            ];

            if ($serviceType->handler === 'dtsen' || $serviceType->code === 'DTSEN') {
                $rules['dtsen_purpose_id'] = ['required', 'exists:dtsen_purposes,id'];
                $rules['subject_name'] = ['required', 'string', 'max:150'];
                $rules['subject_nik'] = ['required', 'numeric', 'digits:16'];
                $rules['relationship_to_applicant'] = ['required', 'string', 'max:50'];
            } elseif ($serviceType->handler === 'pbi' || $serviceType->code === 'PBI') {
                $rules['participant_name'] = ['required', 'string', 'max:150'];
                $rules['participant_nik'] = ['required', 'numeric', 'digits:16'];
                $rules['bpjs_card_number'] = ['required', 'string', 'max:30'];
                $rules['pbi_reason'] = ['required', 'string'];
                $rules['health_facility_name'] = ['nullable', 'string', 'max:150'];
            }

            $this->validate($rules, [
                'subject_nik.digits' => 'NIK harus berjumlah 16 digit.',
                'participant_nik.digits' => 'NIK Peserta harus berjumlah 16 digit.',
                'dtsen_purpose_id.required' => 'Pilih peruntukan surat keterangan DTSEN.',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'applicant_name' => ['required', 'string', 'max:150'],
                'applicant_nik' => ['required', 'numeric', 'digits:16'],
                'family_card_number' => ['required', 'numeric', 'digits:16'],
                'phone' => ['required', 'string', 'min:9', 'max:20'],
                'district_id' => ['required', 'exists:districts,id'],
                'village_id' => ['required', 'exists:villages,id'],
                'address' => ['required', 'string', 'max:300'],
            ], [
                'applicant_nik.digits' => 'NIK Pemohon harus 16 digit.',
                'family_card_number.digits' => 'Nomor Kartu Keluarga (KK) harus 16 digit.',
                'district_id.required' => 'Pilih Kecamatan tempat tinggal Anda.',
                'village_id.required' => 'Pilih Desa/Kelurahan tempat tinggal Anda.',
            ]);
        } elseif ($this->currentStep === 3) {
            $rules = [];
            $messages = [];

            foreach ($serviceType->requirements as $req) {
                if ($req->is_mandatory) {
                    $rules["uploads.{$req->id}"] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
                    $messages["uploads.{$req->id}.required"] = "Berkas '{$req->name}' wajib diunggah.";
                    $messages["uploads.{$req->id}.mimes"] = "Berkas '{$req->name}' harus berformat PDF, JPG, atau PNG.";
                    $messages["uploads.{$req->id}.max"] = "Ukuran berkas '{$req->name}' maksimal 5MB.";
                } else {
                    $rules["uploads.{$req->id}"] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
                }
            }

            $this->validate($rules, $messages);
        }
    }

    public function submit(): void
    {
        $this->validateCurrentStep();

        $this->validate([
            'agreement' => ['accepted'],
        ], [
            'agreement.accepted' => 'Anda harus menyetujui pernyataan keabsahan data sebelum mengirim permohonan.',
        ]);

        $serviceType = ServiceType::with('requirements')->findOrFail($this->service_type_id);

        DB::transaction(function () use ($serviceType) {
            $isPriority = false;
            if (($serviceType->handler === 'pbi' || $serviceType->code === 'PBI') && $this->pbi_reason === 'emergency') {
                $isPriority = true;
            }

            // 1. Create ServiceRequest
            $request = ServiceRequest::create([
                'service_type_id' => $serviceType->id,
                'submitter_id' => null, // Public citizen
                'applicant_name' => $this->applicant_name,
                'applicant_nik' => $this->applicant_nik,
                'family_card_number' => $this->family_card_number,
                'address' => $this->address,
                'village_id' => $this->village_id,
                'phone' => $this->phone,
                'submitted_at' => now(),
                'status' => ServiceRequestStatus::SUBMITTED,
                'is_priority' => $isPriority,
            ]);

            // 2. Create Child Details
            if ($serviceType->handler === 'dtsen' || $serviceType->code === 'DTSEN') {
                DtsenCertificate::create([
                    'service_request_id' => $request->id,
                    'dtsen_purpose_id' => $this->dtsen_purpose_id,
                    'subject_name' => $this->subject_name,
                    'subject_nik' => $this->subject_nik,
                    'relationship_to_applicant' => $this->relationship_to_applicant,
                ]);
            } elseif ($serviceType->handler === 'pbi' || $serviceType->code === 'PBI') {
                PbiReactivation::create([
                    'service_request_id' => $request->id,
                    'participant_name' => $this->participant_name,
                    'participant_nik' => $this->participant_nik,
                    'bpjs_card_number' => $this->bpjs_card_number,
                    'reason' => PbiReason::tryFrom($this->pbi_reason) ?? PbiReason::EMERGENCY,
                    'health_facility_name' => $this->health_facility_name ?: null,
                ]);
            }

            // 3. Upload & Attach Documents
            foreach ($this->uploads as $reqId => $file) {
                if ($file) {
                    $path = $file->store('service-requests', 'public');
                    ServiceRequestDocument::create([
                        'service_request_id' => $request->id,
                        'service_requirement_id' => $reqId,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'verification_status' => DocumentVerificationStatus::PENDING,
                    ]);
                }
            }

            // 4. Initial Status History
            StatusHistory::create([
                'statusable_type' => ServiceRequest::class,
                'statusable_id' => $request->id,
                'from_status' => null,
                'to_status' => ServiceRequestStatus::SUBMITTED->value,
                'notes' => 'Permohonan diajukan secara daring oleh pemohon melalui Portal Publik SAPA SOSIAL.',
                'user_id' => null,
            ]);

            $this->submittedTicket = $request->request_number;
            $this->createdRequest = $request->load(['serviceType', 'village.district']);
        });
    }

    public function resetForm(): void
    {
        $this->reset([
            'currentStep', 'dtsen_purpose_id', 'subject_name', 'subject_nik', 'relationship_to_applicant',
            'duplicateWarning', 'participant_name', 'participant_nik', 'bpjs_card_number', 'health_facility_name',
            'is_same_as_subject', 'applicant_name', 'applicant_nik', 'family_card_number', 'phone',
            'district_id', 'village_id', 'address', 'uploads', 'agreement', 'submittedTicket', 'createdRequest',
        ]);
        $this->currentStep = 1;
    }

    public function render()
    {
        $serviceTypes = ServiceType::where('is_active', true)->get();
        $selectedType = ServiceType::with('requirements')->find($this->service_type_id);
        $dtsenPurposes = DtsenPurpose::where('is_active', true)->get();
        $districts = District::orderBy('name')->get();
        $villages = $this->district_id ? Village::where('district_id', $this->district_id)->orderBy('name')->get() : collect();

        return view('livewire.portal.service-request-submission', [
            'serviceTypes' => $serviceTypes,
            'selectedType' => $selectedType,
            'dtsenPurposes' => $dtsenPurposes,
            'districts' => $districts,
            'villages' => $villages,
            'pbiReasons' => PbiReason::cases(),
        ]);
    }
}
