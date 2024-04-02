<?php

namespace App\Http\Controllers\api\mobile\auth;

use App\Models\User;
use App\Http\traits\media;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadIDSRequest;
use App\Models\Identification;

class IdentifiesController extends Controller
{
    use ApiTrait;
    use media;

    public function UploadIDS(UploadIDSRequest $request)
    {

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated)
        {
            $user = User::find($authenticated->id);

            $check = Identification::where('user_id',$user->id)->first();

            if (!$check){
                
                $nation = $request->file('nation');
                $nation_name = $this->uploadFile($nation,'nations',$user->id);
                
                $union = $request->file('union');
                $union_name = $this->uploadFile($union,'unions',$user->id);

                $data = [
                    "user_id" => $user->id,
                    "national" => $nation_name,
                    "union" => $union_name,
                ];

                Identification::create($data);
                return $this->Data(compact('user'),"Identifies Uploaded Succefully",200);
            }

            else {
                return $this->ErrorMessage(["Error"=>"Something Wrong"],"You have uploaded the data before",404);
            }
        }
        else {
            return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
        }
    }
}