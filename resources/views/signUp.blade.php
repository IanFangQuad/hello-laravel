@extends('layouts.layout')
@section('title', 'sign up')
@section('content')
    <div class="container my-5">
        <h3 class="h3">sign up</h3>
        <form id="form-register" action="/register" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label"><span class="text-danger">*</span>Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label"><span class="text-danger">*</span>Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><span class="text-danger">*</span>Password</label>
                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><span class="text-danger">*</span>Password confirmation</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    value="{{ old('password_confirmation') }}">
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
                    <button id="btn-register" type="submit" class="btn btn-primary mx-1">register</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            modalMsg('modal', 'modal-body', '/login_page');
        });
    </script>
@endsection
