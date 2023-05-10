<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeal extends Model
{
    use HasFactory;
    protected $table = 'user_meal';
    protected $guarded = ['id'];
    protected $hidden = [
        'id',
        'uuid',
    ];
}
