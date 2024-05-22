<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'file',
        'patient_id'
    ];
    
    public $timestamps = false;

    public function patient(){

        return $this->belongsTo(Patient::class,'patient_id');
    }

}
