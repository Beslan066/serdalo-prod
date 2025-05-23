<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostTranslation extends Model
{
    use HasFactory;

    protected $table = 'post_translations';

    public $timestamps = false;

    public function file()
    {
        return $this->hasOne(MediaFile::class, 'id', 'file_id');
    }

    public function thumb()
    {
        return $this->hasOne(MediaFile::class, 'id', 'thumb_id');
    }
}
