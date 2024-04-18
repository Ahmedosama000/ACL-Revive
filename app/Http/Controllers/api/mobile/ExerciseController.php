<?php

namespace App\Http\Controllers\api\mobile;

use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExerciseRequest;
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

    public function GetExerciseByID(Request $request , $id){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();
        
        if ($authenticated){

            $exercise = Exercise::find($id);
            if ($exercise){

                $data = Exercise::with('protocol:protocols.id,name')->find($id);
                return $this->Data(compact('data'),"",200);
            }
            return $this->ErrorMessage(['ID'=>'ID not Found'],"Please check ID",404);
        }

        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function GetExerciseByPhase(Request $request , $protocol , $type , $phase){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();
        
        if ($authenticated){

            if ($phase == 0 || $phase == 1 || $phase == 2 || $phase == 3 ){

                if ($protocol == 'pre'){

                    $data = Exercise::with('protocol:protocols.id,name')->where('phase',$phase)->where('protocol_id',1)->where('type',$type)->get();
                    return $this->Data(compact('data'),"",200);
                }

                else if ($protocol == 'post'){

                    $data = Exercise::with('protocol:protocols.id,name')->where('phase',$phase)->where('protocol_id',2)->where('type',$type)->get();
                    return $this->Data(compact('data'),"",200);
                }
                else {

                    return $this->ErrorMessage(['protocol'=>'protocol not found'],"Please check protocol",404);
                }
            }

            return $this->ErrorMessage(['ID'=>'ID not found'],"Please check ID",404);
        }

        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function CreateExercise(ExerciseRequest $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        $data = $request->all();
        
        if ($authenticated) {

            if ($request->phase == 0 ){

                $data['protocol_id'] = 1 ;
                $exercise = Exercise::create($data);
                return $this->Data(compact('exercise'),"Exercise Created Successfully",201);

            }

            else if ($request->protocol_id == 1 ){

                $data['phase'] = 0 ;
                $exercise = Exercise::create($data);
                return $this->Data(compact('exercise'),"Exercise Created Successfully",201);
            }

            $exercise = Exercise::create($request->all());
            return $this->Data(compact('exercise'),"Exercise Created Successfully",201);

        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);

    }

}
