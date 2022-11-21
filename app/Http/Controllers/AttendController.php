<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $formData = $request->safe()->only(['member_id', 'date', 'start_time']);

        return $this->PunchService->punchin($formData);
    }

    public function update(AttendPostRequest $request, $id)
    {
        $formData = $request->safe()->only(['member_id', 'date', 'end_time']);

        return $this->PunchService->punchout($formData, $id);
    }

}
