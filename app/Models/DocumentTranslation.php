<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DocumentTranslation extends Model
{
    use HasFactory;

    protected $table = 'document_translations';
    
    public $timestamps = false;
}
