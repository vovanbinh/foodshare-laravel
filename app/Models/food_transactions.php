<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class food_transactions extends Model
{
    use HasFactory;
    protected $table = 'food_transactions';
    protected $fillable = [
        'food_id',
        'receiver_id',
        'quantity_received',
        'pickup_time',
        'status',
        'receiver_status',
        'donor_status',
    ];
    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }

    // Định nghĩa mối quan hệ với bảng users cho người nhận
    public function receiver()
    {
        return $this->belongsTo(Users::class, 'receiver_id');
    }

    // Định nghĩa mối quan hệ với bảng users cho người tặng
}
