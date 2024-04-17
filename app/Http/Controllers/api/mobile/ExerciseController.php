<?php

namespace App\Http\Controllers\api\mobile;

use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
    use ApiTrait;

    public function GetAllExercise(Request $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();
        $data = Exercise::with('protocol:protocols.id,name')->get();

        return $this->Data(compact('data'),"",200);

        if ($authenticated){
            
            $data = Exercise::with('protocol:protocols.id,name')->get();
            return $this->Data(compact('data'),"",200);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);

    }

    public function GetExercise(Request $request , $type){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){
            
            if ($type == 0){

                $data = Exercise::with('protocol:protocols.id,name')->where('protocol_id',1)->get();
                return $this->Data(compact('data'),"",200);
            }
            else if ($type == 1){

                $data = Exercise::with('protocol:protocols.id,name')->where('protocol_id',2)->get();
                return $this->Data(compact('data'),"",200);
            }
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);

    }
}
