<?php

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
            throw ValidationException::withMessages(['register fail, please try again']);
        }

        return redirect()->back()->with('msg', 'register success');
    }
}
