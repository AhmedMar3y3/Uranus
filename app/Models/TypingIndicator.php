<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypingIndicator extends Model
{
    public const CREATED_AT = null;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'is_typing',
        'updated_at',
    ];

    protected $casts = [
        'is_typing' => 'boolean',
        'updated_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
