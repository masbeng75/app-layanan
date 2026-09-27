<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'nik',
        'work_unit_id',
        'district_id',
        'village_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function workUnit(): BelongsTo
    {
        return $this->belongsTo(WorkUnit::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function serviceRequestsSubmitted(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'submitter_id');
    }

    public function serviceRequestsHandled(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'officer_id');
    }

    public function complaintsReported(): HasMany
    {
        return $this->hasMany(Complaint::class, 'reporter_id');
    }

    public function complaintsHandled(): HasMany
    {
        return $this->hasMany(Complaint::class, 'officer_id');
    }

    public function rehabilitationCasesHandled(): HasMany
    {
        return $this->hasMany(RehabilitationCase::class, 'officer_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'officer_id');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'officer_id');
    }

    public function monitoringRecords(): HasMany
    {
        return $this->hasMany(MonitoringRecord::class, 'officer_id');
    }

    public function managedInformationPages(): HasMany
    {
        return $this->hasMany(InformationPage::class, 'manager_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class, 'approver_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_active;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('administrator');
    }

    public function isPimpinan(): bool
    {
        return $this->hasRole('pimpinan');
    }

    public function isPurePimpinan(): bool
    {
        return $this->hasRole('pimpinan') && ! $this->hasAnyRole(['administrator', 'petugas_dinsos', 'pejabat_penandatangan']);
    }

    public function isPejabatPenandatangan(): bool
    {
        return $this->hasRole('pejabat_penandatangan');
    }

    public function isPetugasDinsos(): bool
    {
        return $this->hasRole('petugas_dinsos');
    }

    public function isOperator(): bool
    {
        return $this->hasRole('operator_kecamatan_desa');
    }

    public function canAccessWilayah(?int $districtId, ?int $villageId): bool
    {
        if (! $this->isOperator()) {
            return true;
        }

        if ($this->village_id) {
            return $villageId === $this->village_id;
        }

        if ($this->district_id) {
            if ($districtId && $districtId === $this->district_id) {
                return true;
            }
            if ($villageId) {
                return Village::where('id', $villageId)->where('district_id', $this->district_id)->exists();
            }
        }

        return false;
    }
}
