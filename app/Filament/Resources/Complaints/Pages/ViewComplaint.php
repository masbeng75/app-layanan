<?php

namespace App\Filament\Resources\Complaints\Pages;

use App\Enums\ComplaintStatus;
use App\Filament\Resources\Complaints\ComplaintResource;
use App\Models\Complaint;
use App\Models\StatusHistory;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewComplaint extends ViewRecord
{
    protected static string $resource = ComplaintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),

            // 1. Verifikasi Awal
            Action::make('verify')
                ->label('Verifikasi Awal')
                ->icon('heroicon-o-check-circle')
                ->color('info')
                ->visible(fn (): bool => $this->record->status === ComplaintStatus::RECEIVED)
                ->action(function (): void {
                    $this->transitionStatus(ComplaintStatus::VERIFICATION, 'Laporan telah diverifikasi awal oleh petugas intake.');
                    Notification::make()->title('Status: Diverifikasi Awal')->success()->send();
                }),

            // 2. Minta Klarifikasi Pelapor
            Action::make('requestClarification')
                ->label('Minta Klarifikasi Pelapor')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->color('warning')
                ->visible(fn (): bool => in_array($this->record->status, [
                    ComplaintStatus::RECEIVED,
                    ComplaintStatus::VERIFICATION,
                ]))
                ->schema([
                    Textarea::make('notes')
                        ->label('Poin Pertanyaan / Hal yang Perlu Diklarifikasi')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->transitionStatus(ComplaintStatus::CLARIFICATION_REQUESTED, $data['notes']);
                    Notification::make()->title('Permintaan klarifikasi dikirim ke pelapor')->warning()->send();
                }),

            // 3. Disposisi ke Petugas Lapangan
            Action::make('dispatch')
                ->label('Disposisikan Laporan')
                ->icon('heroicon-o-user-plus')
                ->color('primary')
                ->visible(fn (): bool => in_array($this->record->status, [
                    ComplaintStatus::RECEIVED,
                    ComplaintStatus::VERIFICATION,
                ]))
                ->schema([
                    Select::make('officer_id')
                        ->label('Pilih Petugas Penangan Lapangan')
                        ->options(fn () => User::whereHas('roles', fn ($q) => $q->where('name', 'petugas_dinsos'))->pluck('name', 'id'))
                        ->required(),
                    Textarea::make('instructions')
                        ->label('Instruksi Penanganan Khusus'),
                ])
                ->action(function (array $data): void {
                    $this->record->update(['officer_id' => $data['officer_id']]);
                    $this->transitionStatus(ComplaintStatus::DISPATCHED, 'Didisposisikan: '.($data['instructions'] ?? 'Tolong ditindaklanjuti.'));
                    Notification::make()->title('Laporan berhasil didisposisikan')->success()->send();
                }),

            // 4. Mulai Tangani Kasus Lapangan
            Action::make('handle')
                ->label('Mulai Penanganan')
                ->icon('heroicon-o-play')
                ->color('warning')
                ->visible(fn (): bool => in_array($this->record->status, [
                    ComplaintStatus::DISPATCHED,
                    ComplaintStatus::VERIFICATION,
                ]))
                ->action(function (): void {
                    $this->transitionStatus(ComplaintStatus::IN_HANDLING, 'Petugas mulai melakukan penjangkauan/tindakan di lapangan.');
                    Notification::make()->title('Kasus kini dalam tahap penanganan')->warning()->send();
                }),

            // 5. Kasus Selesai Ditangani
            Action::make('resolve')
                ->label('Selesaikan Penanganan')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn (): bool => in_array($this->record->status, [
                    ComplaintStatus::IN_HANDLING,
                    ComplaintStatus::DISPATCHED,
                ]))
                ->schema([
                    Textarea::make('resolution_notes')
                        ->label('Uraian Solusi / Hasil Penanganan di Lapangan')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'resolution_notes' => $data['resolution_notes'],
                        'resolved_at' => now(),
                    ]);
                    $this->transitionStatus(ComplaintStatus::RESOLVED, 'Kasus selesai ditangani: '.$data['resolution_notes']);
                    Notification::make()->title('Pengaduan berhasil diselesaikan!')->success()->send();
                }),

            // 6. Tandai Laporan Duplikat
            Action::make('markDuplicate')
                ->label('Tandai Duplikat')
                ->icon('heroicon-o-document-duplicate')
                ->color('gray')
                ->visible(fn (): bool => ! in_array($this->record->status, [
                    ComplaintStatus::RESOLVED,
                    ComplaintStatus::DUPLICATE,
                ]))
                ->schema([
                    Select::make('duplicate_of_id')
                        ->label('Duplikat dari Pengaduan No.')
                        ->options(Complaint::where('id', '!=', $this->record->id)->pluck('complaint_number', 'id'))
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->update(['duplicate_of_id' => $data['duplicate_of_id']]);
                    $this->transitionStatus(ComplaintStatus::DUPLICATE, 'Ditandai sebagai duplikat dari tiket ID: '.$data['duplicate_of_id']);
                    Notification::make()->title('Laporan ditandai sebagai duplikat')->send();
                }),
        ];
    }

    protected function transitionStatus(ComplaintStatus $toStatus, ?string $notes = null): void
    {
        $fromStatus = $this->record->status;
        $this->record->update(['status' => $toStatus]);

        StatusHistory::create([
            'statusable_type' => Complaint::class,
            'statusable_id' => $this->record->id,
            'from_status' => $fromStatus instanceof ComplaintStatus ? $fromStatus->value : $fromStatus,
            'to_status' => $toStatus->value,
            'notes' => $notes,
            'user_id' => Auth::id(),
            'created_at' => now(),
        ]);
    }
}
