<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{
	User,
	Gender,
	Teacher,
	Module,
	Term,
	Subject,
	Mark
};
use Crypt;
use Log;
use DataTables;
use Utils;
use DB;

class TeacherDashboardController extends Controller
{
    public function index(Request $request){
    	$students = User::select('name','email')->where(['teacher'=>auth()->user()->id])->get();
    	return view('teacher.home')->with(compact('students'));
    }
    public function getStudentMark (Request $request){
    	$subjects  = Subject::orderBy('id')->get();
        $gettermMarks = Mark::query()
                    ->select('user_id','term_id',DB::raw('group_concat(marks.subject_id) as subject_id'),DB::raw('group_concat(marks.marks) as marks'),'marks.created_at as created_at')
                    ->with(['getUser:id,name,email','getTerm:id,name'])
                    ->orderBy('subject_id')
                    ->groupBy('term_id','user_id','created_at')
                    ->get();
	   $termMarks = $gettermMarks->map(function($term) {
			            return [
			            	'user' => $term->getUser->name,
			            	'email' => $term->getUser->email,
			                'term' => $term->getTerm->name,
			                'marks' => array_combine(explode(',',$term->subject_id),explode(',',$term->marks)),
			                'sum' =>array_sum(explode(',',$term->marks)),
			                'term_id' => $term->term_id,
                            'user_id' =>$term->user_id,
			                'created_at' => $term->created_at
			            ];
			        })->values();
    	return view('teacher.get_student')->with(compact('termMarks','subjects'));
    }
    public function createStudentMark(Request $request){
        $token    = $request->route('token');
        $marks    = [];
        if(!is_null($token)){
            $edit  = explode('-',Crypt::decrypt($token));
            $marks = Mark::where(['term_id'=>$edit[0],'user_id'=>$edit[1]])->get()->keyBy('subject_id');
        }
    	$user     = User::select('id','name')->get();
    	$term     = Term::select('id','name')->where(['status'=>1])->get();
    	$subjects = Subject::select('id','name')->where(['status'=>1])->get();
    	// $module  = Module::with(['moduleTerm:id,name','moduleSubjects:id,name'])->where(['status'=>1])->get();
    	return view('teacher.create_student_marks')->with(compact('user','term','subjects','marks','token'));
    }
    public function createStudentMarkSubmit(Request $request){
    	try{
    		$validator = Validator::make($request->all(),
			    ['student'=>'required',
				    'term'=>'required',
				    'subject.*'  => 'required',
				]
        	);
	        if ($validator->fails()){
	            return redirect()->back()->withInput()->withError(implode(',', $validator->errors()->all()));
	        }
	        $subjects = $request->subject;
	        foreach ($subjects as $key => $value) {
	        	$marksData[]   = [
		        	'term_id'=> $request->term,
		        	'user_id'=> $request->student,
		        	'subject_id'=> $key,
		        	'marks'=>$value,
		        	'created_at'=>now(),
		        	'updated_at'=>now()
	        	];
	        }
	        if($request->type == 'new'){
                $exists  = Mark::where(['term_id'=>$request->term,'user_id'=>$request->student])->exists();
                if(!$exists){
    	        	$userId = Mark::insert($marksData);
                    return redirect()->back()->withSuccess('Student marks assigned successfully!!.');
                }
                return redirect()->back()->withError('Already assigned marks for the student for the term!!');
	        }else{
                $edit = explode('-',Crypt::decrypt($request->type));
                $cases  = [];
                $ids    = [];
                $params = [];
                foreach ($subjects as $key => $value) {
                    $id = (int) $key;
                    $cases[] = "WHEN {$id} then ?";
                    $params[] = $value;
                    $ids[] = $id;
                } 
                $ids = implode(',', $ids);
                $cases = implode(' ', $cases);
                $params[] = now();
                DB::update("UPDATE marks SET `marks` = CASE `subject_id` {$cases} END, `updated_at` = ? WHERE `subject_id` in ({$ids}) AND term_id =".$edit[0]." AND user_id = ".$edit[1]." AND deleted_at IS NULL ",$params);
                return redirect()->back()->withSuccess('Student marks updated successfully!!.');
            }
    	}catch(\Exception $e){
    		Log::error('teacher-student-marks-assign',[
                'result' =>   json_encode($e->getMessage()),
                'request'=> json_encode($request->all())
            ]);
            return redirect()->back()->withError('Something went wrong');
    	}
    }
    public function deleteStudentMark(Request $request){
        try{
            $token = $request->route('token');
            $delete = explode('-',Crypt::decrypt($token));
            Mark::where(['term_id'=>$delete[0],'user_id'=>$delete[1]])->delete();
            return redirect()->back()->withSuccess('Student marks deleted successfully!!.');
        }catch(\Exception $e){
          return redirect()->back()->withError('something went wrong');  
        }
    }
    public function logout(Request $request){
        auth()->logout();
        return redirect('/teacher');
    }
}

