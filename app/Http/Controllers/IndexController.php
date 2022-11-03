<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller {

    public $Request;

    public function __construct() {

    }

    public function Index(Request $request) {
        if (!$request->session()->has('userID')) {
            return view('login');
        }
        return view('index');
    }

    public function LogIn(Request $request) {

        if(!$request->has(['email', 'password'])){
            return array('status' => false, 'msg' => 'please input email/password.');
        }

        $email = $request->email;
        $password = md5($request->password);
        $user = DB::table('member')->where('email', $email)->get();
        if(!$user->count()){
            return array('status' => false, 'msg' => 'this email does not exist.');
        }
        if(!($user->first()->password === $password)){
            return array('status' => false, 'msg' => 'wrong password.');
        }

        session(['userID' => $user->first()->id,'email' => $user->first()->email,]);

        return array('status' => true,);
    }

    public function LogOut(Request $request) {

        session()->flush();

        return array('status' => true,);
    }
}
