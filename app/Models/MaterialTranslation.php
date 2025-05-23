<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MaterialTranslation extends Model
{
    use HasFactory;

    protected $table = 'material_translations';

    public $timestamps = false;

    public function file()
    {
        return $this->hasOne(MediaFile::class, 'id', 'file_id');
    }

    public function author()
    {
        return $this->hasOne(Author::class, 'id', 'author_id');
    }
}
