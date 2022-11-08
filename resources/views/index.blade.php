@extends('layouts.layout')
@section('title', 'index')
@section('content')
    <div class="container my-5">
        <h3 class="h3">index</h3>
        <div class="col d-flex justify-content-center m-5">
            <span class="h3">hello, <a href="/user/{{ $id }}">{{ $name }}</a>.</span>
        </div>
        <div class="col d-flex justify-content-end">
            <form action="/logout" method="POST">
                <input class="d-none" type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-primary" id="btn-logout">log out</button>
            </form>
        </div>
    </div>
@endsection
@section('script')
@endsection
