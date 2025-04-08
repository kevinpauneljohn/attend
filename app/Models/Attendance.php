<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['uid','id','timestamp','type','state','is_sent'];

    protected $attributes = [
        'is_sent' => false,
    ];
}
