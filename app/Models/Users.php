<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;
    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'image',
        'address',
        'phone_number',
        'birthdate',
        'gender',
        'bio',
        'role',
        'verification_code',
        'forgot_password_code',
        'is_verified',
    ];
    public function foods()
    {
        return $this->hasMany(Food::class, 'user_id');
    }
}