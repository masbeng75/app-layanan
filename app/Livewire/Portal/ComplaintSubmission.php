<?php

namespace App\Livewire\Portal;

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintCategory;
use App\Models\District;
use App\Models\StatusHistory;
use App\Models\Village;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.portal')]
#[Title('Form Pengaduan Masalah Sosial — SAPA SOSIAL Kab. Blitar')]
class ComplaintSubmission extends Component
{
    use WithFileUploads;

    public ?int $complaint_category_id = null;

    public bool $is_anonymous = false;

    public string $reporter_name = '';

    public string $reporter_phone = '';

    public ?int $district_id = null;

    public ?int $village_id = null;

    public string $location_detail = '';

    public string $description = '';

    /** @var array<int, mixed> */
    public array $attachments = [];

    public bool $agreement = false;

    // Submitted state
    public ?string $submittedTicket = null;

    public ?Complaint $createdComplaint = null;

    public function mount(): void
    {
        $defaultCat = ComplaintCategory::where('is_active', true)->first();
        $this->complaint_category_id = $defaultCat?->id;
    }

    public function updatedDistrictId(): void
    {
        $this->village_id = null;
    }

    public function updatedIsAnonymous(): void
    {
        if ($this->is_anonymous) {
            $this->reporter_name = 'Anonim / Warga Terlindungi';
            $this->reporter_phone = '';
        } else {
            $this->reporter_name = '';
        }
    }

    public function submit(): void
    {
        $rules = [
            'complaint_category_id' => ['required', 'exists:complaint_categories,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'village_id' => ['required', 'exists:villages,id'],
            'location_detail' => ['required', 'string', 'max:250'],
            'description' => ['required', 'string', 'min:20', 'max:2000'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,mp4', 'max:10240'],
            'agreement' => ['accepted'],
        ];

        if (! $this->is_anonymous) {
            $rules['reporter_name'] = ['required', 'string', 'max:150'];
            $rules['reporter_phone'] = ['required', 'string', 'min:9', 'max:20'];
        }

        $this->validate($rules, [
            'complaint_category_id.required' => 'Pilih kategori masalah sosial yang dilaporkan.',
            'district_id.required' => 'Pilih Kecamatan lokasi kejadian.',
            'village_id.required' => 'Pilih Desa/Kelurahan lokasi kejadian.',
            'location_detail.required' => 'Tuliskan patokan atau alamat lokasi kejadian secara spesifik.',
            'description.min' => 'Uraian kejadian minimal 20 karakter agar petugas memahami kronologi kasus.',
            'reporter_name.required' => 'Nama pelapor wajib diisi jika tidak memilih opsi anonim.',
            'reporter_phone.required' => 'Nomor kontak pelapor wajib diisi untuk verifikasi klarifikasi.',
            'agreement.accepted' => 'Anda harus menyetujui pernyataan kebenaran informasi laporan.',
        ]);

        DB::transaction(function () {
            // 1. Create Complaint
            $complaint = Complaint::create([
                'complaint_category_id' => $this->complaint_category_id,
                'reporter_id' => null,
                'reporter_name' => $this->is_anonymous ? 'Anonim' : $this->reporter_name,
                'reporter_phone' => $this->is_anonymous ? null : $this->reporter_phone,
                'location_detail' => $this->location_detail,
                'village_id' => $this->village_id,
                'description' => $this->description,
                'reported_at' => now(),
                'status' => ComplaintStatus::RECEIVED,
            ]);

            // 2. Upload attachments
            foreach ($this->attachments as $file) {
                if ($file) {
                    $path = $file->store('complaint-attachments', 'public');
                    $extension = strtolower($file->getClientOriginalExtension());
                    $type = in_array($extension, ['jpg', 'jpeg', 'png']) ? 'photo' : 'document';

                    ComplaintAttachment::create([
                        'complaint_id' => $complaint->id,
                        'file_path' => $path,
                        'type' => $type,
                    ]);
                }
            }

            // 3. Status History
            StatusHistory::create([
                'statusable_type' => Complaint::class,
                'statusable_id' => $complaint->id,
                'from_status' => null,
                'to_status' => ComplaintStatus::RECEIVED->value,
                'notes' => 'Pengaduan dikirim oleh warga melalui Portal Publik SAPA SOSIAL.',
                'user_id' => null,
            ]);

            $this->submittedTicket = $complaint->complaint_number;
            $this->createdComplaint = $complaint->load(['category', 'village.district']);
        });
    }

    public function resetForm(): void
    {
        $this->reset([
            'complaint_category_id', 'is_anonymous', 'reporter_name', 'reporter_phone',
            'district_id', 'village_id', 'location_detail', 'description', 'attachments',
            'agreement', 'submittedTicket', 'createdComplaint',
        ]);
        $defaultCat = ComplaintCategory::where('is_active', true)->first();
        $this->complaint_category_id = $defaultCat?->id;
    }

    public function render()
    {
        $categories = ComplaintCategory::where('is_active', true)->get();
        $districts = District::orderBy('name')->get();
        $villages = $this->district_id ? Village::where('district_id', $this->district_id)->orderBy('name')->get() : collect();

        return view('livewire.portal.complaint-submission', [
            'categories' => $categories,
            'districts' => $districts,
            'villages' => $villages,
        ]);
    }
}
