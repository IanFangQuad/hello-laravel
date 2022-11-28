<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use \App\Repositories\MemberRepository;

class AccountService
{

    private $memberRepository;

    public function __construct(MemberRepository $memberRepository)
    {
        $this->MemberRepository = $memberRepository;
    }

    public function login(array $credentials)
    {
        $status = Auth::attempt($credentials);

        if (!$status) {
            throw ValidationException::withMessages(['wrong email/password.']);
        }

        return redirect('/');
    }

    public function register(array $formData)
    {
        $status = $this->MemberRepository->create($formData);

        if (!$status) {
            throw ValidationException::withMessages(['register fail, please try again']);
        }

        return redirect()->back()->with('msg', 'register success');
    }
}
