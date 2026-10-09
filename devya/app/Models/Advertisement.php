<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'media_path',
        'media_type',
        'is_active',
        'sort_order',
        'display_seconds',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'display_seconds' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (Advertisement $advertisement) {

            $extension = strtolower(
                pathinfo(
                    $advertisement->media_path ?? '',
                    PATHINFO_EXTENSION
                )
            );

            $videoExtensions = [
                'mp4',
                'webm',
                'ogg',
                'mov',
                'm4v',
            ];

            $advertisement->media_type =
                in_array($extension, $videoExtensions, true)
                    ? 'video'
                    : 'image';
        });
    }
}
