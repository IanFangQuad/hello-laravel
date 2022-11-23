<?php

namespace App\Services;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use \App\Exceptions\PostException;
use \App\Repositories\MemberRepository;

class AccountService
{

    private $memberRepository;

    public function __construct(MemberRepository $memberRepository)
    {
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
        try {
            $status = $this->MemberRepository->create($formData);
            throw_if(!$status, new PostException);
        } catch (QueryException $e) {
            throw new PostException;
        }

        return redirect()->back()->with('msg', 'register success');
    }
}
