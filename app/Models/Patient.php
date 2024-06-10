<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dr_id',
        'result',
        'age',
        'phone',
        'email',
        'name',
        'dr_phone',
        'dr_gmail',
        'dr_name',
        'mri',
        'report'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s', 
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user(){

        return $this->belongsTo(User::class,'user_id');
    }
}
