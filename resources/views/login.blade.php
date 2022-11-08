@extends('layouts.layout')
@section('title', 'login')
@section('content')
    <div class="container my-5">
        <h3 class="h3">log in</h3>
        <form id="form-login" action="/login" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
            </div>
            <input class="d-none" type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col d-flex justify-content-between">
                <div>
                    <div class="col text-danger" id="error-msg">
                        @if ($errors->any())
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div>
                    <a href="/signup" class="text-white text-decoration-none">
                        <button id="" type="button" class="btn btn-secondary mx-1">
                            sign up
                        </button>
                    </a>
                    <button id="btn-login" type="submit" class="btn btn-primary mx-1">log in</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
@endsection
