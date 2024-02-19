<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rate extends Model
{
    use HasFactory;

    protected $table = 'rates'; 
    protected $fillable = ['food_transaction_id', 'rating', 'review']; 

    public function foodTransaction()
    {
        return $this->belongsTo(FoodTransaction::class, 'food_transaction_id', 'id');
    }
}
