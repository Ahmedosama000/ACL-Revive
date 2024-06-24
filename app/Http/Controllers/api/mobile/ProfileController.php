<?php

namespace App\Http\Controllers\api\mobile;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\Faq;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    use ApiTrait;

    public function ChangeName(Request $request){

        $request->validate([
            'name' => ['required','string','max:32'],
        ]);

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $user = User::find($authenticated->id)->first();
            $user->name = $request->name;
            $user->save();

            return $this->SuccessMessage("Name Changed Successfully",200);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function ChangeUserName(Request $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $request->validate([
                'username' => ['required','string','max:32','unique:users,username,'.$authenticated->id],
            ]);

            $user = User::find($authenticated->id)->first();
            $user->username = $request->username;
            $user->save();

            return $this->SuccessMessage("Username Changed Successfully",200);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function ChangePhone(Request $request){

        $request->validate([
            'phone' => ['required','numeric'],
        ]);

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $user = User::find($authenticated->id)->first();
            $user->phone = $request->phone;
            $user->save();

            return $this->SuccessMessage("Phone Changed Successfully",200);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function ChangePassword(Request $request){

        $request->validate([
            'old_password' => ['required','min:8'],
            'new_password' => ['required','min:8','confirmed'],
        ]);

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            if (! Hash::check($request->old_password,$authenticated->password)){
                return $this->ErrorMessage(["password"=>"Password not correct"],"",401);
            }

            $user = User::find($authenticated->id)->first();
            $user->password = Hash::make($request->new_password);
            $user->save();

            $authenticated->tokens()->delete();

            return $this->SuccessMessage("Password Changed Successfully",200);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function ChangeEmail(Request $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        try {
            $request->validate([
                'email' => ['required','unique:users,email,'.$authenticated->id],
            ]);
            
            $user = User::find($authenticated->id)->first();
            $user->email = $request->email;
            $user->save();
    
            return $this->SuccessMessage("Email Changed Successfully",200);
        }
        catch (\Throwable $th) {
            return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
        }
    }

    public function Myinfo(Request $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $data = User::with('type:types.id,name')->find($authenticated->id);
            return $this->Data(compact('data'),"",200);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function AddVisa(Request $request){

        $request->validate([
            'name' => ['required','string','min:1','max:32'],
            'credit' => ['required','integer','max:16'],
            'expired_at' => ['required','date_format:Y-m-d'],
            'OTP' => ['required','integer','max:6']
        ]);

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $credit = Credit::create($request->all());
            $credit_id = $credit->id;

            $user = User::find($authenticated->id)->first();
            $user->credit_id = $credit_id;
            $user->save();

            return $this->SuccessMessage("Visa Added Successfully",200);
        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function CreateFaq(Request $request){

        $request->validate([
            'question' => ['required','string'],
            'answer' => ['required','string']
        ]);

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $faq = Faq::create($request->all());
            return $this->Data(compact('faq'),"Questions added successfully",200);

        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function GetFaqs(Request $request){
        
        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $faqs = Faq::all();
            return $this->Data(compact('faqs'),"",200);
        }

        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }

    public function CreateFeedback(Request $request){

        $request->validate([
            'comment' => ['required','string','max:64'],
            'stars' => ['required','integer','between:1,5'],
        ]);

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $data = [
                'comment' => $request->comment,
                'stars' => $request->stars,
                'user_id' => $authenticated->id
            ];

            $feedback = Feedback::create($data);
            return $this->Data(compact('feedback'),"Feedback added successfully",200);

        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }


    public function GetFeedbacks(Request $request){
        
        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $feddback = Feedback::with('user:users.id,name')->get();
            return $this->Data(compact('feddback'),"",200);
        }

        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
    }
}