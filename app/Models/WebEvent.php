<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebEvent extends Model
{
    public const TYPE_VISIT    = 'visit';
    public const TYPE_DOWNLOAD = 'download';

    public $timestamps = false;

    protected $fillable = [
        'type',
        'visitor_hash',
        'apk_release_id',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];
}
