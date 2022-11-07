<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Services;

use Illuminate\Http\Request;
use \App\Models\Member;

class AccountService  {

    private $Member;

    public function __construct(Member $Member,)
    {
        $this->Member = $Member;
    }

    public function LogIn(Request $request) : array
    {
        $validated = $request->validated();
        $email = $validated['email'];
        $password = md5($validated['password']);

        $where = array(['email', '=', $email]);
        $user = $this->Member->findMember($where);

        if(!$user->count()){
            return array('status' => false, 'msg' => 'this email does not exist.');
        }

        if(!($user->first()->password === $password)){
            return array('status' => false, 'msg' => 'wrong password.');
        }

        session(['userID' => $user->first()->id, 'email' => $user->first()->email, 'name' => $user->first()->name,]);

        return array('status' => true,);
    }
}
