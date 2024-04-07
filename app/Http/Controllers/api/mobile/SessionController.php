<?php

namespace App\Http\Controllers\api\mobile;

use App\Models\Session;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SessionRequest;
use App\Http\Requests\SessionUpdateRequest;

class SessionController extends Controller
{
    use ApiTrait;

    public function Create(SessionRequest $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        $data = $request->all();

        if ($authenticated){
            
            $data['user_id'] = $authenticated->id;

            $session = Session::create($data);

            $session['Name'] = $authenticated->name;
            $session['token'] = $token;

            return $this->Data(compact('session'),"Session Created",201);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function ShowRelatedSessions(Request $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $data = Session::with('user:users.id,name,photo')->where('user_id',$authenticated->id)->whereNull('patient_id')->get();
            return $this->Data(compact('data'),"",200);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);

    }

    public function ShowRelatedReservedSessions(Request $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $data = Session::with('user:users.id,name,photo','patient:users.id,name')->where('user_id',$authenticated->id)->whereNotNull('patient_id')->get();
            return $this->Data(compact('data'),"",200);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);

    }

    public function UpdateSession(SessionRequest $request , $id){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        $data = $request->all();

        if ($authenticated){

            $session = Session::find($id);

            if ($session){

                Session::find($id)->update($data);
                return $this->SuccessMessage("Session Updated",200);
            }

            else {
                return $this->ErrorMessage(['Session'=> 'Session ID Not Found'],"Session ID Not Found ",404);
            }
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function RemoveSession(Request $request , $id){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $session = Session::find($id);

            if ($session){

                Session::find($id)->delete();
                return $this->SuccessMessage("Session Deleted",200);
            }
            else {
                return $this->ErrorMessage(['Session'=> 'Session ID Not Found'],"Session ID Not Found ",404);
            }

        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function Reserve(Request $request , $id)
    {
        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){
            $session = Session::find($id);
            if ($session){

                $status = Session::find($id)->patient_id;

                if (is_null($status)){
                    
                    $reserve = Session::where('id',$id)->first();
                    $reserve->patient_id = $authenticated->id;

                    $reserve->save();

                    return $this->SuccessMessage("Session Reserved",200);
                }

                return $this->ErrorMessage(['Session'=> 'Session Reserved Before'],"Session Not available ",404);
            }

            return $this->ErrorMessage(['Session'=> 'Session ID Not Found'],"Session ID Not Found ",404);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);

    }

    public function GetSessionInfo(Request $request , $id){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){
            
            $session = Session::find($id);

            if ($session){

                $data = Session::with('user:users.id,name,photo')->where('id',$id)->get();
                return $this->Data(compact('data'),"",200);
            }
            return $this->ErrorMessage(['Session'=> 'Session ID Not Found'],"Session ID Not Found ",404);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);

    }

    public function ShowMyReservedSessions(Request $request)
    {

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $data = Session::with('user:users.id,name,photo','patient:users.id,name')->where('patient_id',$authenticated->id)->get();
            return $this->Data(compact('data'),"",200);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);


    }
}
