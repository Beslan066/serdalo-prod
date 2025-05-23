<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AuthorTranslation extends Model
{
    use HasFactory;

    protected $table = 'author_translations';
    
    public $timestamps = false;
}
