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
use \App\Exceptions\PostException;

class LeaveController extends Controller
{

    private $leaveRepository;

    public function __construct(LeaveRepository $leaveRepository, )
    {
        $this->LeaveRepository = $leaveRepository;
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
        $leave = $this->LeaveRepository->getById($id);
        $this->authorize('delete_leave', $leave);

        $status = $this->LeaveRepository->delete($id);

        throw_if(!$status, new PostException);

        return redirect()->back()->with('msg', 'delete success');
    }

    public function update(LeavePostRequest $request, $id)
    {
        $leave = $this->LeaveRepository->getById($id);
        $this->authorize('update_leave', $leave);

        $formData = $request->safe()->only(['member_id', 'type', 'start', 'end', 'description', 'hours', 'approval']);

        $status = $this->LeaveRepository->update($id, $formData);

        throw_if(!$status, new PostException);

        return redirect()->back()->with('msg', 'update success');
    }

}
