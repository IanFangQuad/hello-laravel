@extends('layouts.layout')
@section('title', 'index')
@section('content')
    <div class="container">
        <div class="col d-flex justify-content-center m-5">
            <span class="h3">hello, <a href="/user/{{ $id }}">{{ $name }}</a>.</span>
        </div>
        <div class="col d-flex justify-content-end">
            <button type="button" class="btn btn-primary" id="btn-logout">log out</button>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            $("#btn-logout").on("click", function() {
                const logout = new Promise((res, rej) => {
                    $.ajax({
                        type: "POST",
                        url: "/logOut",
                        dataType: "json",
                        success: function(response) {
                            res(response);
                        },
                        error: function(error) {
                            rej(error);
                        }
                    });
                });
                (async () => {
                    try {
                        const result = await logout;
                        if (!result.status) {
                            $("#error-msg").text(result.msg);
                            return;
                        }
                        window.location.href = window.location.href;
                    } catch (e) {

                    }
                })();
            })
        });
    </script>
@endsection
