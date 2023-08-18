<?php

namespace App\Http\Controllers\Holiday;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Masters\Role_authorization;
use App\Models\Role\Employee;
use App\Models\Holiday\Holiday;

use View;
use Validator;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HolidayController extends Controller
{
	public function viewdashboard()
	{
		if (!empty(Session::get('admin'))) {

				$data['Roledata']= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();
	return View('holiday/dashboard',$data);
		} else {
			return redirect('/');
		}
	}

	public function viewHolidayDetails()
	{
	if (!empty(Session::get('admin'))) {

				$data['Roledata']= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();

		$data['holiday_rs']=Holiday::select('*')->orderBy('id', 'DESC')->get();
		// dd($data['holiday_rs']);
		
		return view('holiday/companywise-holiday', $data);
		} else {
			return redirect('/');
		}
	}
	
	public function viewAddHoliday()
	{
		if (!empty(Session::get('admin'))) {

				$data['Roledata']= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();
		return view('holiday/add-holiday',$data);
		} else {
			return redirect('/');
		}
	}
	public function saveHolidayData(Request $request)
	{

if (!empty(Session::get('admin'))) {

				$data['Roledata']= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();
		//echo "<pre>";print_r($request->all()); exit;
		$validator=Validator::make($request->all(),[
		
		'years'=>'required',
		'from_date'=>'required',
		'to_date'=>'required',
		'month'=>'required',
		'day'=>'required',
		'holiday_descripion'=>'required'
		],
		[
		
		'years.required'=>'Year Required',
		'from_date.required'=>'From Date Required',
		'to_date.required'=>'To Date Required',
		'month.required'=>'Month Required',
		'day.required'=>'Day Required',
		'holiday_descripion.required'=>'Holiday Descripion Required',
		]);
		
		/*if($validator->fails())
		{
			return redirect('attendance/add-holiday')->withErrors($validator)->withInput();
			
		}*/
		//$data = $request->all();
		
		//print_r($request->all()); exit;
		$monthYear= explode("-",$request->from_date);
		$data=array(
          'years'=>$monthYear[0],
          'month'=>$monthYear[1],
          'from_date'=>$request->from_date,
          'to_date'=>$request->to_date,
          'day'=>$request->day,
          'weekname'=>$request->weekname,
          'holiday_type'=>$request->holiday_type,
          'holiday_descripion'=>$request->holiday_descripion
        ); 
	
		if(!empty($request->id)){
			Holiday::where('id', $request->id)
	        ->update($data);
			Session::flash('message','Holiday Information Successfully Updated.');

		}else{
			$holiday=new Holiday;
			$holiday->years=$monthYear[0];
			
$holiday->month=$monthYear[1];
$holiday->from_date=date('Y-m-d',strtotime($request->from_date));
$holiday->to_date=date('Y-m-d',strtotime($request->to_date));
$holiday->day=$request->day;
$holiday->weekname=$request->weekname;
$holiday->holiday_type=$request->holiday_type;
$holiday->holiday_descripion=$request->holiday_descripion;
$holiday->updated_at=date('Y-m-d H:i:s');
$holiday->created_at=date('Y-m-d H:i:s');
			$holiday->save();
			Session::flash('message','Holiday Information Successfully Saved.');
		}
		
		
		return redirect('holidays');
		} else {
			return redirect('/');
		}
	}
	
	public function getHolidayDtl($holiday_id)
	{
		
		if (!empty(Session::get('admin'))) {

				$data['Roledata']= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();

		$data['holidaydtl']=Holiday::where('id',$holiday_id)->first();
		// dd($data);
		
		return view('holiday/add-holiday', $data);
		} else {
			return redirect('/');
		}
	}

	public function deleteHoliday($holiday_id)
	{
		if (!empty(Session::get('admin'))) {

				$data['Roledata']= Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

				->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
				->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
				->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
				->where('member_id', '=', Session::get('adminusernmae'))
				->get();
		$result= Holiday::where('id', $holiday_id)->delete();
		Session::flash('message','Holiday Deleted Successfully.');
		return redirect('holidays');
		} else {
			return redirect('/');
		}
	}
}
