<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use \App\Models\Member;
use \App\Repositories\MemberRepository;

class AccountService
{

    private $member;
    private $memberRepository;

    public function __construct(Member $member, MemberRepository $memberRepository)
    {
        $this->Member = $member;
        $this->MemberRepository = $memberRepository;
    }

    public function login($credentials)
    {
        $status = Auth::attempt($credentials);

        if (!$status) {
            throw ValidationException::withMessages(['wrong email/password.']);
        }

        return redirect('/');
    }

    public function register($formData)
    {
        $status = $this->MemberRepository->create($formData);

        if (!$status) {
            return redirect()->back()->with('msg', 'register fail, please try again');
        }

        return redirect()->back()->with('msg', 'register success');
    }
}
