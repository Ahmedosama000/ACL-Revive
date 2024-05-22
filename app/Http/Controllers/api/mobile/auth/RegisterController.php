<?php

namespace App\Http\Controllers\api\mobile\auth;

use App\Models\Type;
use App\Models\User;
use App\Http\traits\media;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Models\Identification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserPhotoRequest;
use App\Http\Requests\OrthRegisterRequest;

class RegisterController extends Controller
{
    use ApiTrait;
    use media;

    public function ShowType(){

        $types = Type::all();

        $data = compact('types');
        return $this->Data($data,'',200);
    }

    public function Register(RegisterRequest $request){
        $type_id = $request->get('type');
        if (Type::find($type_id)){
            $data = $request->except('password','password_confirmation','type');
            $data['password'] = Hash::make($request->password);
            $data['type_id'] = $type_id;
            $user = User::create($data);
            $user->token = "Bearer ".$user->createToken($request->email)->plainTextToken;
            return $this->Data(compact('user'),"User Created Successfully",201);
        }

        else {
            return $this->ErrorMessage([],"This Type Not Found",404);
        }
    }

    public function SetUserPhoto(UserPhotoRequest $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated)
        {
            $user = User::find($authenticated->id);

            $photo = $request->file('photo');

            $photo_name = $this->uploadPhoto($photo,'profiles',$user->id);
                        
            $user->photo = asset("profiles/$photo_name");

            $user->save();
            
            return $this->SuccessMessage("User Photo Set Successfully",200);
        }

        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);

    }

    public function OrthRegister(OrthRegisterRequest $request){

        $data = [

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'type_id' => 4
        ];
        
        $user = User::create($data);
        $user->token = "Bearer ".$user->createToken($request->email)->plainTextToken;

        $nation = $request->file('nation');
        $nation_name = $this->uploadFile($nation,'nations',$user->id);
                
        $union = $request->file('union');
        $union_name = $this->uploadFile($union,'unions',$user->id);

        $data = [

            "user_id" => $user->id,
            "national" => asset("nations/$nation_name"),
            "union" => asset("unions/$union_name")
        ];

        Identification::create($data);

        return $this->Data(compact('user'),"User Created Successfully",201);

    }
}
