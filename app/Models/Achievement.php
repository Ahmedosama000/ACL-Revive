<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'protocol_id'
    ];

    protected $table = 'achievements';

    public function protocol(){

        return $this->belongsTo(Protocol::class,'protocol_id');
    }
}
