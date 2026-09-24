<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class NumberSequence extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefix',
        'period',
        'last_number',
    ];

    protected function casts(): array
    {
        return [
            'last_number' => 'integer',
        ];
    }

    /**
     * Atomically generate the next sequential number with locking.
     */
    public static function nextFormattedNumber(string $prefix, ?string $period = null, int $digits = 5): string
    {
        $period = $period ?? Carbon::now()->format('Ym');

        return DB::transaction(function () use ($prefix, $period, $digits) {
            $sequence = self::where('prefix', $prefix)
                ->where('period', $period)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                $sequence = self::create([
                    'prefix' => $prefix,
                    'period' => $period,
                    'last_number' => 1,
                ]);
                $next = 1;
            } else {
                $next = $sequence->last_number + 1;
                $sequence->update(['last_number' => $next]);
            }

            return sprintf('%s-%s-%0'.$digits.'d', $prefix, $period, $next);
        });
    }
}
