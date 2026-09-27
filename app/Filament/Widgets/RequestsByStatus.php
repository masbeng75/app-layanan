<?php

namespace App\Filament\Widgets;

use App\Enums\ServiceRequestStatus;
use App\Filament\Widgets\Concerns\AppliesDashboardFilters;
use App\Models\ServiceRequest;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class RequestsByStatus extends ChartWidget
{
    use AppliesDashboardFilters;
    use InteractsWithPageFilters;

    protected ?string $heading = 'Distribusi Status Pengajuan Layanan';

    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    protected function getData(): array
    {
        $query = ServiceRequest::query();
        $this->applyServiceRequestFilters($query);

        $countsByStatus = (clone $query)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->orderByDesc('count')
            ->pluck('count', 'status')
            ->toArray();

        $labels = [];
        $data = [];
        $backgroundColors = [];

        $colorPalette = [
            ServiceRequestStatus::SUBMITTED->value => '#94a3b8',
            ServiceRequestStatus::DOCUMENT_CHECK->value => '#38bdf8',
            ServiceRequestStatus::REVISION_REQUESTED->value => '#fbbf24',
            ServiceRequestStatus::DATA_VERIFICATION->value => '#06b6d4',
            ServiceRequestStatus::ELIGIBILITY_VERIFICATION->value => '#0ea5e9',
            ServiceRequestStatus::VERIFICATION->value => '#3b82f6',
            ServiceRequestStatus::ASSESSMENT->value => '#6366f1',
            ServiceRequestStatus::AWAITING_APPROVAL->value => '#f59e0b',
            ServiceRequestStatus::RECOMMENDATION_ISSUED->value => '#8b5cf6',
            ServiceRequestStatus::PROPOSED_TO_MINISTRY->value => '#a855f7',
            ServiceRequestStatus::MINISTRY_APPROVED->value => '#10b981',
            ServiceRequestStatus::MINISTRY_REJECTED->value => '#ef4444',
            ServiceRequestStatus::REACTIVATED->value => '#059669',
            ServiceRequestStatus::IN_PROCESS->value => '#64748b',
            ServiceRequestStatus::ISSUED->value => '#10b981',
            ServiceRequestStatus::COMPLETED->value => '#16a34a',
            ServiceRequestStatus::REJECTED->value => '#f43f5e',
        ];

        foreach ($countsByStatus as $statusValue => $count) {
            $enum = ServiceRequestStatus::tryFrom($statusValue);
            $labels[] = $enum ? $enum->getLabel() : ucfirst($statusValue);
            $data[] = (int) $count;
            $backgroundColors[] = $colorPalette[$statusValue] ?? '#cbd5e1';
        }

        if (empty($data)) {
            $labels = ['Belum Ada Data'];
            $data = [0];
            $backgroundColors = ['#e2e8f0'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Berkas',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
