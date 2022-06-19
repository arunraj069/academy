<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{
	Gender,
	Teacher,
	User,
    Term,
    Subject,
    Module
};
use Crypt;
use Log;
use Utils;
use DataTables;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
    public function index(Request $request){
    	return view('admin.home');
    }
    public function getStudent(Request $request){
    	return view('admin.get_student');
    }
    public function createStudent(Request $request){
        $token      = $request->route('token');
        $getuser    = [];
        if(!is_null($token)){
            $edit  = Crypt::decrypt($token);
            $getuser = User::whereId($edit)->first();
        }
    	$gender = Gender::select('id','name')->where(['status'=>1])->get();
    	$teacher  = Teacher::select('id','name')->where(['status'=>1])->get('id','name');
    	return view('admin.create_student')->with(compact('gender','teacher','getuser'));
    }
    public function createStudentSubmit(Request $request){
    	try{
    		$validator = Validator::make($request->all(),
			    ['name'=>'required|string|min:3|max:255',
				    'email'=>'required|string|email|max:255|unique:users,deleted_at,NULL',
				    'age'  => 'required|integer|between:3,30',
				    'gender'  => 'required',
				    'teacher'  => 'required'
				]
        	);
	        if ($validator->fails()){
	            return redirect()->back()->withInput()->withError(implode(',', $validator->errors()->all()));
	        }
	        $studentData = $request->only('name','email','age','gender','teacher');
	        $password    = '';
	        if($request->type == 'new'){
	        	$password = Utils::random(8);
	        	$studentData['password'] = Hash::make($password);
	        	$userId = User::create($studentData);
	        }else{
                $studentData = $request->only('age','gender','teacher');
                User::whereId(Crypt::decrypt($request->type))
                        ->limit(1)->update($studentData);
                return redirect()->back()->withSuccess('Student details updated successfully!!');
            }
	        return redirect()->back()->withSuccess('Student details created successfully!!. Please note the email: '.$request->email.' password: '. $password. ' for reference');
    	}catch(\Exception $e){
    		Log::error('admin-student-create',[
                'result' =>   json_encode($e->getMessage()),
                'request'=> json_encode($request->all())
            ]);
            return redirect()->back()->withError('Something Went Wrong');
    	}
    }
    public function getStudentDetails(Request $request){
        try{
            $querydatas =  User::with(['reporting:id,name','orientation:id,name']);
            return DataTables::of($querydatas)
                    ->editColumn('reporting_to', function ($data) {
                        return $data->reporting->name;
                    })
                    ->editColumn('gender', function ($data) {
                        return $data->orientation->name;
                    })
                    ->editColumn('created_at', function ($data) {
                        return Utils::dateTimeFormat($data->created_at);
                    })
                    ->editColumn('action', function ($data) {
                        return '<p><a href="'.route('admin.createStudent',Crypt::encrypt( $data->id )).'" class="text-muted mark_edit" data-toggle="tooltip" data-id="" title="Edit Mark"><button class="btn btn-sm btn-info">edit</button></a>&nbsp<span><form action="'.route('admin.deleteStudent', Crypt::encrypt( $data->id )).'" method="post"  class="user_delete">
                                   <input type="hidden" name="_method" value="DELETE">
                                   '.csrf_field().'
                                   <input class="btn btn-sm btn-danger user_delete" type="submit" value="Delete" />
                                </form> </span></p>';
                    })
                    ->addIndexColumn()
                    ->make(true);
        }catch(\Exception $e){
            return false;
        }
    }
    public function deleteStudent(Request $request){
        try{
            $token     = $request->route('token');
            $delete_id = Crypt::decrypt($token);
            User::findorFail($delete_id)->delete();
            return redirect()->back()->withSuccess('Student deleted successfully!!.');
        }catch(\Exception $e){
          return redirect()->back()->withError('something went wrong');  
        }
    }
    public function getTerm(Request $request){
      return view('admin.get_term');  
    }
    public function createTerm(Request $request){
        $term = Term::select('id','name')->where(['status'=>1])->get();
        $subject  = Subject::select('id','name')->where(['status'=>1])->get('id','name');
        return view('admin.create_term')->with(compact('term','subject'));
    }
    public function createTermSubmit(Request $request){
        try{
            $validator = Validator::make($request->all(),
                ['term'=>'required',
                'subject' => 'array|min:2'
                ]
            );
            if ($validator->fails()){
                return redirect()->back()->withInput()->withError(implode(',', $validator->errors()->all()));
            }
            $moduleData = [];
            $moduleData['term_id'] = $request->term;
            $moduleData['subjects'] = $request->subject;
            $module = Module::where(['term_id'=> $request->term]);
            if($module->exists()){
                Module::where(['term_id'=> $request->term])->limit(1)->update(['subjects'=>$request->subject]);
            }else{
                Module::create($moduleData);
            }
            return redirect()->back()->withSuccess('Terms Added successfully!!!');
        }catch(\Exception $e){
            Log::error('admin-student-create',[
                'result' =>   json_encode($e->getMessage()),
                'request'=> json_encode($request->all())
            ]);
            return redirect()->back()->withError('Something Went Wrong');
        }
    }
    public function getTermDetails(Request $request){
        try{
            $querydatas =  Module::with(['moduleTerm:id,name','moduleSubjects:id,name']);
            return DataTables::of($querydatas)
                    ->editColumn('term', function ($data) {
                        return $data->moduleTerm->name;
                    })
                    ->editColumn('subject', function ($data) {
                        return $data->moduleSubjects->pluck('name');
                    })
                    ->editColumn('created_at', function ($data) {
                        return Utils::dateTimeFormat($data->created_at);
                    })
                    ->addIndexColumn()
                    ->make(true);
        }catch(\Exception $e){
            return false;
        }  
    }
    public function logout(Request $request){
        auth()->logout();
        return redirect('/admin');
    }
}

