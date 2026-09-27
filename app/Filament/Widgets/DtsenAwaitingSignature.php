<?php

namespace App\Filament\Widgets;

use App\Enums\ApprovalDecision;
use App\Enums\ServiceRequestStatus;
use App\Filament\Widgets\Concerns\AppliesDashboardFilters;
use App\Models\ServiceRequest;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class DtsenAwaitingSignature extends BaseWidget
{
    use AppliesDashboardFilters;
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $baseQuery = ServiceRequest::query()
            ->where(function (Builder $query): void {
                $query->whereHas('serviceType', fn (Builder $q) => $q->where('code', 'DTSEN'))
                    ->orWhereHas('dtsenCertificate');
            })
            ->where('status', ServiceRequestStatus::AWAITING_APPROVAL);

        $this->applyServiceRequestFilters($baseQuery);

        $awaitingKabid = (clone $baseQuery)
            ->whereHas('approvals', fn (Builder $q) => $q->where('step', 1)->where('decision', ApprovalDecision::PENDING))
            ->count();

        $awaitingKadis = (clone $baseQuery)
            ->whereHas('approvals', fn (Builder $q) => $q->where('step', 2)->where('decision', ApprovalDecision::PENDING))
            ->count();

        $totalAwaiting = (clone $baseQuery)->count();

        return [
            Stat::make('Menunggu Paraf Kabid', number_format($awaitingKabid))
                ->description('Tahap 1 persetujuan draft SK')
                ->descriptionIcon('heroicon-m-pencil-square', IconPosition::Before)
                ->color($awaitingKabid > 0 ? 'warning' : 'success'),

            Stat::make('Menunggu TTD Kadis', number_format($awaitingKadis))
                ->description('Tahap 2 pengesahan akhir & terbit')
                ->descriptionIcon('heroicon-m-check-badge', IconPosition::Before)
                ->color($awaitingKadis > 0 ? 'danger' : 'success'),

            Stat::make('Total Antrean Pengesahan', number_format($totalAwaiting))
                ->description('Draf berkas dalam antrean persetujuan')
                ->descriptionIcon('heroicon-m-clock', IconPosition::Before)
                ->color('info'),
        ];
    }
}
