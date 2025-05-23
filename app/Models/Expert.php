<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{


    use HasFactory;

    protected  $fillable=[
        'user_id',
        'title',
        'file_id',
        'description',
    ];

    public function file()
    {
        return $this->hasOne(MediaFile::class, 'id', 'file_id');
    }
}
