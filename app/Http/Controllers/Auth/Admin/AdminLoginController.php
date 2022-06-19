<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    public function showLogin(){
      return view('auth.admin.login');
    }
    public function login(Request $request){
    	try{
    		$validator = Validator::make($request->all(), [
                'email' =>  'required|email|max:255',
                'password'  => 'required|string|min:2'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withError(implode(',', $validator->errors()->all())); 
            }
            $credentials = $request->merge(['status'=>1])->only('email', 'password','status');
            if (!auth()->attempt($credentials)) {
                return redirect()->back()->withInput()->withError('Not a valid credentials'); 
            }
            return redirect()->intended(route('admin.dashboard'));
    	}catch(\Exception $e){

    	}
    }
}
