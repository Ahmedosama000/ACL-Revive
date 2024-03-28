<?php

namespace App\Http\Controllers\api\mobile\auth;

use App\Models\Achievement;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    use ApiTrait;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $data = Achievement::with('protocol:protocols.id,name')->where('user_id',$authenticated->id)->get();
            return $this->Data(compact('data'),"",200);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }
}

