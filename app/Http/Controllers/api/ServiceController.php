<?php

namespace App\Http\Controllers\api;

use App\Http\traits\media;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ServiceRequest;
use App\Models\Patient;
use App\Models\Report;
use App\Models\Scan;

class ServiceController extends Controller
{
    use ApiTrait;
    use media;

    public function SendPatient(ServiceRequest $request){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();

        $service_data = [
            'user_id' => $authenticated->id,
            'dr_name' => $request->doctor_name,
            'dr_gmail' => $request->doctor_mail,
            'dr_phone' => $request->doctor_phone,
            'name' => $request->patient_name,
            'email' => $request->patient_mail,
            'phone' => $request->patient_phone,
            'age' => $request->patient_age,
            'result' => $request->result,
            'mri' => $request->mri
        ];

        $data = Patient::create($service_data);

        $patient_id = $data->id;

        if ($request->file('report')) {
            $report = $request->file('report');
            $report_name = $this->uploadFile($report,'reports',$patient_id);
            $reports = ['patient_id'=>$patient_id,'file'=>asset("reports/$report_name")];

            $report_data = Report::create($reports);
            return $this->Data(compact('data','report_data'),"Patient Send Successfully",200);
        }
        
        return $this->Data(compact('data'),"Patient Send Successfully",200);
    }

    public function ShowAllReports(){

        $data = Report::with('patient:patients.id,name,email,phone')->get();
        return $this->Data(compact('data'),"",200);
    }

    public function ShowAllPatients(){

        $data = Patient::all()->select('id','name','email','phone','age','result','dr_name','dr_gmail','dr_phone')->
        where('result',null);

        return $this->Data(compact('data'),"",200);

    }

    public function ShowPatient($id){

        $data = Patient::find($id);
       
        if (!$data){
            return $this->ErrorMessage(["ID"=>"ID Not Fount"],"Something Error",404); 
    }
        return $this->Data(compact('data'),"",200);
        
    }

    public function EditPatient(ServiceRequest $request,$id){

        $token = $request->header('Authorization');
        $authenticated = Auth::guard('sanctum')->user();
        $patient = Patient::find($id);

        if (!$patient){
            return $this->ErrorMessage(["ID"=>"ID Not Fount"],"Something Error",404); 
        }

        elseif ($patient->result != null ){
            return $this->ErrorMessage(["Patient"=>"Patient Data already completed"],"Something Error",404); 
        }
        elseif (!$request->hasFile('report')){
            return $this->ErrorMessage(["Report"=>"Upload Report"],"You Should Upload Report",404); 
        }
        elseif (!$request->result){
            return $this->ErrorMessage(["result"=>"result needed"],"You Should write result",404); 
        }
        elseif (!$request->mri){
            return $this->ErrorMessage(["mri"=>"mri needed"],"You Should input mri",404); 
        }
        
        $patient_data = [
            'user_id' => $authenticated->id,
            'dr_name' => $request->doctor_name,
            'dr_gmail' => $request->doctor_mail,
            'dr_phone' => $request->doctor_phone,
            'name' => $request->patient_name,
            'email' => $request->patient_mail,
            'phone' => $request->patient_phone,
            'age' => $request->patient_age,
            'result' => $request->result,
            'mri' => $request->mri,
        ];

        if ($request->hasFile('report')) {
            $report = $request->file('report');
            $report_name = $this->uploadFile($report,'reports',$id);
            $reports = ['patient_id'=>$id,'file'=>asset("reports/$report_name")];

            $report_data = Report::create($reports);
            Patient::find($id)->Update($patient_data);

            return $this->SuccessMessage("Patient Info Updated Successfully",200);
        }
        else {
            return $this->ErrorMessage(["Report"=>"Upload Report"],"You Should Upload Report",404); 
        }
    }

    public function ShowMRI(){

        $data = Patient::whereNotNull('mri')->get();
        return $this->Data(compact('data'),"",200);
    }
}
