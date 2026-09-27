<?php

namespace App\Livewire\Portal;

use App\Enums\PublishStatus;
use App\Models\InformationPage;
use App\Models\PageVisit;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal')]
class ServiceDetail extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $this->slug = $slug;

        // Log page visit
        $page = InformationPage::where('slug', $slug)
            ->where('publish_status', PublishStatus::PUBLISHED)
            ->first();

        if ($page) {
            PageVisit::firstOrCreate([
                'information_page_id' => $page->id,
                'visit_date' => now()->toDateString(),
            ])->increment('visit_count');
        }
    }

    public function render()
    {
        $page = InformationPage::query()
            ->with(['serviceType.requirements', 'downloadableForms', 'faqs'])
            ->where('slug', $this->slug)
            ->where('publish_status', PublishStatus::PUBLISHED)
            ->firstOrFail();

        return view('livewire.portal.service-detail', [
            'page' => $page,
        ])->title($page->title.' — SAPA SOSIAL Kab. Blitar');
    }
}
