<?php

namespace App\Filament\Widgets;

use App\Enums\ServiceRequestStatus;
use App\Filament\Widgets\Concerns\AppliesDashboardFilters;
use App\Models\ServiceRequest;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class PbiReactivationByStage extends BaseWidget
{
    use AppliesDashboardFilters;
    use InteractsWithPageFilters;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $baseQuery = ServiceRequest::query()
            ->where(function (Builder $query): void {
                $query->whereHas('serviceType', fn (Builder $q) => $q->where('code', 'PBI'))
                    ->orWhereHas('pbiReactivation');
            });

        $this->applyServiceRequestFilters($baseQuery);

        $verificationStatuses = [
            ServiceRequestStatus::SUBMITTED,
            ServiceRequestStatus::DOCUMENT_CHECK,
            ServiceRequestStatus::REVISION_REQUESTED,
            ServiceRequestStatus::DATA_VERIFICATION,
            ServiceRequestStatus::ELIGIBILITY_VERIFICATION,
            ServiceRequestStatus::VERIFICATION,
            ServiceRequestStatus::AWAITING_APPROVAL,
            ServiceRequestStatus::RECOMMENDATION_ISSUED,
            ServiceRequestStatus::IN_PROCESS,
        ];

        $verificationCount = (clone $baseQuery)
            ->whereIn('status', $verificationStatuses)
            ->count();

        $proposedQuery = (clone $baseQuery)
            ->where('status', ServiceRequestStatus::PROPOSED_TO_MINISTRY);

        $proposedCount = (clone $proposedQuery)->count();

        // Alert threshold: 14 days
        $overdueThreshold = now()->subDays(14);
        $overdueCount = (clone $proposedQuery)
            ->where(function (Builder $q) use ($overdueThreshold): void {
                $q->whereHas('pbiReactivation', fn (Builder $pq) => $pq->where('proposed_to_ministry_at', '<=', $overdueThreshold))
                    ->orWhere('submitted_at', '<=', $overdueThreshold);
            })
            ->count();

        $reactivatedCount = (clone $baseQuery)
            ->whereIn('status', [
                ServiceRequestStatus::REACTIVATED,
                ServiceRequestStatus::MINISTRY_APPROVED,
                ServiceRequestStatus::COMPLETED,
            ])
            ->count();

        $rejectedCount = (clone $baseQuery)
            ->whereIn('status', [
                ServiceRequestStatus::REJECTED,
                ServiceRequestStatus::MINISTRY_REJECTED,
            ])
            ->count();

        $proposedDescription = $overdueCount > 0
            ? "⚠️ {$overdueCount} berkas tertahan > 14 hari"
            : 'Menunggu keputusan Kemensos RI';

        return [
            Stat::make('Verifikasi Dinsos', number_format($verificationCount))
                ->description('Pemeriksaan berkas & rekomendasi')
                ->descriptionIcon('heroicon-m-clipboard-document-check', IconPosition::Before)
                ->color('info'),

            Stat::make('Diusulkan ke Kemensos', number_format($proposedCount))
                ->description($proposedDescription)
                ->descriptionIcon('heroicon-m-arrow-up-tray', IconPosition::Before)
                ->color($overdueCount > 0 ? 'danger' : 'warning'),

            Stat::make('Hasil Reaktivasi', "{$reactivatedCount} Aktif")
                ->description("{$rejectedCount} permohonan ditolak / gugur")
                ->descriptionIcon('heroicon-m-check-circle', IconPosition::Before)
                ->color('success'),
        ];
    }
}
