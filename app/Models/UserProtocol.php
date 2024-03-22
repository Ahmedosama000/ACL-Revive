<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProtocol extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'protocol_id',
    ];

    protected $table = 'protocol_users';
}
