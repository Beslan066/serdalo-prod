<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';
    
    protected $casts = [
        'signed_at' => 'date:Y-m-d',
        'published_at' => 'date:Y-m-d',
        'enter_into_force_at' => 'date:Y-m-d',
    ];

    public function getTitleAttribute($value)
    {
        return (App::getLocale() !== 'ru') ? $this->translation->title ?? $value : $value;
    }

    public function translation() 
    {
        return $this->hasOne(DocumentTranslation::class, 'document_id', 'id');
    }
    public function file() 
    {
        return $this->hasOne(MediaFile::class, 'id', 'file_id');
    }
}
