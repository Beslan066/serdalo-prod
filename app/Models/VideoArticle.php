<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App;

class VideoArticle extends Model
{
    use HasFactory;

    protected $table = 'video_articles';

    public function getTitleAttribute($value)
    {
        return (App::getLocale() !== 'ru') ? $this->translation->title ?? $value : $value;
    }

    

    public function getDisplayCreatedAtAttribute()
    {
        //Y-m-d H:i:s
        if($this->created_at->isToday()) {
            $value = $this->created_at->format('H:i');
        } else {
            $value = $this->created_at->translatedFormat('j F Y, H:i');
            //$value = $this->created_at->format('H:i, d.m.Y');
        }
        return $value;
    }

    public function translation()
    {
        return $this->hasOne(VideoArticleTranslation::class, 'video_article_id', 'id');
    }
    public function file()
    {
        return $this->hasOne(MediaFile::class, 'id', 'file_id');
    }
    public function thumb()
    {
        return $this->hasOne(MediaFile::class, 'id', 'thumb_id');
    }
}
