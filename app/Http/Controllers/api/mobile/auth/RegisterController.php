<?php

namespace App\Http\Controllers\api\mobile\auth;

use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    use ApiTrait;

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
}
