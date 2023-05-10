<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserAccount extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'user_account';
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'id',
        'uuid',
        'created_at',
        'updated_at',
        'user_account_uuid'
    ];
}
