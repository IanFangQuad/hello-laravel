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
use \App\Http\Requests\LogInPostRequest;
use \App\Http\Requests\registerPostRequest;
use \App\Http\Services\AccountService;
use \App\Http\Services\CalendarService;

class IndexController extends Controller
{

    private $accountService;
    private $calendarService;

    public function __construct(AccountService $accountService, CalendarService $calendarService)
    {
        $this->AccountService = $accountService;
        $this->CalendarService = $calendarService;
    }

    public function index(Request $request)
    {

        $userName = Auth::user()->name;
        $email = Auth::user()->email;
        $id = Auth::user()->id;

        $dateParms = $request->query();
        $calendar = $this->CalendarService->getSchedules($dateParms);

        return view('index', ['name' => $userName, 'id' => $id, 'email' => $email, 'calendar' => $calendar]);
    }

    public function signup(Request $request)
    {
        return view('signUp');
    }

    public function register(RegisterPostRequest $request)
    {
        $formData = $request->safe()->only(['name', 'email', 'password']);
        $formData['password'] = Hash::make($formData['password']);

        return $this->AccountService->register($formData);
    }
}
