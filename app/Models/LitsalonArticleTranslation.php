<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LitsalonArticleTranslation extends Model
{
    use HasFactory;

    protected $table = 'litsalon_article_translations';
    
    public $timestamps = false;
}
