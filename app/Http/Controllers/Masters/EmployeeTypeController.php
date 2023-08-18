<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmployeeType;
use App\Models\Masters\Company;
use App\Models\Masters\Employee_type;
use View;
use Validator;
use Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;

class EmployeeTypeController extends Controller
{
	//
	public function addEmployeeType()
	{
		if (!empty(Session::get('admin'))) {

			$company_rs = Company::where('company_status', '=', 'active')->select('id', 'company_name')->get();
			return view('masters/employee-type', compact('company_rs'));
		} else {
			return redirect('/');
		}
	}



	public function saveEmployeeType(Request $request)
	{
		if (!empty(Session::get('admin'))) {

			$employee_type_name = strtoupper(trim($request->employee_type_name));

			if (is_numeric($employee_type_name) == 1) {
				Session::flash('error', 'Employee Type Should not be numeric.');
				return redirect('masters/vw-employee-type');
			}
			$employee_type = Employee_type::where('employee_type_name', $request->employee_type_name)->first();
			if (!empty($employee_type)) {
				Session::flash('error', 'Employee Type Alredy Exists.');
				return redirect('masters/vw-employee-type');
			}

			$validator = Validator::make(
				$request->all(),
				[
					'employee_type_name' => 'required|max:255'
				],
				['employee_type_name.required' => 'Employee Type Name required']
			);

			if ($validator->fails()) {
				return redirect('masters/employee-type')->withErrors($validator)->withInput();
			}

			//$data=request()->except(['_token']);

			$employee_type = new Employee_type();



			Employee_type::insert(
				['employee_type_name' => $employee_type_name, 'employee_type_status' => 'Active', 'created_at' => date("Y-m-d H:i:s")]
			);


			Session::flash('message', 'Employee Type Information Successfully saved.');

			return redirect('masters/vw-employee-type');
		} else {
			return redirect('/');
		}
	}


	public function updateEmployeeType(Request $request)
	{
		if (!empty(Session::get('admin'))) {

			$employee_type_name = strtoupper(trim($request->employee_type_name));

			if (is_numeric($employee_type_name) == 1) {
				Session::flash('error', 'Employee Type Should not be numeric.');
				return redirect('masters/vw-employee-type');
			}
			$employee_type = Employee_type::where('employee_type_name', $request->employee_type_name)->where('id', '!=', $request->id)->first();
			if (!empty($employee_type)) {
				Session::flash('error', 'Employee Type Alredy Exists.');
				return redirect('masters/vw-employee-type');
			}

			$validator = Validator::make(
				$request->all(),
				[
					'employee_type_name' => 'required|max:255'
				],
				['employee_type_name.required' => 'Employee Type Name required']
			);

			if ($validator->fails()) {
				return redirect('masters/employee-type')->withErrors($validator)->withInput();
			}

			//$data=request()->except(['_token']);

			$employee_type = new Employee_type();


			Employee_type::where('id', $request->id)
				->update(['employee_type_name' => $employee_type_name]);
			Session::flash('message', 'Employee Type Information Successfully Saved.');
			return redirect('masters/vw-employee-type');
		} else {
			return redirect('/');
		}
	}

	public function getEmployeeTypes()
	{
		if (!empty(Session::get('admin'))) {

			$employee_type_rs = Employee_type::get();

			return view('masters/view-employee-type', compact('employee_type_rs'));
		} else {
			return redirect('/');
		}
	}

	public function getTypeById($id)
	{
		if (!empty(Session::get('admin'))) {

			$data['employee_type'] = Employee_type::where('id', $id)->first();
			return view('masters/edit-employee-type', $data);
		} else {
			return redirect('/');
		}
	}
}
