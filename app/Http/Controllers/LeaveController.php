<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\LeavePostRequest;
use \App\Repositories\LeaveRepository;

class LeaveController extends Controller
{

    private $leaveRepository;
    private $member;

    public function __construct(LeaveRepository $leaveRepository, )
    {
        $this->LeaveRepository = $leaveRepository;
    }

    public function create(LeavePostRequest $request)
    {

        $formData = $request->safe()->only(['member_id', 'type', 'start-date', 'start-time', 'end-date', 'end-time', 'description', 'hours']);

        $formData['start-date'] = str_replace('/', '-', $formData['start-date']);
        $formData['start'] = $formData['start-date'] . ' ' . $formData['start-time'];
        $formData['end'] = $formData['end-date'] . ' ' . $formData['end-time'];
        $formData['approval'] = 0;
        unset($formData['start-date'], $formData['start-time'], $formData['end-date'], $formData['end-time']);

        $this->LeaveRepository->create($formData);

        return redirect()->back()->with('msg', 'add success');
    }

    public function delete(Request $request, $id)
    {
        $this->LeaveRepository->delete($id);

        return redirect()->back()->with('msg', 'delete success');
    }

    public function update(LeavePostRequest $request, $id)
    {
        $formData = $request->safe()->only(['member_id', 'type', 'start-date', 'start-time', 'end-date', 'end-time', 'description', 'hours']);
        $this->LeaveRepository->update($id, $formData);

        return redirect()->back()->with('msg', 'update success');
    }

}
