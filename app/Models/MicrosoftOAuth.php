<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MicrosoftOAuth extends Model
{
    protected $fillable = [
        'accessToken',
        'refreshToken',
        'tokenExpires',
        'userName',
        'userEmail',
        'userTimeZone',
        'userId',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determine if the token has expired, or about to expire.
     */
    public function expired(): bool
    {
        return $this->tokenExpires < (time() + 500);
    }
}
