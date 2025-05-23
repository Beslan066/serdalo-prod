<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App;

class PostTag extends Model
{
    use HasFactory;

    protected $table = 'post_tags';
    
    public $timestamps = false;
}
