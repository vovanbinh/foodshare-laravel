<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class demojwt extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'quantity',
        'image_url',
    ];
}