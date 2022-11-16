<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \App\Http\Services\AccountService;
use \App\Http\Requests\registerPostRequest;



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

    public function store(Request $request)
    {
        return view('signUp');
    }

    public function create(RegisterPostRequest $request)
    {
        $formData = $request->safe()->only(['name', 'email', 'password']);
        $formData['password'] = Hash::make($formData['password']);

        return $this->AccountService->register($formData);
    }

}
