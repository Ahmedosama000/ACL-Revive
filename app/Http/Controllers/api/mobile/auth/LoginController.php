<?php

namespace App\Http\Controllers\api\mobile\auth;

use App\Models\Type;
use App\Models\User;
use App\Models\Protocol;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Models\Identification;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Models\UserProtocol;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use ApiTrait;

    public function login(LoginRequest $request){

        if ($request->email){

            $user = User::with('type:types.id,name')->where('email',$request->email)->get();
            $type_id = User::where('email',$request->email)->first()->type_id;
        }

        else if ($request->username){
            $user = User::with('type:types.id,name')->where('username',$request->username)->get();
            $type_id = User::where('username',$request->username)->first()->type_id;
        }

        
        if (! Hash::check($request->password,$user[0]->password)){
            return $this->ErrorMessage(["email"=>"The email or password not correct"],"",401);
        }

        else {

            $user['token'] = "Bearer ".$user[0]->createToken($request->password)->plainTextToken;

            try {

                $protocol = UserProtocol::where('user_id',$user[0]->id)->get()[0];
                return $this->Data(compact('user'),"",200);
            }
            
            catch (\Throwable $th) {
                
                if ($type_id == 1){
                    
                    return $this->Data(compact('user'),"You need to select your protocol",401);
                }
                else if ($type_id == 2) {
                    
                    $check = Identification::where('user_id',$user[0]->id)->first();
                    if (!$check){
                        
                        return $this->Data(compact('user'),"You need to complete your data",401);
                    }
                    return $this->Data(compact('user'),"",200);
                }
                
                return $this->Data(compact('user'),"",200);
            }
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
