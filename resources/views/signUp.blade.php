@extends('layouts.layout')
@section('title', 'sign up')
@section('content')
    <div class="container my-5">
        <h3 class="h3">sign up</h3>
        <form id="form-register" action="/user" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label"><span class="text-danger">*</span>Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label"><span class="text-danger">*</span>Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><span class="text-danger">*</span>Password</label>
                <input type="password" class="form-control" id="password" name="password" value="">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><span class="text-danger">*</span>Password confirmation</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    value="">
            </div>
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
                    <a href="/login" class="text-decoration-none">
                        <button id="" type="button" class="btn btn-secondary mx-1">log in</button>
                    </a>
                    <button id="btn-register" type="submit" class="btn btn-primary mx-1">sign up</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            modalMsg('modal', 'modal-body', '/login');
        });
    </script>
@endsection
