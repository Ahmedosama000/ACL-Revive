<?php

namespace App\Http\Controllers\api\mobile\auth;

use App\Models\Type;
use App\Models\User;
use App\Models\Protocol;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProtocolRequest;
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

        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $user = User::find($authenticated->id);
            
            $data = [
                'user_id'=> $authenticated->id,
                'protocol_id' => $protocol_id,
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
}
