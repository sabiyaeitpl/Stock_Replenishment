<?php

namespace App\Http\Controllers\Attendance;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Masters\Role_authorization;
use App\Models\Role\Employee;
use App\Models\Attendance\Upload_attendence;
use App\Models\Attendance\Process_attendance;
use View;
use Validator;
use Session;
use App\Models\User;
use League\Csv\Reader;
use Illuminate\Support\Facades\Hash;
use DateTime;
class DailyAttendanceController extends Controller
{
	public function viewDailyAttendance()
	{
		if (!empty(Session::get('admin'))) {

				$data['Roledata']= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();
				$data['result']='';
	return View('attendance/daily-attendence',$data);
		} else {
			return redirect('/');
		}
	}

		public function getDailyAttandance(Request $request)
	{
		
	if (!empty(Session::get('admin'))) {

				$Roledata= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();

		$filename=$result='';
		
		$validator = Validator::make($request->all(), 
		[
		'month_yr' => 'required'		
        ],
		[
		 'month_yr.required'=>'Month, Year Field Required'
		]);
		
		if($validator->fails())
		{
			return redirect('attendance/daily-attendance')->withErrors($validator)->withInput();
		}
		
		
		$employee_id=$request->employee_code;
		$month_yr=$request->month_yr;
		
		
		if($employee_id == '')
		
		{
		

			
                   $leave_allocation_rs=Upload_attendence::where('month_yr','=',$month_yr)
                            ->get();
		}
		else
		{
 			$leave_allocation_rs=Upload_attendence::where('month_yr','=',$month_yr)
         	->where('employee_code','=',$employee_id)
            ->get();
		}
		
		//dd($leave_allocation_rs);
		if($leave_allocation_rs)
		{
			foreach($leave_allocation_rs as $leave_allocation)
			{
				$result .='<tr>
							<td><div class="checkbox"><label><input type="checkbox" name="upload_attendence_id[]" value="'.$leave_allocation->id.'"></label></div></td>
							<td>'.$leave_allocation->employee_code.'</td>
							
							<td>'.$leave_allocation->name.'</td>
							
							<td>'.$leave_allocation->att_date.'</td> 
							
							<td><input type="text" class="form-control" name="arrival_time'.$leave_allocation->id.'" value="'.$leave_allocation->arrival_time.'"></td>
							<td><input type="text" class="form-control" name="departure_time'.$leave_allocation->id.'" value="'.$leave_allocation->departure_time.'"></td>
							<td>'.$leave_allocation->duty_hrs.'</td>
							<!-- <td><a href="#" title="Edit"><i class="ti-pencil-alt"></i></a><a href="#" title="Delete"><i class="ti-trash"></i></a></td> -->
						</tr>';
			}
		}
		$month_yr_new=$month_yr;
		return view('attendance/daily-attendence',compact('result','Roledata','employee_id','month_yr_new'));
		
			} else {
			return redirect('/');
		}
	}
	
	
	
	public function updateDailyAttendance(Request $request)
	{
	
		if (!empty(Session::get('admin'))) {

				$Roledata= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();
		if( isset($request->upload_attendence_id) && count($request->upload_attendence_id)!=0)
		{
			$i=0;
			foreach($request->upload_attendence_id as $attandance_id)
			{
				$upload_attendence=Upload_attendence::find($attandance_id);
				
			
$datetime1 = new DateTime($request['arrival_time'.$attandance_id]);
$datetime2 = new DateTime($request['departure_time'.$attandance_id]);
$interval = $datetime1->diff($datetime2);
$dutyhr=$interval->format('%h')." Hours ".$interval->format('%i')." Minutes";

				
				$upload_attendence->arrival_time=$request['arrival_time'.$attandance_id];
				$upload_attendence->departure_time=$request['departure_time'.$attandance_id];
                                $upload_attendence->duty_hrs=$dutyhr;
				
				
				$upload_attendence->save();
				$i++;
			}
			Session::flash('message','Daily Attendance Information Successfully Updated.');
		}else{
			Session::flash('error','No data selected.');
		}
			 
		return redirect('attendance/daily-attendance');
		} else {
			return redirect('/');
		}
		
		
	}
}
