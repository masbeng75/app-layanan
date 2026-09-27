<?php

namespace App\Livewire\Portal;

use App\Models\Complaint;
use App\Models\ServiceRequest;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Lacak Status Tiket — SAPA SOSIAL Kab. Blitar')]
class TicketTracking extends Component
{
    #[Url(as: 'ticket')]
    public string $ticketNumber = '';

    public string $securityDigits = '';

    public ?string $trackingType = null; // 'service_request' or 'complaint'

    public ?ServiceRequest $serviceRequest = null;

    public ?Complaint $complaint = null;

    public ?string $errorMessage = null;

    public bool $hasSearched = false;

    public function mount(): void
    {
        if (! empty($this->ticketNumber) && ! empty($this->securityDigits)) {
            $this->trackTicket();
        }
    }

    public function trackTicket(): void
    {
        $this->validate([
            'ticketNumber' => ['required', 'string', 'min:5'],
            'securityDigits' => ['nullable', 'string', 'digits:4'],
        ], [
            'ticketNumber.required' => 'Nomor tiket wajib diisi.',
            'securityDigits.digits' => 'Digit pengaman harus 4 digit angka terakhir NIK atau No. HP.',
        ]);

        $this->hasSearched = true;
        $this->errorMessage = null;
        $this->serviceRequest = null;
        $this->complaint = null;
        $this->trackingType = null;

        $ticket = trim(strtoupper($this->ticketNumber));

        // 1. Try finding Service Request
        $sr = ServiceRequest::query()
            ->withoutGlobalScopes()
            ->with(['serviceType', 'village.district', 'dtsenCertificate.purpose', 'pbiReactivation', 'documents.requirement', 'statusHistories'])
            ->where('request_number', $ticket)
            ->first();

        if ($sr) {
            // Verify security digits if provided
            if (! empty($this->securityDigits)) {
                $nikMatch = substr((string) $sr->applicant_nik, -4) === $this->securityDigits;
                $phoneMatch = substr((string) $sr->phone, -4) === $this->securityDigits;

                if (! $nikMatch && ! $phoneMatch) {
                    $this->errorMessage = '4 digit verifikasi pengaman tidak cocok dengan data NIK atau Nomor HP pemohon.';

                    return;
                }
            }

            $this->trackingType = 'service_request';
            $this->serviceRequest = $sr;

            return;
        }

        // 2. Try finding Complaint
        $cmp = Complaint::query()
            ->withoutGlobalScopes()
            ->with(['category', 'village.district', 'attachments', 'statusHistories'])
            ->where('complaint_number', $ticket)
            ->first();

        if ($cmp) {
            // If complaint has reporter phone and security digits are given
            if (! empty($this->securityDigits) && ! empty($cmp->reporter_phone)) {
                $phoneMatch = substr((string) $cmp->reporter_phone, -4) === $this->securityDigits;
                if (! $phoneMatch) {
                    $this->errorMessage = '4 digit verifikasi pengaman tidak cocok dengan nomor telepon pelapor.';

                    return;
                }
            }

            $this->trackingType = 'complaint';
            $this->complaint = $cmp;

            return;
        }

        $this->errorMessage = 'Nomor tiket tidak ditemukan di dalam sistem. Pastikan format nomor tiket benar (Contoh: REQ-202609-00001 atau ADU-202609-00001).';
    }

    public function render()
    {
        return view('livewire.portal.ticket-tracking');
    }
}
