<?php

namespace App\Http\Controllers;

namespace App;

use Route;

//use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

 //import controller route
 Route::get('stock/dashboard', 'import\ImportController@viewdashboard');
 Route::get('stock', 'import\ImportController@getStock');
 Route::get('sales', 'import\ImportController@getSales');
 Route::get('add-sales', 'import\ImportController@viewAddSales');
 Route::post('add-sales', 'import\ImportController@importSaalesExcel');
 Route::get('add-stock', 'import\ImportController@viewAddStock');
 Route::post('add-stock', 'import\ImportController@importExcel');
 Route::post('add-rol', 'import\ImportController@importRolExcel');
 Route::get('compare','import\ImportController@salesCompare');

 //Rol Route
 Route::get('stock/rol', 'Stock\RolController@getRol');
 Route::get('stock/add-rol','Stock\RolController@addRol');
 Route::post('stock/add-rol','Stock\RolController@saveRol');
 Route::post('/get-sku-codes', 'Stock\RolController@getSkuCodes');
 Route::post('/get-data', 'Stock\RolController@getData');


 //end import controller route

//******* Routes with Login  start *********//
Route::get('/', 'HomeController@getlogin');
Route::post('/login', 'HomeController@DoLogin');
Route::get('dashboard', 'HomeController@Dashboard');
Route::get('change-password', 'HomeController@changepassword');
Route::post('save-change-password', 'HomeController@savechangepassword');
Route::get('logout', 'HomeController@Logout');
Route::get('masters/dashboard', 'HomeController@mastersdashboard');
Route::get('hcm-dashboard', 'HomeController@hcmdashboard');
Route::get('finance-dashboard', 'HomeController@FinanceDashboard');
//******* Routes with Login  end *********//

//******* Routes with Role start *********//
Route::get('role/dashboard', 'Role\UserAccessRightsController@viewdashboard');
Route::get('role/vw-users', 'Role\UserAccessRightsController@viewUserConfig');
Route::get('role/add-user-config', 'Role\UserAccessRightsController@viewUserConfigForm');
Route::post('role/save-user-config', 'Role\UserAccessRightsController@SaveUserConfigForm');
Route::get('role/edit-user-config/{user_id}', 'Role\UserAccessRightsController@GetUserConfigForm');
Route::post('role/update-user-config', 'Role\UserAccessRightsController@UpdateUserConfigForm');

Route::get('role/view-users-role', 'Role\UserAccessRightsController@viewUserAccessRights');
Route::get('role/add-user-role', 'Role\UserAccessRightsController@viewUserAccessRightsForm');
Route::post('role/save-user-role', 'Role\UserAccessRightsController@saveUserAccessRightsForm');
Route::get('role/delete-users-role/{role_authorization_id}', 'Role\UserAccessRightsController@deleteUserAccess');

Route::get('role/get-sub-modules/{id_module}', 'Role\UserAccessRightsController@subModuleID');
Route::get('role/get-role-menu/{id_sub_module}', 'Role\UserAccessRightsController@subMenuID');
//******* Routes with Role end *********//

