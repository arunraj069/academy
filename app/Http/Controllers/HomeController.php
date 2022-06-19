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
        $subjects  = Subject::orderBy('id')->get();
        $termMarks = Mark::with(['getUser:id,name as uname,email','getTerm:id,name as term','getSubject:id,name as subject'])
                    ->where('user_id', auth()->user()->id)
                    ->orderBy('subject_id')
                    ->get()
                    ->groupBy('term_id','user_id')
                    ->map(function(\Illuminate\Support\Collection $term) {
                        $marks   = $term->pluck('marks','subject_id');
                        $getData = $term->map(function($data){
                            return (object)[
                                    'user' =>$data->getUser->uname,
                                    'email' => $data->getUser->email,
                                    'term' => $data->getTerm->term,
                                ];
                        });
                        return [
                            'user' => $getData->first()->user,
                            'email' => $getData->first()->email,
                            'term' => $getData->first()->term,
                            'marks' => $marks,
                            'sum' => $term->sum('marks'),
                            'term_id' => $term->first()->term_id,
                            'user_id' =>$term->first()->user_id,
                            'created_at' => $term->first()->created_at
                        ];
                    })->values();
        return view('get_student')->with(compact('termMarks','subjects'));
    }
}
