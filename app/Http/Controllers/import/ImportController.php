<?php

namespace App\Http\Controllers\import;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Masters\Role_authorization;
use App\Models\Role\Employee;
use App\Models\Attendance\Upload_attendence;
use App\Models\Attendance\Process_attendance;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use View;
use Validator;
use Session;
use App\Models\User;
use League\Csv\Reader;
use Illuminate\Support\Facades\Hash;
use DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;


use App\Imports\importUser;
use App\Imports\importSales;
use App\Imports\importRol;
// use Excel;
use App\Models\importUserModel;
use App\Models\salesModel;
use App\Models\rolsModel;

use DB;
class ImportController extends Controller
{
    public function paginate($items,$perPage=4,$page=null){
      
        $page=$page ?:(Paginator::resolveCurrentPage()?:1);
        $total=count($items);
        $currentpage=$page;
        $offset=($currentpage * $perPage)- $perPage;
        $itemstoshow=array_slice($items ,$offset,$perPage);
        return new LengthAwarePaginator($itemstoshow,$total,$perPage);
    }

    // public function demo(){
    //     $books=Book::get()->toArray();
    //     $books=$this->paginate($books,2)
    //     $books->path('');

    //     return View('stock/dashboard', $data);
    // }

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

            return View('stock/dashboard', $data);
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

                $data['employee_rs']=importUserModel::get()->toArray();
                $data['employee_rs']=$this->paginate($data['employee_rs'],10);
                $data['employee_rs']->path('');
                
            return view('stock.view-stock', $data);
        } else {
            return redirect('/');
        }
    }

    public function getSearchValue($value){
        $data=importUserModel::where('name','LIKE','%'.$value.'%')
        ->orWhere('barcode','LIKE','%'.$value.'%')
        ->get();
        return Response::json($data);
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

            return view('stock/stock-master',$data);
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

                // $request->validate(
                //     [
                //         'upload_csv' => 'required|mimes:xlsx',
                //     ],
                //     ['upload_csv.required' => 'File Must Be required!',
                //         'upload_csv.mimes' => 'File Must Be XLSX format!']
                // );

                $rasponse=DB::table('stock')->delete();
                    $path = $request->file('upload_csv');
                    Excel::import(new importUser,$path);

                    $delayInSeconds = 30;
                    sleep($delayInSeconds);

                    Session::flash('message', 'Excel Information Successfully saved.');
                    return redirect('stock');
            }else {
                return redirect('/');
            }
    }

    public function getSales(){
        if (!empty(Session::get('admin'))) {

            $email = Session::get('adminusernmae');

            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name')
                ->where('member_id', '=', $email)
                ->get();

                $data['sales_rs'] =DB::table('sales')->select(
                        DB::raw('SUM(bill_quantity) as total_quantity'),
                        DB::raw('barcode as barcode'),
                        DB::raw('date as date')
                    )
                    ->groupBy(DB::raw('barcode'))
                    ->get();


            // $data['sales_rs'] = salesModel::get();

            // dd($data['employee_rs']);
            return view('stock.view-sales', $data);
        } else {
            return redirect('/');
        }
    }

    public function viewAddSales(){
        if (!empty(Session::get('admin'))) {

            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();

            return view('stock/sales-master',$data);
        } else {
            return redirect('/');
        }
    }

    public function importSaalesExcel(Request $request){
        if (!empty(Session::get('admin'))) {

            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', Session::get('adminusernmae'))
                ->get();

                // $request->validate(
                //     [
                //         'upload_csv' => 'required|mimes:xlsx',
                //     ],
                //     ['upload_csv.required' => 'File Must Be required!',
                //         'upload_csv.mimes' => 'File Must Be XLSX format!']
                // );

                $path = $request->file('upload_csv');
                Excel::import(new importSales,$path);

                   $delayInSeconds = 30;
                    sleep($delayInSeconds);
                Session::flash('message', 'Excel Information Successfully saved.');
                return redirect('sales');
            }else {
                return redirect('/');
            }
    }

    public function getRol(){
        if (!empty(Session::get('admin'))) {

            $email = Session::get('adminusernmae');

            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name')
                ->where('member_id', '=', $email)
                ->get();

            $data['sales_rs'] = rolsModel::get();

            return view('stock/view-rol',$data);
        } else {
            return redirect('/');
        }
    }

    public function viewRol(){
        if (!empty(Session::get('admin'))) {

            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();

            return view('stock/rol-master',$data);
        } else {
            return redirect('/');
        }
    }

    public function importRolExcel(Request $request){
        if (!empty(Session::get('admin'))) {

            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')

                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', Session::get('adminusernmae'))
                ->get();

                // $request->validate(
                //     [
                //         'upload_csv' => 'required|mimes:xlsx',
                //     ],
                //     ['upload_csv.required' => 'File Must Be required!',
                //         'upload_csv.mimes' => 'File Must Be XLSX format!']
                // );

                $path = $request->file('rol_csv');
                Excel::import(new importRol,$path);
                   $delayInSeconds = 30;
                    sleep($delayInSeconds);
                Session::flash('message', 'Excel Information Successfully saved.');
                return redirect('stock/rol');
            }else {
                return redirect('/');
            }
    }


    public function salesCompare(){
        if (!empty(Session::get('admin'))) {

            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
            ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
            ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
            ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
            ->where('member_id', '=', Session::get('adminusernmae'))
            ->get();

        $results = DB::table('sales')
        ->select('sales.barcode', DB::raw('SUM(sales.bill_quantity) as total_sales_quantity'), 'rol.quantity as rol_quantity', 'sales.date')
        ->join('rol', function ($join) {
            $join->on('sales.barcode', '=', 'rol.sku')
                ->where(function ($query) {
                    $query->where('sales.date', '<=', DB::raw('rol.effective_from'))
                        ->orWhere('sales.date', '>=', DB::raw('rol.effective_to'));
                });
        })
        ->groupBy('sales.barcode')
        ->orderByDesc('total_sales_quantity')
        ->get();
        $data['rolValue'] = [];
        foreach ($results as $item) {
            $quantity = (int)$item->rol_quantity;
            $response = DB::table('stock')
                ->select('stock.*', 'rol.quantity as rol_quantity')
                ->join('rol', 'stock.barcode', '=', 'rol.sku')
                ->where('stock.barcode', $item->barcode)
                ->where('stock.stock_quantity','<',$quantity)
                ->get();
            if ($response->isNotEmpty()) {
                $data['rolValue'][] = $response;
            }
        }
        return view('stock/rol-stock',$data);
            }else{
                return redirect('/');
            }
        }
}
