<?php

namespace App\Filament\Resources\RehabilitationCases\Pages;

use App\Enums\RehabilitationCaseStatus;
use App\Filament\Resources\RehabilitationCases\RehabilitationCaseResource;
use App\Models\RehabilitationCase;
use App\Models\StatusHistory;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewRehabilitationCase extends ViewRecord
{
    protected static string $resource = RehabilitationCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),

            // 1. Mulai Asesmen
            Action::make('startAssessment')
                ->label('Mulai Asesmen Klien')
                ->icon('heroicon-o-document-magnifying-glass')
                ->color('info')
                ->visible(fn (): bool => $this->record->status === RehabilitationCaseStatus::RECEIVED)
                ->action(function (): void {
                    $this->transitionStatus(RehabilitationCaseStatus::ASSESSMENT, 'Memulai asesmen komprehensif terhadap kondisi klien.');
                    Notification::make()->title('Status: Asesmen Klien')->info()->send();
                }),

            // 2. Rencana Pelayanan
            Action::make('planService')
                ->label('Susun Rencana Pelayanan')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('info')
                ->visible(fn (): bool => $this->record->status === RehabilitationCaseStatus::ASSESSMENT)
                ->action(function (): void {
                    $this->transitionStatus(RehabilitationCaseStatus::SERVICE_PLANNING, 'Asesmen selesai, menyusun rencana intervensi/pelayanan.');
                    Notification::make()->title('Status: Penyusunan Rencana Pelayanan')->info()->send();
                }),

            // 3. Mulai Intervensi / Pelayanan
            Action::make('startService')
                ->label('Mulai Pelayanan / Rujukan')
                ->icon('heroicon-o-play')
                ->color('warning')
                ->visible(fn (): bool => in_array($this->record->status, [
                    RehabilitationCaseStatus::SERVICE_PLANNING,
                    RehabilitationCaseStatus::ASSESSMENT,
                ]))
                ->action(function (): void {
                    $this->transitionStatus(RehabilitationCaseStatus::IN_SERVICE, 'Klien resmi menerima intervensi sosial / pendampingan / rujukan.');
                    Notification::make()->title('Kasus sedang dalam penanganan aktif')->warning()->send();
                }),

            // 4. Monitoring
            Action::make('startMonitoring')
                ->label('Tahap Monitoring')
                ->icon('heroicon-o-eye')
                ->color('primary')
                ->visible(fn (): bool => $this->record->status === RehabilitationCaseStatus::IN_SERVICE)
                ->action(function (): void {
                    $this->transitionStatus(RehabilitationCaseStatus::MONITORING, 'Pelayanan utama selesai, memasuki tahap evaluasi dan pemantauan kondisi.');
                    Notification::make()->title('Memasuki tahap pemantauan/monitoring')->primary()->send();
                }),

            // 5. Tutup Kasus
            Action::make('closeCase')
                ->label('Tutup Kasus (Selesai)')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn (): bool => $this->record->status !== RehabilitationCaseStatus::CLOSED)
                ->schema([
                    Textarea::make('handling_result')
                        ->label('Hasil Akhir Penanganan & Rekomendasi Terminasi')
                        ->required()
                        ->rows(4),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'handling_result' => $data['handling_result'],
                        'closed_at' => now(),
                    ]);
                    $this->transitionStatus(RehabilitationCaseStatus::CLOSED, 'Kasus terminasi/selesai: '.$data['handling_result']);
                    Notification::make()->title('Kasus rehabilitasi sosial berhasil ditutup')->success()->send();
                }),
        ];
    }

    protected function transitionStatus(RehabilitationCaseStatus $toStatus, ?string $notes = null): void
    {
        $fromStatus = $this->record->status;
        $this->record->update(['status' => $toStatus]);

        StatusHistory::create([
            'statusable_type' => RehabilitationCase::class,
            'statusable_id' => $this->record->id,
            'from_status' => $fromStatus instanceof RehabilitationCaseStatus ? $fromStatus->value : $fromStatus,
            'to_status' => $toStatus->value,
            'notes' => $notes,
            'user_id' => Auth::id(),
            'created_at' => now(),
        ]);
    }
}
