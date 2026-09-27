<?php

namespace App\Livewire\Portal;

use App\Enums\PublishStatus;
use App\Models\Complaint;
use App\Models\InformationPage;
use App\Models\ServiceRequest;
use App\Models\ServiceType;
use App\Models\Village;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('SAPA SOSIAL — Satu Pintu Layanan Sosial Kabupaten Blitar')]
class Home extends Component
{
    public function render()
    {
        $stats = [
            'service_requests' => ServiceRequest::count(),
            'complaints' => Complaint::count(),
            'active_services' => ServiceType::where('is_active', true)->count(),
            'villages' => Village::count(),
        ];

        $featuredPages = InformationPage::query()
            ->with(['serviceType'])
            ->where('publish_status', PublishStatus::PUBLISHED)
            ->latest('published_at')
            ->take(4)
            ->get();

        $serviceTypes = ServiceType::query()
            ->where('is_active', true)
            ->withCount('requirements')
            ->take(6)
            ->get();

        return view('livewire.portal.home', [
            'stats' => $stats,
            'featuredPages' => $featuredPages,
            'serviceTypes' => $serviceTypes,
        ]);
    }
}
