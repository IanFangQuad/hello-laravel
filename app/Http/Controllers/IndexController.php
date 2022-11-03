<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\LogInPostRequest;
use Illuminate\Support\Facades\DB;
use \App\Http\Services\AccountService;

class IndexController extends Controller {

    private $AccountService;

    public function __construct(AccountService $AccountService) {
        $this->AccountService = $AccountService;
    }

    public function Index(Request $request) {
        if (!$request->session()->has('userID')) {
            return view('login');
        }
        return view('index');
    }

    public function LogIn(LogInPostRequest $request) : array
    {
        return $this->AccountService->LogIn($request);
    }

    public function LogOut(Request $request) : array
    {
        session()->flush();

        return array('status' => true,);
    }
}
