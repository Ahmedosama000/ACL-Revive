<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'protocol_id',
        'injury_date',
        'surgery_date',
        'started_at',
    ];

    protected $casts = [
        'started_at' => 'datetime:Y-m-d ', 
        'created_at' => 'datetime:Y-m-d ', 
        'updated_at' => 'datetime:Y-m-d ',
    ];


    protected $table = 'achievements';

    public function protocol(){

        return $this->belongsTo(Protocol::class,'protocol_id');
    }
}
