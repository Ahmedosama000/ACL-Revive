<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'duration',
        'price',
        'end_at',
    ];

    protected $hidden = [
        'updated_at',
    ];

    protected $casts = [
        'end_at' => 'datetime:Y-m-d', 
        'created_at' => 'datetime:Y-m-d', 
        'updated_at' => 'datetime:Y-m-d',
    ];

    protected $table = 'subscription';


    public function user(){

        return $this->belongsTo(User::class,'user_id');
    }
}
