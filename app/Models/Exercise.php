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

    protected $fillable = [
        'name',
        'link',
        'img',
        'instruction',
        'type',
        //'phase',
        'protocol_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s', 
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'instruction' => 'array',
    ];

    public $timestamps = false;

}
