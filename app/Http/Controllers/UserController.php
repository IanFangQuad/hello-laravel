<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\LogInPostRequest;
use \App\Http\Services\AccountService;
use \App\Models\Member;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    private $accountService;
    private $member;

    public function __construct(AccountService $accountService, Member $member,)
    {
        $this->AccountService = $accountService;
        $this->Member = $member;
    }

    public function Get(Request $request, $id)
    {

        $userName = Auth::user()->name;
        $email = Auth::user()->email;

        return view('userInfo', ['name' => $userName, 'email' => $email,]);
    }

}
