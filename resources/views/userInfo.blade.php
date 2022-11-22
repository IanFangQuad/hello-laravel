@extends('layouts.layout')
@section('title', 'userinfo')
@section('content')
    @include('components.sider')
    <div class="container my-5">
        <h3 class="h3">user info</h3>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $name }}" disabled>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="{{ $email }}" disabled>
        </div>
        <div class="col d-flex justify-content-end">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" id="btn-logout">log out</button>
            </form>
        </div>
    </div>
@endsection
@section('script')
@endsection
