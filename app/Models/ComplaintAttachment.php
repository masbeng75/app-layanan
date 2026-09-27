<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;

class ComplaintAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'file_path',
        'type',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function getSignedUrl(int $expirationMinutes = 30): string
    {
        return URL::temporarySignedRoute(
            'documents.complaint-attachment.download',
            now()->addMinutes($expirationMinutes),
            ['attachment' => $this->id]
        );
    }
}
