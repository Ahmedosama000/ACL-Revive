<?php

namespace App\Http\Controllers\api\mobile\auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Requests\CodeRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetRequest;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetController extends Controller
{
    use ApiTrait;

    public function CheckMail($email){
        $email_list = [];
        $user = User::where('email',$email)->first();
        if ($user){
            $email_list['email'] = $email;
            return $this->Data(compact('email'),"Email is exists",200);
        }
        else {
            return $this->ErrorMessage(['email'=>'email not found'],"Email not found",404);
        }
    }

    public function SendCode(EmailRequest $request){
        // 1- get email and id
        $email = $request->email;
        $data = [];
        $data['email'] = $email;

        $id = User::where('email',$email)->first()->id;

        // 2- gen code 
        $code = rand(1000,9999);
        $data['code'] = $code;
        // 3- Gen expiration date 
        $expiration = date('Y-m-d H:i:s',strtotime('+10 minutes'));
        $data['expiration'] = $expiration;
        // 4- Save code and date in db
        $user = User::find($id);
        $user->code = $code ;
        $user->code_expired_at = $expiration;
        $user->save();

        return $this->Data($data,"Code send to email",200);
    }

    public function CheckCode(CodeRequest $request){

        // 1- get data
        $email = $request->email;
        $data = [];
        $data['email'] = $email;

        $id = User::where('email',$email)->first()->id;
        $code = User::where('email',$email)->first()->code;
        $code_expired_at = User::where('email',$email)->first()->code_expired_at;

        $now =  date('Y-m-d H:i:s');

        if ($code == $request->code && $code_expired_at < $now){
            $num = rand(1000,9999);
            $token = Hash::make("$code.$email.$num");
            $data['token'] = $token;

            $user_token = PasswordReset::where('email',$email)->first();
            if ($user_token){
                $data['token'] = $user_token->token;
                return $this->Data(compact('data'),"Code is Successfully",200);
            }
            else {
                $input = PasswordReset::create($data);
                return $this->Data(compact('data'),"Code is Successfully",200);
            }
        }

    }

    public function ResetPassword(ResetRequest $request){
        $token = $request->token;
        $email = PasswordReset::where('token',$token)->first()->email;
        $new_password = Hash::make($request->password);

        $user = User::where('email',$email)->first();
        $user->password = $new_password;
        $user->save();

        PasswordReset::where('email',$email)->delete();

        return $this->SuccessMessage("Password has been changed successfully",200);
    }

}
