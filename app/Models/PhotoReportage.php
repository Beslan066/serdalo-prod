<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoReportage extends Model
{
    use HasFactory;

    protected $table = 'photo_reportages';

    public function minimalSelect()
    {
        return $this->select('id', 'published_at', 'title', 'file_id');
    }

    public function file()
    {
        return $this->hasOne(MediaFile::class, 'id', 'file_id');
    }

    public function translation()
    {
        return $this->hasOne(PhotoReportageTranslate::class, 'post_id', 'id');
    }
}
