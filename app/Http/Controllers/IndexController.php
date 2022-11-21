<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use \App\Services\PunchService;

class IndexController extends Controller
{

    private $punchService;

    public function __construct(PunchService $punchService)
    {
        $this->PunchService = $punchService;
    }

    public function index(Request $request)
    {
        $params = [
            'member_id' => Auth::user()->id,
            'date' => Carbon::now()->format('Y-m-d'),
        ];

        $attendance = $this->PunchService->getRecord($params)->first();
        // dd($attendance);

        return view('index', ['attendance' => $attendance]);
    }

}
