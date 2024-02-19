<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $table = 'food'; // Tên bảng trong cơ sở dữ liệu
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'quantity',
        'expiry_date',
        'province_id',
        'district_id',
        'ward_id',
        'price',
        'status',
        'delivery_fee',
        'contact_information',
        'food_type',
        'operating_hours',
        'payment_methods',
        'remaining_time_to_accept'
    ];
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id'); 
    }
    public function province()
    {
        return $this->belongsTo(province::class, 'province_id'); 
    }
    
    public function district()
    {
        return $this->belongsTo(district::class, 'district_id'); 
    }
    
    public function ward()
    {
        return $this->belongsTo(ward::class, 'ward_id'); 
    }
    public function foodTransactions()
    {
        return $this->hasMany(food_transactions::class, 'food_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rate::class, 'food_transaction_id');
    }
}
