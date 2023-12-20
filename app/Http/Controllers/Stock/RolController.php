<?php

namespace App\Http\Controllers\Stock;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masters\Role_authorization;
use App\Models\rolsModel;
use App\Models\Stock\Stock;
use View;
use Validator;
use Session;


class RolController extends Controller
{
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
    public function addRol(){
        if (!empty(Session::get('admin'))) {

            $email = Session::get('adminusernmae');
            $data['Roledata'] = Role_authorization::leftJoin('modules', 'role_authorizations.module_name', '=', 'modules.id')
                ->leftJoin('sub_modules', 'role_authorizations.sub_module_name', '=', 'sub_modules.id')
                ->leftJoin('module_configs', 'role_authorizations.menu', '=', 'module_configs.id')
                ->select('role_authorizations.*', 'modules.module_name', 'sub_modules.sub_module_name', 'module_configs.menu_name')
                ->where('member_id', '=', $email)
                ->get();
                $data['shops'] = Stock::distinct()->get(['storeid', 'name']);
               //dd($data['shops']);
            return view('stock/add-rol',$data);
        } else {
            return redirect('/');
        }
    }
    public function saveRol(Request $request)
    {
        $validatedData = $request->validate([
            'shop_id' => 'required',
            'sku_code' => 'required',
            'effective_form' => 'required|date',
            'effective_to' => 'required|date',
            'quantity' => 'required|numeric',
        ]);
        $existingRol = rolsModel::where('storeId', $validatedData['shop_id'])
            ->where('sku', $validatedData['sku_code'])
            ->where('effective_from', $validatedData['effective_form'])
            ->where('effective_to', $validatedData['effective_to'])
            ->first();

        if ($existingRol) {
            $existingRol->quantity = $validatedData['quantity'];
            $existingRol->save();
            Session::flash('message', 'ROL updated successfully.');
        } else {
            $rol = new rolsModel();
            $rol->storeId = $validatedData['shop_id'];
            $rol->sku = $validatedData['sku_code'];
            $rol->effective_from = $validatedData['effective_form'];
            $rol->effective_to = $validatedData['effective_to'];
            $rol->quantity = $validatedData['quantity'];
            $rol->save();
            Session::flash('message', 'ROL saved successfully.');
        }

        return redirect('stock/rol');
    }


    public function getSkuCodes(Request $request)
    {
        $storeid = $request->input('shop_id');
        $skuCodes = Stock::where('storeid', $storeid)->distinct()->pluck('barcode');
        return response()->json($skuCodes);
    }

    public function getData(Request $request)
    {
        $skuCode = $request->input('sku_code');
        $data = Stock::where('barcode', $skuCode)->get();
        // dd($data);
        return response()->json($data);
    }


}
