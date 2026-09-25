<?php

namespace App\Filament\Resources\ServiceRequests\Pages;

use App\Enums\ApprovalDecision;
use App\Enums\ServiceRequestStatus;
use App\Filament\Resources\ServiceRequests\ServiceRequestResource;
use App\Models\Approval;
use App\Models\ServiceRequest;
use App\Models\StatusHistory;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewServiceRequest extends ViewRecord
{
    protected static string $resource = ServiceRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),

            // 1. Verifikasi Berkas
            Action::make('verifyDocuments')
                ->label('Mulai Verifikasi Berkas')
                ->icon('heroicon-o-document-magnifying-glass')
                ->color('info')
                ->visible(fn (): bool => in_array($this->record->status, [
                    ServiceRequestStatus::SUBMITTED,
                    ServiceRequestStatus::REVISION_REQUESTED,
                ]))
                ->action(function (): void {
                    $this->transitionStatus(ServiceRequestStatus::DOCUMENT_CHECK, 'Memulai verifikasi kelengkapan berkas fisik/unggah.');
                    Notification::make()->title('Status diperbarui: Pemeriksaan Berkas')->success()->send();
                }),

            // 2. Minta Perbaikan Berkas
            Action::make('requestRevision')
                ->label('Minta Perbaikan Berkas')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn (): bool => in_array($this->record->status, [
                    ServiceRequestStatus::SUBMITTED,
                    ServiceRequestStatus::DOCUMENT_CHECK,
                    ServiceRequestStatus::DATA_VERIFICATION,
                ]))
                ->schema([
                    Textarea::make('notes')
                        ->label('Catatan Kekurangan / Dokumen yang Perlu Diperbaiki')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->transitionStatus(ServiceRequestStatus::REVISION_REQUESTED, $data['notes']);
                    Notification::make()->title('Permintaan perbaikan telah dikirim ke pemohon')->warning()->send();
                }),

            // 3. Verifikasi Data SIKS-NG
            Action::make('verifyData')
                ->label('Verifikasi Data SIKS-NG')
                ->icon('heroicon-o-check-badge')
                ->color('info')
                ->visible(fn (): bool => $this->record->status === ServiceRequestStatus::DOCUMENT_CHECK)
                ->schema(function (): array {
                    if ($this->record->dtsenCertificate) {
                        return [
                            Select::make('decile')
                                ->label('Peringkat Desil Hasil Cek SIKS-NG')
                                ->options([
                                    1 => 'Desil 1 (Sangat Miskin)',
                                    2 => 'Desil 2 (Miskin)',
                                    3 => 'Desil 3 (Hampir Miskin)',
                                    4 => 'Desil 4 (Rentan Miskin)',
                                    5 => 'Desil 5',
                                    6 => 'Desil 6',
                                    7 => 'Desil 7',
                                    8 => 'Desil 8',
                                    9 => 'Desil 9',
                                    10 => 'Desil 10',
                                ])
                                ->required(),
                            Textarea::make('notes')
                                ->label('Catatan Verifikator SIKS-NG'),
                        ];
                    }

                    return [
                        Textarea::make('notes')
                            ->label('Catatan Hasil Verifikasi Data')
                            ->required(),
                    ];
                })
                ->action(function (array $data): void {
                    if ($this->record->dtsenCertificate && isset($data['decile'])) {
                        $this->record->dtsenCertificate->update([
                            'decile' => $data['decile'],
                            'is_registered' => true,
                            'checked_at' => now(),
                            'checker_id' => Auth::id(),
                        ]);
                    }

                    $this->transitionStatus(ServiceRequestStatus::DATA_VERIFICATION, $data['notes'] ?? 'Data pemohon terverifikasi pada sistem SIKS-NG.');
                    Notification::make()->title('Data berhasil diverifikasi')->success()->send();
                }),

            // 4. Ajukan Persetujuan Paraf Kabid
            Action::make('submitApproval')
                ->label('Ajukan Persetujuan (Paraf Kabid)')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->visible(fn (): bool => in_array($this->record->status, [
                    ServiceRequestStatus::DATA_VERIFICATION,
                    ServiceRequestStatus::DOCUMENT_CHECK,
                ]))
                ->action(function (): void {
                    // Buat approval record tahap 1
                    Approval::create([
                        'approvable_type' => ServiceRequest::class,
                        'approvable_id' => $this->record->id,
                        'step' => 1,
                        'decision' => ApprovalDecision::PENDING,
                    ]);

                    $this->transitionStatus(ServiceRequestStatus::AWAITING_APPROVAL, 'Berkas diteruskan ke Kepala Bidang untuk paraf tahap 1.');
                    Notification::make()->title('Berkas diajukan untuk paraf Kepala Bidang')->success()->send();
                }),

            // 5. Beri Paraf (Kabid)
            Action::make('parafKabid')
                ->label('Beri Paraf (Kabid)')
                ->icon('heroicon-o-pencil-square')
                ->color('success')
                ->visible(fn (): bool => $this->record->status === ServiceRequestStatus::AWAITING_APPROVAL
                    && $this->record->approvals()->where('step', 1)->where('decision', ApprovalDecision::PENDING)->exists()
                )
                ->schema([
                    Textarea::make('notes')->label('Catatan Paraf (Opsional)'),
                ])
                ->action(function (array $data): void {
                    $approval = $this->record->approvals()->where('step', 1)->first();
                    $approval?->update([
                        'approver_id' => Auth::id(),
                        'decision' => ApprovalDecision::APPROVED,
                        'notes' => $data['notes'] ?? 'Telah diparaf dan disetujui Kabid.',
                        'decided_at' => now(),
                    ]);

                    // Buat approval tahap 2 (Kadis)
                    Approval::create([
                        'approvable_type' => ServiceRequest::class,
                        'approvable_id' => $this->record->id,
                        'step' => 2,
                        'decision' => ApprovalDecision::PENDING,
                    ]);

                    $this->logHistory('Paraf Tahap 1 (Kabid) diberikan. Menunggu tanda tangan Kepala Dinas.');
                    Notification::make()->title('Paraf diberikan. Berkas diteruskan ke Kepala Dinas.')->success()->send();
                }),

            // 6. Terbitkan Surat / Tanda Tangan Kadis
            Action::make('issueCertificate')
                ->label('Tanda Tangani & Terbitkan')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => in_array($this->record->status, [
                    ServiceRequestStatus::AWAITING_APPROVAL,
                    ServiceRequestStatus::DATA_VERIFICATION,
                ]))
                ->schema([
                    TextInput::make('certificate_number')
                        ->label('Nomor Surat Keterangan / Dokumen')
                        ->default(fn () => '460/'.rand(100, 999).'/409.105/'.date('Y'))
                        ->required(),
                    Textarea::make('notes')
                        ->label('Catatan Penerbitan'),
                ])
                ->action(function (array $data): void {
                    // Update Kadis approval jika ada
                    $approval = $this->record->approvals()->where('step', 2)->first();
                    $approval?->update([
                        'approver_id' => Auth::id(),
                        'decision' => ApprovalDecision::APPROVED,
                        'notes' => $data['notes'] ?? 'Disetujui dan ditandatangani Kepala Dinas.',
                        'decided_at' => now(),
                    ]);

                    if ($this->record->dtsenCertificate) {
                        $this->record->dtsenCertificate->update([
                            'certificate_number' => $data['certificate_number'],
                            'issued_at' => now(),
                            'valid_until' => now()->addDays(30),
                            'signer_id' => Auth::id(),
                            'verification_code' => strtoupper(bin2hex(random_bytes(6))),
                        ]);
                    }

                    $this->transitionStatus(ServiceRequestStatus::ISSUED, 'Surat Keterangan resmi diterbitkan dengan nomor: '.$data['certificate_number']);
                    Notification::make()->title('Surat Keterangan Berhasil Diterbitkan!')->success()->send();
                }),

            // 7. Tolak Permohonan
            Action::make('reject')
                ->label('Tolak Permohonan')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (): bool => ! in_array($this->record->status, [
                    ServiceRequestStatus::ISSUED,
                    ServiceRequestStatus::COMPLETED,
                    ServiceRequestStatus::REJECTED,
                ]))
                ->schema([
                    Textarea::make('rejection_reason')
                        ->label('Alasan Penolakan')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'rejection_reason' => $data['rejection_reason'],
                        'rejected_at' => now(),
                    ]);

                    $this->transitionStatus(ServiceRequestStatus::REJECTED, 'Ditolak: '.$data['rejection_reason']);
                    Notification::make()->title('Permohonan telah ditolak')->danger()->send();
                }),
        ];
    }

    protected function transitionStatus(ServiceRequestStatus $toStatus, ?string $notes = null): void
    {
        $fromStatus = $this->record->status;
        $this->record->update(['status' => $toStatus]);

        StatusHistory::create([
            'statusable_type' => ServiceRequest::class,
            'statusable_id' => $this->record->id,
            'from_status' => $fromStatus instanceof ServiceRequestStatus ? $fromStatus->value : $fromStatus,
            'to_status' => $toStatus->value,
            'notes' => $notes,
            'user_id' => Auth::id(),
            'created_at' => now(),
        ]);
    }

    protected function logHistory(string $notes): void
    {
        StatusHistory::create([
            'statusable_type' => ServiceRequest::class,
            'statusable_id' => $this->record->id,
            'from_status' => $this->record->status instanceof ServiceRequestStatus ? $this->record->status->value : $this->record->status,
            'to_status' => $this->record->status instanceof ServiceRequestStatus ? $this->record->status->value : $this->record->status,
            'notes' => $notes,
            'user_id' => Auth::id(),
            'created_at' => now(),
        ]);
    }
}
