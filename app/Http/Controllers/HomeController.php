<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Subject,
    Mark
};

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
        // $marks = Mark::select(DB::raw('SUM(marks) as marks'))
        //          ->groupBy('term_id','user_id')->get()->toArray();
        // $c = Term::with(['results'])->get()->map(function(Term $term){
           //      return [
           //       'student_name' => $user->name,
           //          'term_name' => $term->name,
           //          'marks' => $term->results->pluck('marks', 'subject_id'),
           //      ];
        //  })->values();
        $subjects  = Subject::orderBy('id')->get();
        $termMarks = Mark::query()
                    ->select('terms.name as term','users.name as uname','users.email as email','marks.*','subjects.name as subject')
                    ->join('terms', 'terms.id', 'marks.term_id')
                    ->join('users', 'users.id', 'marks.user_id')
                    ->join('subjects', 'subjects.id', 'marks.subject_id')
                    ->where('user_id', auth()->user()->id)
                    ->orderBy('subject_id')
                    ->get()
                    ->groupBy('term_id','user_id')
                    ->map(function(\Illuminate\Support\Collection $term) {
                        return [
                            'user' => $term->first()->uname,
                            'email' => $term->first()->email,
                            'term' => $term->first()->term,
                            'marks' => $term->pluck('marks','subject'),
                            'sum' => $term->sum('marks'),
                            'term_id' => $term->first()->term_id,
                            'user_id' =>$term->first()->user_id,
                            'created_at' => $term->first()->created_at
                        ];
                    })->values();
        return view('get_student')->with(compact('termMarks','subjects'));
    }
}
