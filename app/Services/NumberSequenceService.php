<?php

namespace App\Services;

use App\Models\NumberSequence;
use Illuminate\Support\Carbon;

class NumberSequenceService
{
    /**
     * Atomically generate the next sequential number with lockForUpdate.
     * Format: {PREFIX}-YYYYMM-NNNNN
     */
    public static function generate(string $prefix, ?string $period = null, int $digits = 5): string
    {
        $period = $period ?? Carbon::now()->format('Ym');

        return NumberSequence::nextFormattedNumber($prefix, $period, $digits);
    }

    /**
     * Generate sequential service request number: REQ-YYYYMM-00001
     */
    public static function generateServiceRequestNumber(): string
    {
        return self::generate('REQ');
    }

    /**
     * Generate sequential complaint ticket number: ADU-YYYYMM-00001
     */
    public static function generateComplaintNumber(): string
    {
        return self::generate('ADU');
    }

    /**
     * Generate sequential rehabilitation case number: REH-YYYYMM-00001
     */
    public static function generateRehabilitationCaseNumber(): string
    {
        return self::generate('REH');
    }

    /**
     * Generate sequential SK DTSEN certificate number: SK-DTSEN-YYYYMM-00001
     */
    public static function generateDtsenCertificateNumber(): string
    {
        return self::generate('SK-DTSEN');
    }

    /**
     * Generate sequential PBI recommendation number: REC-PBI-YYYYMM-00001
     */
    public static function generatePbiRecommendationNumber(): string
    {
        return self::generate('REC-PBI');
    }

    /**
     * Generate sequential referral number: RUJ-YYYYMM-00001
     */
    public static function generateReferralNumber(): string
    {
        return self::generate('RUJ');
    }
}
