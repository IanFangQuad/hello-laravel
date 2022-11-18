@extends('layouts.layout')
@section('title', 'index')
@section('content')
    <div class="container my-2">
        <div class="row">
            <div class="col d-flex justify-content-between align-items-end">
                <h2 class="m-0">punch card</h2>
            </div>
        </div>

        <div class="row my-3">
            <div class="col d-flex justify-content-center clock my-5" id="clock">
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $(function() {
            modalMsg('modal', 'modal-body', window.location.href);
            showTime()
        });

        function showTime() {
            const date = new Date();
            let h = date.getHours(); // 0 - 23
            let m = date.getMinutes(); // 0 - 59
            let s = date.getSeconds(); // 0 - 59

            h = (h < 10) ? "0" + h : h;
            m = (m < 10) ? "0" + m : m;
            s = (s < 10) ? "0" + s : s;

            var time = h + ":" + m + ":" + s;
            $("#clock").text(time)

            setTimeout(showTime, 1000);
        }
    </script>
@endsection
