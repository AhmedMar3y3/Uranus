<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApkRelease extends Model
{
    protected $fillable = [
        'version',
        'release_notes',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'is_active',
        'downloads_count',
        'uploaded_by',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'file_size'       => 'integer',
        'downloads_count' => 'integer',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }
}
