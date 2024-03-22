<?php

namespace App\Http\Controllers\api\mobile\auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Models\Protocol;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use ApiTrait;

    public function login(LoginRequest $request){

        $user = User::where('email',$request->email)->first();
        if (! Hash::check($request->password,$user->password)){
            return $this->ErrorMessage(["email"=>"The email or password not correct"],"",401);
        }
        else {
            $type_id = User::where('email',$request->email)->first()->type_id;
            $type  = Type::where('id',$type_id)->first()->name;
            $protocol_id = User::where('email',$request->email)->first()->protocol_id;
            $user['protocol'] = $protocol_id;
            $user['type'] = $type;
            $user->token = "Bearer ".$user->createToken($request->email)->plainTextToken;

            if (!$protocol_id){
                
                if ($type_id == 1){
                    return $this->Data(compact('user'),"You need to select your protocol",401);
                }
                else {
                    $protocol = Protocol::where('id',$protocol_id)->first()->name;
                    $user['protocol'] = $protocol;
                    return $this->Data(compact('user'),"You need to complete your data",401);
                }
            }
            return $this->Data(compact('user'),"",200);
        }
        
    }

    public function logout(Request $request){
        
        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        $IdWithBearer = explode('|',$token)[0];
        $TokenId = explode(' ',$IdWithBearer)[1];

        $authenticated->tokens()->where('id',$TokenId)->delete();
        return $this->SuccessMessage("User logged out Successfully",200);

    }

    public function AllLogout(){

        $authenticated = Auth::guard('sanctum')->user();
        $authenticated->tokens()->delete();
        return $this->SuccessMessage("User logged out from all devices",200);
    }
}