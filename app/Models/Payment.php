<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    
    protected $casts = [
        'subrscribe_start_at' => 'date:d.m.Y',
        'subrscribe_end_at' => 'date:d.m.Y',
    ];
}
