<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Http\Requests\AttendPostRequest;
use \App\Services\PunchService;

class AttendController extends Controller
{
    private $punchService;

    public function __construct(PunchService $punchService)
    {
        $this->PunchService = $punchService;
    }

    public function show(Request $request)
    {

    }

    public function store(AttendPostRequest $request)
    {
        $formData = $request->safe()->only(['member_id', 'date', 'time']);

        return $this->PunchService->punch($formData);

    }

    public function update(Request $request, $id)
    {

    }

}
