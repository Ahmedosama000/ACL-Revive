<?php

namespace App\Http\Controllers\api\mobile\auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
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
        $user->token = "Bearer ".$user->createToken($request->email)->plainTextToken;
        return $this->Data(compact('user'),"",200);
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
