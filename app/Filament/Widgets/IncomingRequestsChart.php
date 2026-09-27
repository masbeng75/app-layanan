<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\AppliesDashboardFilters;
use App\Models\Complaint;
use App\Models\ServiceRequest;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class IncomingRequestsChart extends ChartWidget
{
    use AppliesDashboardFilters;
    use InteractsWithPageFilters;

    protected ?string $heading = 'Tren Pengajuan Layanan & Pengaduan';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    protected function getData(): array
    {
        $dates = $this->getDateRange();
        $startDate = $dates['startDate'] ? $dates['startDate']->copy() : Carbon::today()->subDays(13);
        $endDate = $dates['endDate'] ? $dates['endDate']->copy() : Carbon::today();

        // Limit range if wider than 60 days to keep chart readable
        if ($startDate->diffInDays($endDate) > 60) {
            $startDate = $endDate->copy()->subDays(60);
        }

        // Base queries
        $requestQuery = ServiceRequest::query();
        $this->applyServiceRequestFilters($requestQuery, 'submitted_at');

        $complaintQuery = Complaint::query();
        $this->applyComplaintFilters($complaintQuery, 'reported_at');

        // Fetch counts grouped by date
        $requestCounts = (clone $requestQuery)
            ->whereDate('submitted_at', '>=', $startDate)
            ->whereDate('submitted_at', '<=', $endDate)
            ->selectRaw('DATE(submitted_at) as date, count(*) as count')
            ->groupByRaw('DATE(submitted_at)')
            ->pluck('count', 'date')
            ->toArray();

        $complaintCounts = (clone $complaintQuery)
            ->whereDate('reported_at', '>=', $startDate)
            ->whereDate('reported_at', '<=', $endDate)
            ->selectRaw('DATE(reported_at) as date, count(*) as count')
            ->groupByRaw('DATE(reported_at)')
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $requestData = [];
        $complaintData = [];

        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $dateKey = $current->format('Y-m-d');
            $labels[] = $current->translatedFormat('d M');
            $requestData[] = (int) ($requestCounts[$dateKey] ?? 0);
            $complaintData[] = (int) ($complaintCounts[$dateKey] ?? 0);
            $current->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pengajuan Layanan',
                    'data' => $requestData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Pengaduan Masyarakat',
                    'data' => $complaintData,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.15)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
