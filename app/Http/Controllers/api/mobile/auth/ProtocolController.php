<?php

namespace App\Http\Controllers\api\mobile\auth;

use App\Models\Type;
use App\Models\User;
use App\Models\Protocol;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\DelProtocolRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProtocolRequest;
use App\Http\Requests\UpdateProtocolRequest;
use App\Models\Achievement;
use App\Models\UserProtocol;

class ProtocolController extends Controller
{
    use ApiTrait;

    public function GetProtocols(){
        
        $protocols = Protocol::all();
        return $this->Data(compact('protocols'),"",200);
    }

    public function SetUserProtocol(ProtocolRequest $request){

        $token = $request->header('Authorization');
        $protocol_id = $request->protocol_id;
        $injury_date = $request->injury_date;
        $surgery_date = $request->surgery_date;

        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $user = User::find($authenticated->id);
            
            $data = [
                'user_id'=> $authenticated->id,
                'protocol_id' => $protocol_id,
                'injury_date' => $injury_date ,
                'surgery_date' => $surgery_date,
            ];


            $SetProtocol = UserProtocol::create($data);

            $type_id = $user->type_id;
            $user->protocol = Protocol::where('id',$protocol_id)->first()->name;
            $user->type = Type::where('id',$type_id)->first()->name;
            $user->token = $token;
    
            return $this->Data(compact('user'),"Protocol selected successfully",200);
    
        }
        else {
            return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
        }
        
    }

    public function GetUserProtocols(Request $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $name = User::find($authenticated->id)->name;
            // $data = UserProtocol::with(['protocol:protocols.id,name','user:users.id,name'])->where('user_id',$authenticated->id)->get();
            $data = UserProtocol::with('protocol:protocols.id,name')->where('user_id',$authenticated->id)->get();
            return $this->Data(compact('data'),"",200);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function ShowUserProtocolInfo(Request $request , $id){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $protocols = UserProtocol::find($id);

            if ($protocols){

                $data = UserProtocol::with('protocol:protocols.id,name')->find($id);
                return $this->Data(compact('data'),"",200);

            }

            return $this->ErrorMessage(['ID'=>'ID not found'],"Please check ID",404);

        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);

    }

    public function RemoveUserProtocol(DelProtocolRequest $request){

        $token = $request->header('Authorization');
        $id = $request->id;
        $authenticated = Auth::guard('sanctum')->user();
        if ($authenticated){

            $data = UserProtocol::where('id',$id)->first();
            if ($data){

                UserProtocol::where('id',$id)->delete();
                return $this->SuccessMessage("Protocol Deleted",200);
            }

            return $this->ErrorMessage(['id'=>'id invalid'],"Please check id",404);

        }
        else {
            return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
        }
    }

    public function UpdateUserProtocol(UpdateProtocolRequest $request){
        
        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();
        $id = $request->id;
        $new_protocol = $request->protocol_id;

        if ($authenticated){

            $user = UserProtocol::where('id',$id)->first();

            if ($user->protocol_id != $new_protocol){
                
                $user->protocol_id = $new_protocol;
                $user->save();
                return $this->SuccessMessage("Protocol Updated",200);
            }
            return $this->ErrorMessage($data=[],"Already Updated",400);
        }

        else {
            return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
        }
        
    }


    public function MoveToAchieve(DelProtocolRequest $request){

        $token = $request->header('Authorization');
        $id = $request->id;
        $authenticated = Auth::guard('sanctum')->user();
        if ($authenticated){

            $data = UserProtocol::where('id',$id)->first();
            if ($data){

                $achieves = [];

                $achieves['user_id'] = $data->user_id;
                $achieves['protocol_id'] = $data->protocol_id;
                $achieves['injury_date'] = $data->injury_date;
                $achieves['surgery_date'] = $data->surgery_date;
                $achieves['started_at'] = $data->created_at;


                $achieve = Achievement::create($achieves);
                UserProtocol::where('id',$id)->delete();
                $achieve->token = $token;

                return $this->Data(compact('achieve'),"Protocol Moved to Achievements.",200);
            }

            return $this->ErrorMessage(['id'=>'id invalid'],"Please check id",404);

        }
        else {
            return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
        }
    }

    public function RemoveAchievement(Request $request){

        $request->validate([
            'id' => ['required','exists:achievements,id'],
        ]);

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            Achievement::find($request->id)->delete();
            return $this->SuccessMessage("Achievment deleted",200);
        }

        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);

    }
}