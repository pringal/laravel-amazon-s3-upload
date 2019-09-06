<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UploadImage extends Model
{
    /* Fillable */
    protected $fillable = [
        'image_name', 'image_path', 'image_size'
    ];
    /* @array $appends */

    public $appends = ['url', 'uploaded_time', 'size_in_kb'];

    public function getUrlAttribute()
    {
        return Storage::disk('s3')->url($this->image_path);
    }

    public function getUploadedTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSizeInKbAttribute()
    {
        return round($this->image_size / 1024, 2);
    }

    public static function boot()
    {
        parent::boot();
    }
}
