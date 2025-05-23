<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App;

class Author extends Model
{
    use HasFactory;

    protected $table = 'authors';

    public function getTitleAttribute($value)
    {
        return (App::getLocale() !== 'ru') ? $this->translation->title ?? $value : $value;
    }
    public function getDescriptionAttribute($value)
    {
        return (App::getLocale() !== 'ru') ? $this->translation->description ?? $value : $value;
    }

    public function translation() 
    {
        return $this->hasOne(AuthorTranslation::class, 'author_id', 'id');
    }
    public function file() 
    {
        return $this->hasOne(MediaFile::class, 'id', 'file_id');
    }
    public function posts() 
    {
        return $this->hasMany(Post::class, 'author_id', 'id');
    }
    public function materials() 
    {
        return $this->hasMany(Material::class, 'author_id', 'id');
    }
}
