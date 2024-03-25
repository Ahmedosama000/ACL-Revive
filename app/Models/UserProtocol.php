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

    protected $hidden = [
        'user_id',
        'protocol_id',
    ];

    protected $table = 'protocol_users';

    public function user(){

        return $this->belongsTo(User::class,'user_id');
    }

    public function protocol(){

        return $this->belongsTo(Protocol::class,'protocol_id');
    }
}
