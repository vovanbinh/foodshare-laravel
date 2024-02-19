<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'message',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
    public function transaction()
    {
        return $this->belongsTo(food_transactions::class, 'transaction_id');
    }
}
