<?php

namespace App\Services;

use App\Models\DtsenCertificate;
use Illuminate\Support\Carbon;

class DtsenDuplicateCheckService
{
    /**
     * Check if a citizen already has an active, valid DTSEN certificate for the given purpose.
     */
    public static function checkDuplicate(string $nik, ?int $purposeId = null): ?DtsenCertificate
    {
        if (empty($nik)) {
            return null;
        }

        $query = DtsenCertificate::query()
            ->with(['purpose', 'serviceRequest'])
            ->where(function ($q) use ($nik) {
                $q->where('subject_nik', $nik)
                    ->orWhereHas('serviceRequest', fn ($sq) => $sq->where('applicant_nik', $nik));
            })
            ->whereNotNull('issued_at')
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', Carbon::today());
            });

        if ($purposeId) {
            $query->where('dtsen_purpose_id', $purposeId);
        }

        return $query->latest('issued_at')->first();
    }
}
