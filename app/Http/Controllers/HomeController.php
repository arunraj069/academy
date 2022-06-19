<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Subject,
    Mark
};
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function getMark (Request $request){
        $subjects  = Subject::orderBy('id')->get();
        $gettermMarks = Mark::query()
                    ->select('user_id','term_id',DB::raw('group_concat(marks.subject_id) as subject_id'),DB::raw('group_concat(marks.marks) as marks'),'marks.created_at as created_at')
                    ->with(['getUser:id,name,email','getTerm:id,name'])
                    ->where('user_id', auth()->user()->id)
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
        return view('get_student')->with(compact('termMarks','subjects'));
    }
}
