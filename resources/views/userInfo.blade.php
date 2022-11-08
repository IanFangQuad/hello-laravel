@extends('layouts.layout')
@section('title', 'login')
@section('content')
    <div class="container">
        <h3 class="h3">user info</h3>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $name }}" disabled>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="{{ $email }}" disabled>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function() {});
    </script>
@endsection
