<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Services;

use Illuminate\Http\Request;
use \App\Models\Member;
use Illuminate\Support\Facades\Auth;

class AccountService  {

    private $member;

    public function __construct(Member $member,)
    {
        $this->Member = $member;
    }

    public function LogIn(Request $request) : array
    {
        $credentials = $request->safe()->only(['email', 'password']);
        $status = Auth::attempt($credentials);

        return array('status' => $status,);
    }
}
