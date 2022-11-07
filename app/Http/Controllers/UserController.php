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


class UserController extends Controller
{

    private $AccountService;
    private $Member;

    public function __construct(AccountService $AccountService, Member $Member,)
    {
        $this->AccountService = $AccountService;
        $this->Member = $Member;
    }

    public function Get(Request $request, $userID)
    {
        if (!$request->session()->has('userID')) {
            return view('login');
        }

        $userName = session('name');
        $email = session('email');

        return view('userInfo', ['name' => $userName, 'email' => $email,]);
    }

}
