<?php

namespace App\Http\Controllers\Masters;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bank;
use App\Models\Masters\Religion;
use view;
use Validator;
use Session;
use Illuminate\Support\Facades\Input;

class ReligionController extends Controller
{

  public function viewReligionList()
  {
    if (!empty(Session::get('admin'))) {

      // if(Input::get('del'))
      // {
      //     Religion::where('id', Input::get('del'))
      //     ->update(['status' => 'Trash']);
      //            Session::flash('message','Religion Successfully Deleted.');
      //           return back();
      // }

      $data['Religions'] = Religion::where('status', '=', 'active')->get();
      return view('masters/view-religion', $data);
    } else {
      return redirect('/');
    }
  }

  public function addReligionForm()
  {
    if (!empty(Session::get('admin'))) {

      return view('masters/add-new-religion');
    } else {
      return redirect('/');
    }
  }

  public function editReligionForm($id)
  {
    if (!empty(Session::get('admin'))) {

      if ($id != '') {

        $data['getRel'] = Religion::where('id', '=', $id)->where('status', '=', 'active')->get();
        return view('masters/edit-new-religion', $data);
      } else {
        return view('masters/add-new-religion');
      }
    } else {
      return redirect('/');
    }
  }

  public function saveReligionFormSubmit(Request $request)
  {

    if (!empty(Session::get('admin'))) {

      $rel_name = strtoupper(trim($request->rel_name));


      if (is_numeric($rel_name) == 1) {
        Session::flash('error', 'Religion Name Should not be numeric.');
        return redirect('masters/vw-religion');
      }
      $check_religion_name = Religion::where('religion_name', $request->rel_name)->first();
      if (!empty($check_religion_name)) {
        Session::flash('error', 'Already Exists.');
        return redirect('masters/vw-religion');
      }

      $validator = Validator::make(
        $request->all(),
        [
          //'rel_id'=>'required',
          'rel_name' => 'required|max:255'
        ],
        [
          //'rel_id.required'=>'Religion ID Required',
          'rel_name.required' => 'Religion Name Required'
        ]
      );

      if ($validator->fails()) {
        return redirect('masters/add-new-religion')->withErrors($validator)->withInput();
      } else {

        $data = array(
          'religion_id' => $request->rel_id,
          'religion_name' => strtoupper($request->rel_name),
          'status' => 'active'

        );

        $dataInsert = Religion::insert($data);
        Session::flash('message', 'Religion Successfully saved.');
        return redirect('masters/vw-religion');
      }
    } else {
      return redirect('/');
    }
  }


  public function updateReligionFormSubmit(Request $request)
  {

    if (!empty(Session::get('admin'))) {

      $rel_name = strtoupper(trim($request->rel_name));


      if (is_numeric($rel_name) == 1) {
        Session::flash('error', 'Religion Name Should not be numeric.');
        return redirect('masters/vw-religion');
      }
      // $check_religion_name = Religion::where('religion_name', $request->rel_name)->first();
      // if (!empty($check_religion_name)) {
      //   Session::flash('message', 'Already Exists.');
      //   return redirect('masters/vw-religion');
      // }

      $validator = Validator::make(
        $request->all(),
        [
          //'rel_id'=>'required',
          'rel_name' => 'required|max:255'
        ],
        [
          //'rel_id.required'=>'Religion ID Required',
          'rel_name.required' => 'Religion Name Required'
        ]
      );

      if ($validator->fails()) {
        return redirect('masters/add-new-religion')->withErrors($validator)->withInput();
      } else {

        $data = array(
          'religion_id' => $request->rel_id,
          'religion_name' => strtoupper($request->rel_name),
          'status' => $request->status
        );
        Religion::where('id', $request->id)
          ->update($data);
        Session::flash('message', 'Religion Successfully Updated.');
        return redirect('masters/vw-religion');
      }
    } else {
      return redirect('/');
    }
  }
}
