@extends('layouts.layout')
@section('title', 'index')
@section('content')
    <div class="container my-2">
        <div class="row">
            <div class="col d-flex justify-content-between align-items-end">
                <h2 class="m-0">punch card</h2>
            </div>
        </div>

        <form action="/attend" method="POST">
            @csrf
            <div class="row mt-5 fs-3">
                <div class="col d-flex justify-content-center" id="">
                    <span class="mx-3">{{ Illuminate\Support\Carbon::now()->format('Y-m-d') }}</span>
                    <span class="mx-5">{{ Illuminate\Support\Carbon::now()->format('l') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center clock " id="clock">
                </div>
            </div>
            <input type="text" name="date" id="date" class="d-none"
                value="{{ Illuminate\Support\Carbon::now()->format('Y-m-d') }}">
            <input type="text" name="time" id="time" class="d-none" value="">
            <div class="row px-5 my-3 d-flex justify-content-center">
                <div class="col-7 d-flex justify-content-center">
                    <button type="submit" class="py-3 rounded w-100" id="btn-punch">Punch</button>
                </div>
            </div>
        </form>

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

            let time = h + ":" + m + ":" + s;
            $("#clock").text(time);
            $("#time").val(time);

            setTimeout(showTime, 1000);
        }
    </script>
@endsection
