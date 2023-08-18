<?php

namespace App\Http\Controllers\Masters;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Masters\Education_master;
use Validator;
use Session;
use View;
use Illuminate\Support\Facades\Input;
use Auth;

class EducationController extends Controller
{
    public function getEducation()
    {
        if (!empty(Session::get('admin'))) {

            $education = Education_master::get();
            return view('masters/education', compact('education'));
        } else {
            return redirect('/');
        }
    }

    public function addEducation()
    {
        if (!empty(Session::get('admin'))) {

            return view('masters/add-education');
        } else {
            return redirect('/');
        }
    }

    public function saveEducation(Request $request)
    {
        if (!empty(Session::get('admin'))) {

            $validator = Validator::make(
                $request->all(),
                [
                    'education' => 'required'

                ],
                [
                    'education.required' => 'Education Required'

                ]
            );

            if ($validator->fails()) {
                return redirect('masters/add-education')->withErrors($validator)->withInput();
            }
            //$data = $request->all();
            $data = array(

                'education' => $request->input('education')
            );


            $education = new Education_master();
            $education->create($data);
            Session::flash('message', 'Education Master Information Successfully Saved.');

            return redirect('masters/education');
        } else {
            return redirect('/');
        }
    }

    public function editEducation($id)
    {
        if (!empty(Session::get('admin'))) {

            $data['education'] = Education_master::where('id', '=', $id)->first();
           
            return view('masters/edit-education', $data);
        } else {
            return redirect('/');
        }
    }

    public function updateEducation(Request $request)
    {
        if (!empty(Session::get('admin'))) {

            $validator = Validator::make(
                $request->all(),
                [
                    'education' => 'required'

                ],
                [
                    'education.required' => 'Education Required'

                ]
            );

            if ($validator->fails()) {
                return redirect('masters/edit-education')->withErrors($validator)->withInput();
            }
            //$data = $request->all();
            $data = array(

                'education' => $request->input('education')
            );

            Education_master::where('id', $request['id'])->update($data);
            Session::flash('message', 'Education Master Information Successfully Updated.');
            return redirect('masters/education');
        } else {
            return redirect('/');
        }
    }

    public function deleteEducation($id)
    {
        if (!empty(Session::get('admin'))) {

				$education = Education_master::where('id', $id)->delete();
                Session::flash('message', 'Education Master Information Successfully Deleted.');
				return redirect('masters/education');
			
        } else {
            return redirect('/');
        }
    }


}
