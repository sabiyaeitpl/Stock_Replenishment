<?php

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Masters\Role_authorization;
use App\Models\Role\Employee;
use App\Models\Attendance\Upload_attendence;
use App\Models\Attendance\Process_attendance;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
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
use App\Imports\importSales;
use App\Imports\importRol;
// use Excel;
use App\Models\importUserModel;
use App\Models\salesModel;
use App\Models\rolsModel;

use DB;
class StockController extends Controller
{
    public function viewdashboard(Request $request){
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
                ->where('stock.barcode', $item->sku)
                ->where('stock.stock_quantity','<',$quantity)
                ->get();
            if ($response->isNotEmpty()) {
                $data['rolValue'][] = $response;
            }
        }
        return view('stock/dashboard',$data);

            }else {
                return redirect('/');
            }
    }



}
