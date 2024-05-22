<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\traits\ApiTrait;
use App\Http\traits\media;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use ApiTrait;
    use media;

    public function SendPatient(ServiceRequest $request){

        //

    }
}
