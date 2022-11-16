<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Http\Requests\LogInPostRequest;
use \App\Http\Services\AccountService;

class AuthController extends Controller
{

    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->AccountService = $accountService;
    }

    public function index(Request $request)
    {
        return view('login');
    }

    public function login(LogInPostRequest $request)
    {
        $credentials = $request->safe()->only(['email', 'password']);
        return $this->AccountService->login($credentials);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

}
