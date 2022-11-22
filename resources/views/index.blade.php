@extends('layouts.layout')
@section('title', 'index')
@section('content')
    @include('components.sider')
    <div class="container my-2">
        <div class="row">
            <div class="col d-flex justify-content-between align-items-end">
                <h2 class="m-0">punch card</h2>
            </div>
        </div>
        @php
            $method = $attendance ? 'PATCH' : 'POST';
            $id = $attendance ? $attendance->id : '';
            $action = $attendance ? "/attend/{$id}" : '/attend';
        @endphp
        <form action="{{ $action }}" method="POST">
            @csrf
            <input type="text" name="_method" class="d-none" value="{{ $method }}">
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
            <input type="text" name="time" id="time" class="d-none" value="">
            <div class="row px-5 my-3 d-flex justify-content-center">
                <div class="col-7 d-flex justify-content-center">
                    <button type="submit" class="py-3 rounded w-100" id="btn-punch">Punch</button>
                </div>
            </div>
        </form>

        @if ($attendance)
            <div class="row">
                <div class="col">
                    <h3 class="mt-5">today's record</h3>
                    <table class="table my-3">
                        <thead>
                            <tr>
                                <th scope="col">date</th>
                                <th scope="col">action</th>
                                <th scope="col">time</th>
                                <th scope="col">status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $attendance->date }}</td>
                                <td>punch in</td>
                                <td>{{ $attendance->start_time }}</td>
                                <td class="text-danger fw-bold">{{ $attendance->status['start_time'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $attendance->date }}</td>
                                <td>punch out</td>
                                <td>{{ $attendance->end_time }}</td>
                                <td class="text-danger fw-bold">{{ $attendance->status['end_time'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

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
