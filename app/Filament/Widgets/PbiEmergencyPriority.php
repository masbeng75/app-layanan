<?php

namespace App\Filament\Widgets;

use App\Enums\PbiReason;
use App\Enums\ServiceRequestStatus;
use App\Filament\Resources\ServiceRequests\ServiceRequestResource;
use App\Filament\Widgets\Concerns\AppliesDashboardFilters;
use App\Models\ServiceRequest;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class PbiEmergencyPriority extends TableWidget
{
    use AppliesDashboardFilters;
    use InteractsWithPageFilters;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Prioritas Reaktivasi PBI-JK Darurat Medis')
            ->description('Daftar permohonan reaktivasi status darurat medis yang memerlukan percepatan penanganan.')
            ->query(
                fn (): Builder => $this->applyServiceRequestFilters(
                    ServiceRequest::query()
                        ->with(['pbiReactivation', 'village.district', 'serviceType'])
                        ->whereHas('pbiReactivation', fn (Builder $q) => $q->where('reason', PbiReason::EMERGENCY))
                        ->whereNotIn('status', [
                            ServiceRequestStatus::REACTIVATED,
                            ServiceRequestStatus::MINISTRY_APPROVED,
                            ServiceRequestStatus::COMPLETED,
                            ServiceRequestStatus::REJECTED,
                            ServiceRequestStatus::MINISTRY_REJECTED,
                        ])
                )
                    ->orderByDesc('is_priority')
                    ->orderBy('submitted_at')
            )
            ->columns([
                TextColumn::make('request_number')
                    ->label('No. Pengajuan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                IconColumn::make('is_priority')
                    ->label('Prioritas')
                    ->boolean()
                    ->trueIcon(Heroicon::Bolt)
                    ->trueColor('danger')
                    ->falseIcon(Heroicon::Minus)
                    ->falseColor('gray'),

                TextColumn::make('applicant_name')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pbiReactivation.participant_name')
                    ->label('Pasien / Peserta')
                    ->searchable()
                    ->description(fn (ServiceRequest $record): string => 'NIK: '.($record->pbiReactivation?->participant_nik ?? '-')),

                TextColumn::make('pbiReactivation.health_facility_name')
                    ->label('Faskes / RS')
                    ->placeholder('Tidak Disebutkan'),

                TextColumn::make('village.name')
                    ->label('Wilayah')
                    ->description(fn (ServiceRequest $record): string => $record->village?->district?->name ?? '-'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ServiceRequestStatus ? $state->label() : $state)
                    ->color(fn ($state): string => $state instanceof ServiceRequestStatus ? $state->color() : 'gray'),

                TextColumn::make('submitted_at')
                    ->label('Waktu Diajukan')
                    ->dateTime('d M Y H:i')
                    ->description(fn (ServiceRequest $record): ?string => $record->submitted_at?->diffForHumans())
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Buka Berkas')
                    ->icon(Heroicon::OutlinedEye)
                    ->url(fn (ServiceRequest $record): string => ServiceRequestResource::getUrl('view', ['record' => $record])),
            ])
            ->emptyStateHeading('Tidak Ada Pengajuan Darurat')
            ->emptyStateDescription('Saat ini tidak ada permohonan reaktivasi status darurat medis yang tertunda.');
    }
}
