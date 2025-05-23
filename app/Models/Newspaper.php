<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Helpers\TextHelper;
use App;

class Newspaper extends Model
{
    use HasFactory;

    protected $table = 'newspapers';

    protected $appends = ['display_release_at', 'display_created_at'];

    protected $casts = [
        'release_at' => 'date:d.m.Y',
    ];
    
    public function getDisplayReleaseAtAttribute()
    {
        if($this->release_at->isToday()) {
            $value = $this->release_at->format('H:i');
        } else {
            $value = $this->release_at->format('H:i, d.m.Y');
        }
        return $value;
    }
    public function getDisplayCreatedAtAttribute()
    {
        if($this->created_at->isToday()) {
            $value = $this->created_at->format('H:i');
        } else {
            $value = $this->created_at->format('H:i, d.m.Y');
        }
        return $value;
    }
    
    public function file() 
    {
        return $this->hasOne(MediaFile::class, 'id', 'file_id');
    }
}
