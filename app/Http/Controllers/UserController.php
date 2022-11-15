<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Services\AccountService;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    private $accountService;
    private $member;

    public function __construct(AccountService $accountService)
    {
        $this->AccountService = $accountService;
    }

    public function show(Request $request, $id)
    {

        $userName = Auth::user()->name;
        $email = Auth::user()->email;

        return view('userInfo', ['name' => $userName, 'email' => $email,]);
    }


}
