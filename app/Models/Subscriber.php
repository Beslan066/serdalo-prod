<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App;

class Subscriber extends Model
{
    use HasFactory;

    protected $table = 'subscribers';
}
