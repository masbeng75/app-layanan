<?php

namespace App\Filament\Widgets;

use App\Enums\PublishStatus;
use App\Filament\Resources\InformationPages\InformationPageResource;
use App\Models\InformationPage;
use App\Models\SearchLog;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopInformationPages extends TableWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 9;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $topKeywords = SearchLog::query()
            ->selectRaw('keyword, count(*) as count')
            ->groupBy('keyword')
            ->orderByDesc('count')
            ->limit(5)
            ->pluck('keyword')
            ->toArray();

        $description = ! empty($topKeywords)
            ? '🔍 Kata kunci pencarian warga terpopuler: '.implode(' · ', array_map(fn ($k) => "\"{$k}\"", $topKeywords))
            : 'Menampilkan konten informasi layanan yang paling banyak diakses oleh publik.';

        return $table
            ->heading('Informasi Layanan Terpopuler')
            ->description($description)
            ->query(
                fn (): Builder => InformationPage::query()
                    ->withSum('pageVisits', 'visit_count')
                    ->orderByDesc('page_visits_sum_visit_count')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Panduan Layanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('publish_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof PublishStatus ? $state->label() : $state)
                    ->color(fn ($state): string => $state instanceof PublishStatus ? $state->color() : 'gray'),

                TextColumn::make('page_visits_sum_visit_count')
                    ->label('Total Akses (Kunjungan)')
                    ->formatStateUsing(fn ($state): string => number_format((int) $state).' kali dilihat')
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Tgl Publikasi')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Kelola Konten')
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->url(fn (InformationPage $record): string => InformationPageResource::getUrl('edit', ['record' => $record])),
            ])
            ->emptyStateHeading('Belum Ada Data Kunjungan')
            ->emptyStateDescription('Data statistik kunjungan panduan layanan akan muncul setelah publik mengakses portal.');
    }
}
