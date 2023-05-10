<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandMeal extends Model
{
    use HasFactory;
    protected $table = 'brand_meal';
    protected $guarded = ['id'];
    protected $hidden = [
        'id',
        'uuid',
        'created_by'
    ];
}
