<?php

namespace App\Filament\Widgets;

use App\Enums\ReferralStatus;
use App\Enums\RehabilitationCaseStatus;
use App\Filament\Widgets\Concerns\AppliesDashboardFilters;
use App\Models\Referral;
use App\Models\ReferralInstitution;
use App\Models\RehabilitationCase;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RehabilitationActiveCases extends BaseWidget
{
    use AppliesDashboardFilters;
    use InteractsWithPageFilters;

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $baseQuery = RehabilitationCase::query();
        $this->applyRehabilitationCaseFilters($baseQuery);

        $activeStatuses = [
            RehabilitationCaseStatus::ASSESSMENT,
            RehabilitationCaseStatus::SERVICE_PLANNING,
            RehabilitationCaseStatus::IN_SERVICE,
            RehabilitationCaseStatus::MONITORING,
        ];

        $totalActive = (clone $baseQuery)
            ->whereIn('status', $activeStatuses)
            ->count();

        $assessmentCount = (clone $baseQuery)
            ->where('status', RehabilitationCaseStatus::ASSESSMENT)
            ->count();

        $planningCount = (clone $baseQuery)
            ->where('status', RehabilitationCaseStatus::SERVICE_PLANNING)
            ->count();

        $inServiceCount = (clone $baseQuery)
            ->where('status', RehabilitationCaseStatus::IN_SERVICE)
            ->count();

        $monitoringCount = (clone $baseQuery)
            ->where('status', RehabilitationCaseStatus::MONITORING)
            ->count();

        // Rujukan Aktif & Mitra Terbanyak
        $referralQuery = Referral::query()
            ->whereIn('status', [
                ReferralStatus::SENT,
                ReferralStatus::ACCEPTED,
                ReferralStatus::IN_SERVICE,
            ])
            ->whereHas('rehabilitationCase', function ($q): void {
                $this->applyRehabilitationCaseFilters($q);
            });

        $activeReferralsCount = (clone $referralQuery)->count();

        $topInstitutionGroup = (clone $referralQuery)
            ->whereNotNull('referral_institution_id')
            ->selectRaw('referral_institution_id, count(*) as count')
            ->groupBy('referral_institution_id')
            ->orderByDesc('count')
            ->first();

        $topInstitutionName = 'Belum Ada Rujukan';
        if ($topInstitutionGroup) {
            $inst = ReferralInstitution::find($topInstitutionGroup->referral_institution_id);
            $topInstitutionName = $inst ? "{$inst->name} ({$topInstitutionGroup->count})" : 'Lembaga Mitra';
        }

        return [
            Stat::make('Kasus Aktif Rehsos', number_format($totalActive))
                ->description('Penyandang disabilitas, lansia & PPKS')
                ->descriptionIcon('heroicon-m-heart', IconPosition::Before)
                ->color('primary'),

            Stat::make('Tahap Penanganan', "Layanan: {$inServiceCount} | Asesmen: {$assessmentCount}")
                ->description("Monitoring: {$monitoringCount} | Perencanaan: {$planningCount}")
                ->descriptionIcon('heroicon-m-arrow-path', IconPosition::Before)
                ->color('warning'),

            Stat::make('Rujukan Lembaga Mitra', "{$activeReferralsCount} Aktif")
                ->description("Mitra utama: {$topInstitutionName}")
                ->descriptionIcon('heroicon-m-building-office-2', IconPosition::Before)
                ->color('info'),
        ];
    }
}
