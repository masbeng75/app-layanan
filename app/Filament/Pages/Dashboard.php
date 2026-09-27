<?php

namespace App\Filament\Pages;

use App\Enums\ServiceRequestStatus;
use App\Models\District;
use App\Models\ServiceType;
use App\Models\Village;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -2;

    public function getTitle(): string|Htmlable
    {
        return 'Dashboard SAPA SOSIAL';
    }

    public function getSubheading(): ?string
    {
        return 'Sistem Administrasi Pelayanan & Pengaduan Sosial Terpadu - Kabupaten Blitar';
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 2,
        ];
    }

    public function getFiltersFormContentComponent(): Component
    {
        return parent::getFiltersFormContentComponent()->columnSpanFull();
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'sm' => 1,
                'md' => 1,
                'lg' => 1,
                'xl' => 1,
                '2xl' => 1,
            ])
            ->components([
                Section::make('Filter Data Dashboard')
                    ->description('Sesuaikan periode tanggal, jenis layanan, dan cakupan wilayah kerja.')
                    ->icon(Heroicon::Funnel)
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 3,
                        'xl' => 3,
                        '2xl' => 6,
                    ])
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('Dari Tanggal')
                            ->placeholder('Pilih tanggal awal')
                            ->prefixIcon(Heroicon::CalendarDays)
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default(now()->subDays(30)->toDateString())
                            ->maxDate(fn (Get $get): mixed => $get('endDate') ?: now()->toDateString()),

                        DatePicker::make('endDate')
                            ->label('Sampai Tanggal')
                            ->placeholder('Pilih tanggal akhir')
                            ->prefixIcon(Heroicon::CalendarDays)
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default(now()->toDateString())
                            ->minDate(fn (Get $get): mixed => $get('startDate')),

                        Select::make('service_type_id')
                            ->label('Jenis Layanan')
                            ->placeholder('Semua Layanan')
                            ->prefixIcon(Heroicon::ClipboardDocumentList)
                            ->options(fn (): array => ServiceType::query()
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray()
                            )
                            ->searchable()
                            ->preload(),

                        Select::make('status')
                            ->label('Status Tiket')
                            ->placeholder('Semua Status')
                            ->prefixIcon(Heroicon::CheckCircle)
                            ->options(collect(ServiceRequestStatus::cases())
                                ->mapWithKeys(fn (ServiceRequestStatus $status): array => [$status->value => $status->getLabel()])
                                ->toArray()
                            )
                            ->searchable()
                            ->preload(),

                        Select::make('district_id')
                            ->label('Kecamatan')
                            ->placeholder('Semua Kecamatan')
                            ->prefixIcon(Heroicon::BuildingOffice)
                            ->options(fn (): array => District::query()
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->default(fn (): ?int => Auth::user()?->district_id)
                            ->disabled(fn (): bool => (bool) Auth::user()?->district_id)
                            ->afterStateUpdated(fn (Set $set) => $set('village_id', null)),

                        Select::make('village_id')
                            ->label('Desa / Kelurahan')
                            ->placeholder('Semua Desa')
                            ->prefixIcon(Heroicon::MapPin)
                            ->options(function (Get $get): array {
                                $districtId = $get('district_id') ?: Auth::user()?->district_id;

                                return Village::query()
                                    ->when($districtId, fn ($q) => $q->where('district_id', $districtId))
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->default(fn (): ?int => Auth::user()?->village_id)
                            ->disabled(fn (): bool => (bool) Auth::user()?->village_id),
                    ]),
            ]);
    }
}
