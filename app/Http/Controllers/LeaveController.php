<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Exceptions\PostException;
use \App\Http\Requests\LeavePostRequest;
use \App\Repositories\LeaveRepository;
use \App\Services\CalendarService;

class LeaveController extends Controller
{

    private $leaveRepository;
    private $calendarService;

    public function __construct(LeaveRepository $leaveRepository, CalendarService $calendarService)
    {
        $this->LeaveRepository = $leaveRepository;
        $this->CalendarService = $calendarService;
    }

    public function show(Request $request)
    {
        $userName = Auth::user()->name;
        $email = Auth::user()->email;
        $id = Auth::user()->id;

        $canReview = ($request->user()->can('review_leaves'));

        $dateParms = $request->query();
        $calendar = $this->CalendarService->getSchedules($dateParms);

        return view('leave', ['name' => $userName, 'id' => $id, 'calendar' => $calendar, 'canReview' => $canReview]);
    }

    public function store(LeavePostRequest $request)
    {

        $formData = $request->safe()->only(['member_id', 'type', 'start', 'end', 'description', 'hours', 'approval']);

        $status = $this->LeaveRepository->create($formData);

        throw_if(!$status, new PostException);

        return redirect()->back()->with('msg', 'add success');
    }

    public function destroy(Request $request, $id)
    {
        $status = $this->LeaveRepository->delete($id);

        throw_if(!$status, new PostException);

        return redirect()->back()->with('msg', 'delete success');
    }

    public function update(LeavePostRequest $request, $id)
    {
        $formData = $request->safe()->only(['member_id', 'type', 'start', 'end', 'description', 'hours', 'approval']);

        $status = $this->LeaveRepository->update($id, $formData);

        throw_if(!$status, new PostException);

        return redirect()->back()->with('msg', 'update success');
    }

    public function approve(Request $request, $id)
    {
        $formData = ['approval' => 1];

        $status = $this->LeaveRepository->update($id, $formData);

        throw_if(!$status, new PostException);

        return redirect()->back()->with('msg', 'this leave has been approved');
    }

}
