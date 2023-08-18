<?php

namespace App\Http\Controllers\LeaveManagement;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Masters\Role_authorization;
use App\Models\Role\Employee;
use App\Models\LeaveManagement\Leave_rule;
use App\Models\LeaveManagement\Leave_type;
use App\Models\LeaveManagement\Leave_allocation;
use Illuminate\Support\Facades\DB;
use View;
use Validator;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LeaveAllocationController extends Controller
{
	public function getLeaveAllocation()
	{
		if (!empty(Session::get('admin'))) {

				$data['Roledata']= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();
		 $data['leave_allocation']=Leave_allocation::join('leave_types', 'leave_allocations.leave_type_id', '=', 'leave_types.id')
        ->select('leave_allocations.*', 'leave_types.leave_type_name')
        ->whereYear('leave_allocations.created_at', '=', date('Y'))
        ->orderBy('leave_allocations.id', 'desc')
        ->get();
		return view('leavemanagement/view-leave-allocation', $data);
		} else {
			return redirect('/');
		}
	}

	
	
	public function viewAddLeaveAllocation()
	{

		if (!empty(Session::get('admin'))) {

				$data['Roledata']= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();
		$data['result']	='';
        $data['employees']=Employee::where('status','=','active')->get();
		return view('leavemanagement/add-new-leave-allocation', $data);
		} else {
			return redirect('/');
		}
	}
	
	
	public function getAddLeaveAllocation(Request $request)
	{
			if (!empty(Session::get('admin'))) {

				$Roledata= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();

	    $current_year=date('Y');
	    $previous_year=$current_year-1;

        $employee=Employee::where('emp_code','=',$request->employee_code)->first();

     	$leave_allocations=Leave_rule::leftJoin('leave_types','leave_rules.leave_type_id','=','leave_types.id')
		->select('leave_rules.*','leave_types.leave_type_name')
		->where('leave_rules.effective_from', '>=',date('Y').'-01-01' )
		->where('leave_rules.effective_to', '<=',date('Y').'-12-31' )
		->get();


		$result='';

		foreach($leave_allocations as $leave_allocationkey=>$leave_allocation){
			$i=($leave_allocationkey+1);
$leave_allocate=Leave_allocation::where('employee_code','=',$request->employee_code)
				->where('leave_rule_id','=',$leave_allocation->id)
		        ->where('month_yr', 'like', '%'.date('Y'))
		        ->first();

			if($leave_allocation->carry_forward_type=='yes'){
				

		        $leave_balance=Leave_allocation::where('employee_code','=',$request->employee_code)
				->where('leave_type_id','=',$leave_allocation->leave_type_id)
		        ->whereYear('created_at', '=', $previous_year)
		        ->first();
		        if(empty($leave_balance)){

		        	$total_leave_count=0;
		        }else{
		        	$total_leave_count=$leave_balance->leave_in_hand;

		        }


			}else{

				$total_leave_count=0;
			}

			$leave_in_hand=$total_leave_count+$leave_allocation->max_no;
if(empty($leave_allocate)){
			$result .='<tr>
			    <input type="hidden" value="'.$leave_allocation->leave_type_id.'" class="form-control" name="leave_type_id'.$leave_allocation->id.'"  id="leave_type_id'.$i.'" readonly>


                <input type="hidden" value="'.$employee->emp_code.'" class="form-control" name="employee_code'.$leave_allocation->id.'" id="employee_code'.$i.'"  readonly>
				<td><div class="checkbox"><label><input type="checkbox" name="leave_rule_id[]" value="'.$leave_allocation->id.'"  id="leave_rule_id'.$i.'" ></label></div></td>
				<td>'.$employee->emp_code.'</td>
				<td>'.$employee->emp_fname.' '.$employee->emp_mname.' '.$employee->emp_lname.'</td>
				<td>'.$leave_allocation->leave_type_name.'</td>
				<td><input type="text" value="'.$leave_allocation->max_no.'" name="max_no'.$leave_allocation->id.'" class="form-control" id="max_no'.$i.'"  readonly></td>
				<td><input type="text" id="opening_bal'.$i.'" name="opening_bal'.$leave_allocation->id.'" value="'.$total_leave_count.'" class="form-control"  readonly></td>
				<td><input type="text" id="leave_in_hand'.$i.'" value="'.$leave_in_hand.'" name="leave_in_hand'.$leave_allocation->id.'" class="form-control"></td>
				<td><input type="text" id="month_yr'.$i.'" value="'.date('m').'/'.date('Y').'" name="month_yr'.$leave_allocation->id.'" class="form-control"  readonly>
				</td>

			  </tr>';
}
		}

        $employees=Employee::where('status','=','active')
            ->where('emp_status','!=','TEMPORARY')
            ->where('emp_status','!=','EX-EMPLOYEE')
            ->orderBy('emp_fname', 'asc')
            ->get();
			$employee_code=$request->employee_code;
		return view('leavemanagement/add-new-leave-allocation',compact('result','Roledata','employees','employee_code'));
		} else {
			return redirect('/');
		}

	}
	
	public function getLeaveAllocationByYear($leave_rule_id,$employee_code)
	{
        $current_year=date('Y');
        $monthly_leave_allocation=Leave_allocation::where('employee_code', '=', $employee_code)
        ->where('leave_rule_id', '=', $leave_rule_id)
        ->whereYear('created_at', '=', $current_year)
        ->first();
		return $monthly_leave_allocation;
	}
	
		public function saveAddLeaveAllocation(Request $request)
	{



	if (!empty(Session::get('admin'))) {

				$data['Roledata']= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();

        $allocation_list=$request->all();
//print_r($allocation_list);
//print_r($allocation_list['leave_rule_id']);exit;
		if( isset($allocation_list['leave_rule_id']) && count($allocation_list['leave_rule_id'])!=0)
		{
		foreach($allocation_list['leave_rule_id'] as $allocationkey=>$allocation_value)
		{
$leave_allocation = new Leave_allocation;

           $leave_allocation->leave_type_id=$allocation_list['leave_type_id'.$allocation_value];
            $leave_allocation->leave_rule_id=$allocation_value;
            $leave_allocation->max_no=$allocation_list['max_no'.$allocation_value];
            $leave_allocation->opening_bal=$allocation_list['opening_bal'.$allocation_value];
			$leave_allocation->leave_in_hand=$allocation_list['leave_in_hand'.$allocation_value];
			$leave_allocation->month_yr=$allocation_list['month_yr'.$allocation_value];

			$leave_allocation->employee_code=$allocation_list['employee_code'.$allocation_value];
$leave_allocation->updated_at=date('Y-m-d H:i:s');
$leave_allocation->created_at=date('Y-m-d H:i:s');
$leave_allocation->leave_allocation_status='active';
			$leave_month=$this->getLeaveAllocationByYear($allocation_value, $allocation_list['employee_code'.$allocation_value]);
			if(empty($leave_month)){
				$leave_allocation->save();
            }


		}
		Session::flash('message','Leave Allocation Information Successfully Saved.');
		}else{
			Session::flash('error','No data selected.');
		}

		
		return redirect('leave-management/leave-allocation-listing');
		} else {
			return redirect('/');
		}
	}
	
	
	public function getLeaveAllocationById($leave_allocation_id)
	{
		if (!empty(Session::get('admin'))) {

				$data['Roledata']= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();


		$data['leave_allocation']= Leave_allocation::where('id', $leave_allocation_id)->first();
		$data['leave_type']= Leave_type::where('id', $data['leave_allocation']->leave_type_id)->first();
		return view('leavemanagement/edit-leave-allocation',$data);
		} else {
			return redirect('/');
		}
	}

	public function editLeaveAllocation(Request $request)
	{
			if (!empty(Session::get('admin'))) {

				$Roledata= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();
		Leave_allocation::where('id', $request->id)
	        ->update(['leave_in_hand' =>$request->leave_in_hand,'month_yr'=>$request->month_yr]);
		Session::flash('message','Leave Allocation Information Successfully Updated.');
		return redirect('leave-management/leave-allocation-listing');
		} else {
			return redirect('/');
		}
	}


}
