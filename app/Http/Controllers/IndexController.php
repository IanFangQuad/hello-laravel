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

class IndexController extends Controller
{

    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->AccountService = $accountService;
    }

    public function index(Request $request)
    {
        if (!Auth::check()) {
            return view('login');
        }

        $userName = Auth::user()->name;
        $email = Auth::user()->email;
        $id = Auth::user()->id;

        return view('index', ['name' => $userName, 'id' => $id, 'email' => $email]);
    }

    public function logIn(LogInPostRequest $request): array
    {
        return $this->AccountService->LogIn($request);
    }

    public function logOut(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return array('status' => true);
    }
}
