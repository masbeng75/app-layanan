<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\AppliesDashboardFilters;
use App\Models\Complaint;
use App\Models\District;
use App\Models\ServiceRequest;
use App\Models\Village;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class RegionalDistribution extends ChartWidget
{
    use AppliesDashboardFilters;
    use InteractsWithPageFilters;

    protected ?string $heading = 'Sebaran Layanan & Pengaduan per Wilayah';

    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $districtId = $this->getEffectiveDistrictId();

        $labels = [];
        $requestData = [];
        $complaintData = [];

        if ($districtId) {
            // View per Desa pada Kecamatan terpilih
            $district = District::find($districtId);
            $this->heading = 'Sebaran Layanan & Pengaduan — Kecamatan '.($district?->name ?? '');

            $villages = Village::query()
                ->where('district_id', $districtId)
                ->orderBy('name')
                ->get();

            $requestQuery = ServiceRequest::query();
            $this->applyServiceRequestFilters($requestQuery, 'submitted_at');
            $requestCounts = (clone $requestQuery)
                ->selectRaw('village_id, count(*) as count')
                ->groupBy('village_id')
                ->pluck('count', 'village_id')
                ->toArray();

            $complaintQuery = Complaint::query();
            $this->applyComplaintFilters($complaintQuery, 'reported_at');
            $complaintCounts = (clone $complaintQuery)
                ->selectRaw('village_id, count(*) as count')
                ->groupBy('village_id')
                ->pluck('count', 'village_id')
                ->toArray();

            foreach ($villages as $village) {
                $labels[] = $village->name;
                $requestData[] = (int) ($requestCounts[$village->id] ?? 0);
                $complaintData[] = (int) ($complaintCounts[$village->id] ?? 0);
            }
        } else {
            // View per Kecamatan (Seluruh Kabupaten Blitar)
            $this->heading = 'Sebaran Layanan & Pengaduan per Kecamatan';

            $districts = District::query()
                ->orderBy('name')
                ->get();

            $requestQuery = ServiceRequest::query()
                ->join('villages', 'service_requests.village_id', '=', 'villages.id');
            $this->applyServiceRequestFilters($requestQuery, 'service_requests.submitted_at');
            $requestCounts = (clone $requestQuery)
                ->selectRaw('villages.district_id, count(service_requests.id) as count')
                ->groupBy('villages.district_id')
                ->pluck('count', 'villages.district_id')
                ->toArray();

            $complaintQuery = Complaint::query()
                ->join('villages', 'complaints.village_id', '=', 'villages.id');
            $this->applyComplaintFilters($complaintQuery, 'complaints.reported_at');
            $complaintCounts = (clone $complaintQuery)
                ->selectRaw('villages.district_id, count(complaints.id) as count')
                ->groupBy('villages.district_id')
                ->pluck('count', 'villages.district_id')
                ->toArray();

            foreach ($districts as $district) {
                $labels[] = $district->name;
                $requestData[] = (int) ($requestCounts[$district->id] ?? 0);
                $complaintData[] = (int) ($complaintCounts[$district->id] ?? 0);
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pengajuan Layanan',
                    'data' => $requestData,
                    'backgroundColor' => '#10b981',
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Pengaduan Masyarakat',
                    'data' => $complaintData,
                    'backgroundColor' => '#f59e0b',
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
