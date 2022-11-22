<?php

namespace App\Policies;

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
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Member $member)
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
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Member $member)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Member  $member
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Member $member)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Member  $member
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Member $member)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Member  $member
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Member $member)
    {
        //
    }
}
