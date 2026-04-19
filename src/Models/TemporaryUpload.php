<?php

namespace Alareqi\SmartUpload\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryUpload extends Model
{
    protected $table = 'temporary_uploads';

    protected $fillable = [
        'uuid',
        'original_name',
        'mime_type',
        'size',
        'path',
        'disk',
        'expires_at',
        'form_token',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'size' => 'integer',
    ];
}