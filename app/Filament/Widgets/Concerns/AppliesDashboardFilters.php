<?php

namespace App\Filament\Widgets\Concerns;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait AppliesDashboardFilters
{
    /**
     * @return array{startDate: ?Carbon, endDate: ?Carbon}
     */
    protected function getDateRange(): array
    {
        $startDate = ! empty($this->pageFilters['startDate'])
            ? Carbon::parse($this->pageFilters['startDate'])->startOfDay()
            : null;

        $endDate = ! empty($this->pageFilters['endDate'])
            ? Carbon::parse($this->pageFilters['endDate'])->endOfDay()
            : null;

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    protected function getEffectiveDistrictId(): ?int
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user?->hasRole('operator_kecamatan_desa') && $user->district_id) {
            return (int) $user->district_id;
        }

        return ! empty($this->pageFilters['district_id']) ? (int) $this->pageFilters['district_id'] : null;
    }

    protected function getEffectiveVillageId(): ?int
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user?->hasRole('operator_kecamatan_desa') && $user->village_id) {
            return (int) $user->village_id;
        }

        return ! empty($this->pageFilters['village_id']) ? (int) $this->pageFilters['village_id'] : null;
    }

    protected function getEffectiveServiceTypeId(): ?int
    {
        return ! empty($this->pageFilters['service_type_id']) ? (int) $this->pageFilters['service_type_id'] : null;
    }

    protected function getEffectiveStatus(): ?string
    {
        return ! empty($this->pageFilters['status']) ? (string) $this->pageFilters['status'] : null;
    }

    protected function applyServiceRequestFilters(Builder $query, string $dateColumn = 'submitted_at'): Builder
    {
        $dates = $this->getDateRange();

        $query->when($dates['startDate'], fn (Builder $q, $d) => $q->where($dateColumn, '>=', $d))
            ->when($dates['endDate'], fn (Builder $q, $d) => $q->where($dateColumn, '<=', $d));

        $serviceTypeId = $this->getEffectiveServiceTypeId();
        $query->when($serviceTypeId, fn (Builder $q) => $q->where('service_type_id', $serviceTypeId));

        $status = $this->getEffectiveStatus();
        $query->when($status, fn (Builder $q) => $q->where('status', $status));

        $villageId = $this->getEffectiveVillageId();
        $districtId = $this->getEffectiveDistrictId();

        if ($villageId) {
            $query->where('village_id', $villageId);
        } elseif ($districtId) {
            $query->whereHas('village', fn (Builder $q) => $q->where('district_id', $districtId));
        }

        return $query;
    }

    protected function applyComplaintFilters(Builder $query, string $dateColumn = 'reported_at'): Builder
    {
        $dates = $this->getDateRange();

        $query->when($dates['startDate'], fn (Builder $q, $d) => $q->where($dateColumn, '>=', $d))
            ->when($dates['endDate'], fn (Builder $q, $d) => $q->where($dateColumn, '<=', $d));

        $status = $this->getEffectiveStatus();
        $query->when($status, fn (Builder $q) => $q->where('status', $status));

        $villageId = $this->getEffectiveVillageId();
        $districtId = $this->getEffectiveDistrictId();

        if ($villageId) {
            $query->where('village_id', $villageId);
        } elseif ($districtId) {
            $query->whereHas('village', fn (Builder $q) => $q->where('district_id', $districtId));
        }

        return $query;
    }

    protected function applyRehabilitationCaseFilters(Builder $query, string $dateColumn = 'received_at'): Builder
    {
        $dates = $this->getDateRange();

        $query->when($dates['startDate'], fn (Builder $q, $d) => $q->where($dateColumn, '>=', $d))
            ->when($dates['endDate'], fn (Builder $q, $d) => $q->where($dateColumn, '<=', $d));

        $status = $this->getEffectiveStatus();
        $query->when($status, fn (Builder $q) => $q->where('status', $status));

        $villageId = $this->getEffectiveVillageId();
        $districtId = $this->getEffectiveDistrictId();

        if ($villageId) {
            $query->whereHas('client', fn (Builder $q) => $q->where('village_id', $villageId));
        } elseif ($districtId) {
            $query->whereHas('client.village', fn (Builder $q) => $q->where('district_id', $districtId));
        }

        return $query;
    }
}
