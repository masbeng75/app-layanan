<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\AppliesDashboardFilters;
use App\Models\DtsenCertificate;
use App\Models\DtsenPurpose;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class DtsenIssuedOverview extends BaseWidget
{
    use AppliesDashboardFilters;
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $baseQuery = DtsenCertificate::query()
            ->whereNotNull('issued_at')
            ->whereHas('serviceRequest', function (Builder $query): void {
                $villageId = $this->getEffectiveVillageId();
                $districtId = $this->getEffectiveDistrictId();

                if ($villageId) {
                    $query->where('village_id', $villageId);
                } elseif ($districtId) {
                    $query->whereHas('village', fn (Builder $q) => $q->where('district_id', $districtId));
                }

                $status = $this->getEffectiveStatus();
                $query->when($status, fn (Builder $q) => $q->where('status', $status));
            });

        $dates = $this->getDateRange();
        $baseQuery->when($dates['startDate'], fn (Builder $q, $d) => $q->where('issued_at', '>=', $d))
            ->when($dates['endDate'], fn (Builder $q, $d) => $q->where('issued_at', '<=', $d));

        $totalIssued = (clone $baseQuery)->count();

        $activeValid = (clone $baseQuery)
            ->where('valid_until', '>=', now()->toDateString())
            ->count();

        // 7-day sparkline chart
        $sparklineData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $count = (clone $baseQuery)
                ->whereDate('issued_at', $day)
                ->count();
            $sparklineData[] = $count;
        }

        // Breakdown per tujuan
        $topPurposeGroup = (clone $baseQuery)
            ->whereNotNull('dtsen_purpose_id')
            ->selectRaw('dtsen_purpose_id, count(*) as count')
            ->groupBy('dtsen_purpose_id')
            ->orderByDesc('count')
            ->first();

        $topPurposeName = 'Belum Ada Data';
        $topPurposeCount = 0;
        if ($topPurposeGroup) {
            $purpose = DtsenPurpose::find($topPurposeGroup->dtsen_purpose_id);
            $topPurposeName = $purpose ? $purpose->name : 'Tujuan Lain';
            $topPurposeCount = (int) $topPurposeGroup->count;
        }

        $totalPurposes = DtsenPurpose::where('is_active', true)->count();

        // Breakdown per desil
        $desilRendah = (clone $baseQuery)
            ->whereBetween('decile', [1, 3])
            ->count();

        $desilMenengah = (clone $baseQuery)
            ->whereBetween('decile', [4, 7])
            ->count();

        $desilTinggi = (clone $baseQuery)
            ->where('decile', '>=', 8)
            ->count();

        return [
            Stat::make('Total SK DTSEN Terbit', number_format($totalIssued))
                ->description("{$activeValid} surat aktif berlaku")
                ->descriptionIcon('heroicon-m-document-check', IconPosition::Before)
                ->chart($sparklineData)
                ->color('success'),

            Stat::make('Tujuan Penggunaan Utama', $topPurposeCount > 0 ? "{$topPurposeName} ({$topPurposeCount})" : $topPurposeName)
                ->description("Dari {$totalPurposes} variasi tujuan layanan")
                ->descriptionIcon('heroicon-m-tag', IconPosition::Before)
                ->color('primary'),

            Stat::make('Sebaran Desil (Kesejahteraan)', "Desil 1–3: {$desilRendah}")
                ->description("Desil 4–7: {$desilMenengah} | Desil 8+: {$desilTinggi}")
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->color('info'),
        ];
    }
}
