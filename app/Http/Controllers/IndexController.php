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
use \App\Http\Requests\registerPostRequest;
use \App\Http\Services\AccountService;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{

    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->AccountService = $accountService;
    }

    public function index(Request $request)
    {

        $userName = Auth::user()->name;
        $email = Auth::user()->email;
        $id = Auth::user()->id;

        return view('index', ['name' => $userName, 'id' => $id, 'email' => $email]);
    }

    public function loginPage(Request $request)
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

        return redirect('/login_page');
    }

    public function signUp(Request $request)
    {
        return view('signUp');
    }

    public function register(registerPostRequest $request)
    {
        $formData = $request->safe()->only(['name', 'email', 'password']);
        $formData['password'] = Hash::make($formData['password']);
        
        return $this->AccountService->register($formData);
    }
}
