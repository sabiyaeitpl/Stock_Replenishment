<?php

namespace App\Http\Controllers\import;

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
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Arr;

use App\Imports\importUser;
use Excel;
use App\Models\importUserModel;
class ImportController extends Controller
{
	public function viewdashboard()
    {
        if (!empty(Session::get('admin'))) {

            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();

            $data['employeesincrement'] = Employee::whereMonth('emp_next_increament_date', '=', date('m'))
                ->whereYear('emp_next_increament_date', '=', date('Y'))
                ->where('status', '=', 'active')
                ->where('emp_status', '!=', 'TEMPORARY')
                ->where('emp_status', '!=', 'EX-EMPLOYEE')
                ->orderBy('emp_next_increament_date', 'asc')
                ->get();

            $data['employeesdob'] = Employee::whereDay('emp_dob', '>=', date('d'))
                ->whereMonth('emp_dob', '=', date('m'))
                ->where('status', '=', 'active')
                ->where('emp_status', '!=', 'TEMPORARY')
                ->where('emp_status', '!=', 'EX-EMPLOYEE')
                ->orderBy('emp_dob', 'desc')
                ->get();

            $data['employeeretirement'] = Employee::where('emp_retirement_date', '>=', date('Y-m-d'))
                ->whereYear('emp_retirement_date', '=', date('Y'))
                ->where('status', '=', 'active')
                ->orderBy('emp_retirement_date', 'asc')
                ->get();

            return View('employee/dashboard', $data);
        } else {
            return redirect('/');
        }
    }
    public function getStock()
    {

        if (!empty(Session::get('admin'))) {

            $email = Session::get('adminusernmae');

            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name')
                ->where('member_id', '=', $email)
                ->get();

            $data['employee_rs'] = importUserModel::get();

            // dd($data['employee_rs']);
            return view('employee.view-employee', $data);
        } else {
            return redirect('/');
        }
    }
    public function viewAddStock()
    {
        
        if (!empty(Session::get('admin'))) {

            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();
            
            return view('employee/stock-master',$data);
        } else {
            return redirect('/');
        }
    }

   

    public function importExcel(Request $request){
        if (!empty(Session::get('admin'))) {

            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', Session::get('adminusernmae'))
                ->get();

                $request->validate(
                    [
                        'upload_csv' => 'required|mimes:xlsx',
                    ],
                    ['upload_csv.required' => 'File Must Be required!',
                        'upload_csv.mimes' => 'File Must Be XLSX format!']
                );
               
                $path = $request->file('upload_csv');
                Excel::import(new importUser,$path);
                Session::flash('message', 'Excel Information Successfully saved.');
                return redirect('stock');
            }else {
                return redirect('/');
            }
    }
}
