<?php

namespace App\Http\Controllers\api\mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SubscriptionRequest;
use App\Http\traits\ApiTrait;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    use ApiTrait;

    public function CreateSubscription(SubscriptionRequest $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            try {
                $subscribe = Subscription::where('user_id',$authenticated->id)->get()[0];

                if ($subscribe->end_at > date('Y-m-d')){

                    return $this->ErrorMessage(['Error'=>'Error'],"You Already Subscribed",401);

                }

                else if ($subscribe->end_at < date('Y-m-d')){

                    $date= date_create(date("Y-m-d"));
                    date_add($date,date_interval_create_from_date_string("$request->duration weeks"));
                    $end_at = date_format($date,"Y-m-d");
                    
                    $data = [
                        
                        "user_id" => $authenticated->id,
                        "price" => $request->price,
                        "duration" => $request->duration,
                        "end_at" => $end_at,
                    ];
                    $subscription = Subscription::create($data);
                    return $this->Data(compact('subscription'),"User Subscribed Successfully ",200);
                }                    
            }

            catch (\Throwable $th) {

                $date= date_create(date("Y-m-d"));
                date_add($date,date_interval_create_from_date_string("$request->duration weeks"));
                $end_at = date_format($date,"Y-m-d");

                $data = [

                "user_id" => $authenticated->id,
                "price" => $request->price,
                "duration" => $request->duration,
                "end_at" => $end_at,
            ];
            
            $subscription = Subscription::create($data);
            return $this->Data(compact('subscription'),"User Subscribed Successfully ",200);
        }
    }    
    return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);
}

    public function GetSubscriptionDetails(Request $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated){

            $check = Subscription::find($authenticated->id);

            if ($check){

                $data = Subscription::with('user:users.id,name')->where('user_id',$authenticated->id)->get();

                if ($data[0]->end_at < date("Y-m-d")){

                    return $this->Data(compact('data'),"Subscription end ",401);
                }

                return $this->Data(compact('data'),"",200);
            }

            return $this->ErrorMessage(['Subscribe'=>'Something Error'],"User not subscribe before",404);

        }
        return $this->ErrorMessage(['token'=>'token invalid'],"Please check token",404);

    }
}
