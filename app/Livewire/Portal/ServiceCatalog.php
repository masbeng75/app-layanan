<?php

namespace App\Livewire\Portal;

use App\Enums\PublishStatus;
use App\Models\InformationPage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal')]
#[Title('Daftar Informasi Layanan — SAPA SOSIAL Kab. Blitar')]
class ServiceCatalog extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'kategori')]
    public string $category = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->category = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = InformationPage::query()
            ->with(['serviceType.requirements'])
            ->where('publish_status', PublishStatus::PUBLISHED);

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'ilike', '%'.$this->search.'%')
                    ->orWhere('description', 'ilike', '%'.$this->search.'%')
                    ->orWhere('requirements', 'ilike', '%'.$this->search.'%');
            });
        }

        if (! empty($this->category)) {
            $query->where('category', $this->category);
        }

        $pages = $query->latest('published_at')->paginate(9);

        $categories = InformationPage::query()
            ->where('publish_status', PublishStatus::PUBLISHED)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('livewire.portal.service-catalog', [
            'pages' => $pages,
            'categories' => $categories,
        ]);
    }
}
