<?php

namespace App\Policies;

use App\Models\Leave;
use App\Models\Member;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeavePolicy
{
    use HandlesAuthorization;

	public function review(Member $member)
    {
        return ($member->type == 'admin');
    }
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Member $member)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Member  $member
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Member $member, Leave $leave)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Member $member)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Member  $member
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Member $member, Leave $leave)
    {
        return $member->id === $leave->member_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Member  $member
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Member $member, Leave $leave)
    {
        return $member->id === $leave->member_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Member  $member
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Member $member, Leave $leave)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Member  $member
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Member $member, Leave $leave)
    {
        //
    }
}
