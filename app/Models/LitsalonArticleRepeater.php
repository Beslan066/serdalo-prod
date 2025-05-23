<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App;

class LitsalonArticleRepeater extends Model
{
    use HasFactory;

    protected $table = 'litsalon_article_repeaters';

    public function getTitleAttribute($value)
    {
        return (App::getLocale() !== 'ru') ? $this->translation->title ?? $value : $value;
    }
    public function getSubtitleAttribute($value)
    {
        return (App::getLocale() !== 'ru') ? $this->translation->subtitle ?? $value : $value;
    }

    public function translation() 
    {
        return $this->hasOne(LitsalonArticleRepeaterTranslation::class, 'litsalon_article_repeater_id', 'id');
    }
    public function file() 
    {
        return $this->hasOne(MediaFile::class, 'id', 'file_id');
    }
}
