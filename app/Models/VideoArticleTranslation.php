<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VideoArticleTranslation extends Model
{
    use HasFactory;

    protected $table = 'video_article_translations';
    
    public $timestamps = false;
}
