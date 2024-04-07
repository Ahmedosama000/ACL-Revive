<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'duration',
        'notes',
        'price',
        'platform',
        'started_at',
    ];

    protected $hidden = [
        'user_id',
        'patient_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s', 
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user(){

        return $this->belongsTo(User::class,'user_id');
    }

    public function patient(){

        return $this->belongsTo(User::class,'patient_id');
    }

}
