<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacteristicData extends Model
{
    use HasFactory;
    protected $table = 'characteristic_data';
    protected $guarded = ['id'];
    protected $hidden = [
        'id',
        'uuid',
        'user_profile_uuid'
    ];
}
