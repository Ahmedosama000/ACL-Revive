<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    public function protocol(){

        return $this->belongsTo(Protocol::class,'protocol_id');
    }
}
