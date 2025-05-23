<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App;

class LitsalonArticle extends Model
{
    use HasFactory;
    
    public static $GENERATIONS = [
        'the_older_generation' => 'The older generation',
        'middle_generation' => 'Middle generation',
        'younger_generation' => 'Younger generation',
    ];

    protected $table = 'litsalon_articles';
    
    protected $appends = ['generation_display', 'generations_translated'];

    public function getTitleAttribute($value)
    {
        return (App::getLocale() !== 'ru') ? $this->translation->title ?? $value : $value;
    }
    public function getGenerationDisplayAttribute($value)
    {
        $generation_value = self::$GENERATIONS[$this->generations] ?? null;
        return ($generation_value) ? __($generation_value) : $value;
    }
    public function getGenerationsTranslatedAttribute($value)
    {
        $translated = [];
        foreach (self::$GENERATIONS ?? [] as $generation_key => $generation_value) {
            $translated[$generation_key] = __($generation_value);
        }
        return $translated;
    }

    public function scopeMinimalSelect($query)
    {
        $query->select(['id', 'title', 'generation', 'file_id', 'subtitle', 'dates']);
    }
    
    public function translation() 
    {
        return $this->hasOne(LitsalonArticleTranslation::class, 'litsalon_article_id', 'id');
    }
    public function file() 
    {
        return $this->hasOne(MediaFile::class, 'id', 'file_id');
    }
    public function repeaters() 
    {
        return $this->hasMany(LitsalonArticleRepeater::class, 'litsalon_article_id', 'id');
    }
    public function publications() 
    {
        return $this->hasMany(LitsalonArticleRepeater::class, 'litsalon_article_id', 'id')->where('type', 'publications');
    }
    public function awards() 
    {
        return $this->hasMany(LitsalonArticleRepeater::class, 'litsalon_article_id', 'id')->where('type', 'awards');
    }
}
