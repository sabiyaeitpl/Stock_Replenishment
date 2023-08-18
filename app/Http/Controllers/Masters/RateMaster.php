<?php

namespace App\Http\Controllers\Masters;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bank;
use App\Models\Masters\Rate_details;
use App\Models\Masters\Rate_master;
use App\Rate;
use view;
use Validator;
use Session;
use Illuminate\Support\Facades\Input;

class RateMaster extends Controller
{
  public function addRateMasterDetailsForm()
  {
    if (!empty(Session::get('admin'))) {

      $data['Rate'] = Rate_master::get();

      return view('masters/add-rate-master', $data);
    } else {
      return redirect('/');
    }
  }

  public function SubmitRateMasterDetailsForm(Request $request)
  {
    if (!empty(Session::get('admin'))) {
$exit=Rate_master::where('head_name','=',$request['head_name'])->where('head_type','=',$request['head_type'])->first();
     if(empty($exit)){
     
        $data = array(
		 'head_name' => $request['head_name'],
          'head_type' => $request['head_type'],
        
        );

        Rate_master::insert($data);
        Session::flash('message', 'Rate Master Successfully Added.');
	 }else{
		    Session::flash('error', 'Rate Master Already Exits.');
	 }
         return redirect('masters/rate-master');
      
    } else {
      return redirect('/');
    }
  }


  public function getRateMasterList()
  {
    if (!empty(Session::get('admin'))) {

      $data['ratelist'] = Rate_master::orderBy('id','DESC')
        ->get();
      //echo "<pre>"; print_r($data); exit;
      return view('masters/rate-master', $data);
    } else {
      return redirect('/');
    }
  }

  public function getRateMasterChart($rate_id)
  {
    if (!empty(Session::get('admin'))) {

      $data['ratedtl'] = Rate_master::where('id', '=', $rate_id)
    
        ->get();
      return view('masters/edit-rate-master', $data);
    } else {
      return redirect('/');
    }
  }

  public function saveRateMasterChart(Request $request)
  {
    if (!empty(Session::get('admin'))) {
$exit=Rate_master::where('head_name','=',$request['head_name'])->where('head_type','=',$request['head_type'])->where('id','!=',$request->id)->first();
     if(empty($exit)){
      Rate_master::where('id', $request->id)
        ->update([
          'head_name' => $request['head_name'],
          'head_type' => $request['head_type'],
         
        ]);
      
      Session::flash('message', 'Rate Master Successfully Updated.');
     
	 }else{
		    Session::flash('error', 'Rate Master Already Exits.');
	 }
	
      return redirect('masters/rate-master');
    } else {
      return redirect('/');
    }
  }
}
