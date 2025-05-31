<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'path',
        'thumbnail_path',
        'type',
        'size',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getImageUrlAttribute()
    {
        return asset($this->path);
    }

    public function getThumbnailUrlAttribute()
    {
        return asset($this->thumbnail_path);
    }
}