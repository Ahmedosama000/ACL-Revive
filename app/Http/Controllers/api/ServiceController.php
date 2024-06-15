<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Patient;
use App\Http\traits\media;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ServiceRequest;

class ServiceController extends Controller
{
    use ApiTrait;
    use media;

    public function SavePatient(ServiceRequest $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        $doctor_id = User::where('email',$request->doctor_mail)->get()[0]->id;
        
        try {
            
            $report = $request->file('report');
            $report_name = $this->uploadFile($report,'reports',$doctor_id);

            $service_data = [
                'user_id' => $authenticated->id,
                'dr_id' => $doctor_id,
                'dr_name' => $request->doctor_name,
                'dr_gmail' => $request->doctor_mail,
                'dr_phone' => $request->doctor_phone,
                'name' => $request->patient_name,
                'email' => $request->patient_mail,
                'phone' => $request->patient_phone,
                'age' => $request->patient_age,
                'result' => $request->result,
                'mri' => $request->mri,
                'report' =>asset("reports/$report_name")
            
            ];

            $data = Patient::create($service_data);
            return $this->Data(compact('data'),"Patient Send Successfully",200);
        
        } 
        catch (\Throwable $th) {
            //throw $th;

            $service_data = [
                'user_id' => $authenticated->id,
                'dr_id' => $doctor_id,
                'dr_name' => $request->doctor_name,
                'dr_gmail' => $request->doctor_mail,
                'dr_phone' => $request->doctor_phone,
                'name' => $request->patient_name,
                'email' => $request->patient_mail,
                'phone' => $request->patient_phone,
                'age' => $request->patient_age,
                'result' => $request->result,
                'mri' => $request->mri,
                'report' => $request->report
            ];

            $data = Patient::create($service_data);
            return $this->Data(compact('data'), "Patient Send Successfully", 200);
        }
    }
        
    public function ShowAllReports(){

        $authenticated = Auth::guard('sanctum')->user();

        $data = Patient::with('user:users.id,name,email,phone')->
        where('user_id',$authenticated->id)->whereNotNull('report')->select('id','user_id','name','email','report')->get();
        return $this->Data(compact('data'),"",200);
    }

    public function ShowAllPatients(){

        $authenticated = Auth::guard('sanctum')->user();

        $data = Patient::all()->select('id','user_id','name','email','phone','age','result','dr_name','dr_gmail','dr_phone')->
        whereNull('result','mri','report')->where('user_id',$authenticated->id);

        return $this->Data(compact('data'),"",200);

    }

    public function ShowPatient($id){

        $authenticated = Auth::guard('sanctum')->user();

        $data = Patient::find($id);
       
        if (!$data){
            return $this->ErrorMessage(["ID"=>"ID Not Fount"],"Something Error",404); 
        }
        
        try {
            $data = Patient::where('id',$id)->where('user_id',$authenticated->id)->get(['id','user_id','name','email','phone','age','result','dr_name','dr_gmail','dr_phone'])[0];
            return $this->Data(compact('data'),"",200);
        } 
        catch (\Throwable $th) {
            return $this->ErrorMessage(["ID"=>"ID Not Fount"],"Something Error",404); 
        }
    }

    public function EditPatient(ServiceRequest $request,$id){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();
        $patient = Patient::find($id);

        if (!$patient){
            return $this->ErrorMessage(["ID"=>"ID Not Fount"],"Something Error",404); 
        }

        $doctor_id = $patient->dr_id;

        try {
            
            $report = $request->file('report');
            $report_name = $this->uploadFile($report,'reports',$doctor_id);

            $service_data = [
                'user_id' => $authenticated->id,
                'dr_id' => $doctor_id,
                'dr_name' => $request->doctor_name,
                'dr_gmail' => $request->doctor_mail,
                'dr_phone' => $request->doctor_phone,
                'name' => $request->patient_name,
                'email' => $request->patient_mail,
                'phone' => $request->patient_phone,
                'age' => $request->patient_age,
                'result' => $request->result,
                'mri' => $request->mri,
                'report' =>asset("reports/$report_name")
            
            ];

            $data = Patient::find($id)->update($service_data);
            return $this->SuccessMessage("Patient Updated Successfully",200);
        
        } 
        catch (\Throwable $th) {
            //throw $th;

            $service_data = [
                'user_id' => $authenticated->id,
                'dr_id' => $doctor_id,
                'dr_name' => $request->doctor_name,
                'dr_gmail' => $request->doctor_mail,
                'dr_phone' => $request->doctor_phone,
                'name' => $request->patient_name,
                'email' => $request->patient_mail,
                'phone' => $request->patient_phone,
                'age' => $request->patient_age,
                'result' => $request->result,
                'mri' => $request->mri,
                'report' => $request->report
            ];

            $data = Patient::find($id)->update($service_data);
            return $this->SuccessMessage("Patient Updated Successfully",200);
        }

    }

    public function ShowMRI(){

        $authenticated = Auth::guard('sanctum')->user();


        $data = Patient::whereNotNull('mri')->where('user_id',$authenticated->id)->get(['name','email','phone','mri']);
        return $this->Data(compact('data'),"",200);
    }

    public function GetAllDoctors(Request $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        if ($authenticated->type_id == 3) {

            $doctors = User::query()->where('type_id', 4)
            ->get(['id','name','username','email','photo']);

            return $this->Data(compact('doctors'), "", 200);
        }

        return $this->ErrorMessage(["authorized"=>"not authorized"],"pls try again",401);
    }

    public function Send(SendRequest $request , $id){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        $check_id = Patient::find($id);

        if ($check_id) {

            if ($request->doctor_mail) {

                $doctor_id = User::query()->where('email', $request->doctor_mail)->get()[0]->id;

                $report = $request->file('report');
                $report_name = $this->uploadFile($report, 'reports', $doctor_id);

                $service_data = [
                    'user_id' => $authenticated->id,
                    'dr_id' => $doctor_id,
                    'dr_name' => $request->doctor_name,
                    'dr_gmail' => $request->doctor_mail,
                    'dr_phone' => $request->doctor_phone,
                    'name' => $request->patient_name,
                    'email' => $request->patient_mail,
                    'phone' => $request->patient_phone,
                    'age' => $request->patient_age,
                    'result' => $request->result,
                    'mri' => $request->mri ? $request : null,
                    'report' => asset("reports/$report_name")
                ];

                Patient::find($id)->update($service_data);
                return $this->SuccessMessage("Data Send Successfully", 200);
            } 

            elseif ($request->patient_mail){

                $report = $request->file('report');
                $report_name = $this->uploadFile($report, 'reports', uniqid());

                $service_data = [
                    'user_id' => $authenticated->id,
                    'dr_id' => null,
                    'dr_name' => $request->doctor_name,
                    'dr_gmail' => $request->doctor_mail,
                    'dr_phone' => $request->doctor_phone,
                    'name' => $request->patient_name,
                    'email' => $request->patient_mail,
                    'phone' => $request->patient_phone,
                    'age' => $request->patient_age,
                    'result' => $request->result,
                    'mri' => $request->mri ? $request : null,
                    'report' => asset("reports/$report_name")
                ];

                Patient::find($id)->update($service_data);

                $patient_data = Patient::query()->where('id',$id)->get(['id','name','email','phone','age','report','result','mri']);
                return $this->Data(compact('patient_data'),"Data Send Successfully", 200);
            }        
        }
        return $this->ErrorMessage(["ID"=>"ID not found"],"ID not found",401);
    }

}