<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TagTranslation extends Model
{
    use HasFactory;

    protected $table = 'tag_translations';
    
    public $timestamps = false;
}
