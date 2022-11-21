@extends('layouts.layout')
@section('title', 'attendance')
@section('content')
    <div class="container my-2">
        <div class="row">
            <div class="col d-flex justify-content-between align-items-end">
                <h2 class="m-0">attendance records</h2>
            </div>
        </div>
        <div class="row my-3">
            @php
                $year = isset($query['y']) ? $query['y'] : Illuminate\Support\Carbon::now()->format('Y');
                $month = isset($query['m']) ? $query['m'] : Illuminate\Support\Carbon::now()->format('m');
                $target = Illuminate\Support\Carbon::parse("{$year}-{$month}-1");
            @endphp
            <div class="col-12 my-2 fw-bold d-flex align-items-center">
                <a class="text-decoration-none mx-1 d-flex align-items-center"
                    href="/attend?y={{ $target->copy()->subMonths(1)->format('Y') }}&m={{ $target->copy()->subMonths(1)->format('m') }}">
                    <span class="material-symbols-outlined fs-2">
                        chevron_left
                    </span>
                </a>
                <h3 class="m-0">{{ $target->copy()->format('Y / m F') }}</h3>
                <a class="text-decoration-none mx-1 d-flex align-items-center"
                    href="/attend?y={{ $target->copy()->addMonths(1)->format('Y') }}&m={{ $target->copy()->addMonths(1)->format('m') }}">
                    <span class="material-symbols-outlined fs-2">
                        chevron_right
                    </span>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">date</th>
                            <th scope="col">punch in</th>
                            <th scope="col">punch out</th>
                            <th scope="col">status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$attendances->isEmpty())
                            @foreach ($attendances as $date => $attendance)
                                <tr>
                                    <td>{{ $date }}</td>
                                    <td>{{ $attendance->start_time }}</td>
                                    <td>{{ $attendance->end_time }}</td>
                                    <td class="fw-bold text-danger status" style="width:35%;">{{ $attendance->status }}</td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="4" class=""> we don't have future records</td>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            $(".status").each(function(index, element) {
                const text = $(element).text();
                if (text.includes('leave')) {
                    $(element).removeClass('text-danger').addClass('text-primary');
                }
                if (text.includes('review')) {
                    $(element).removeClass('text-danger').addClass('text-warning');
                }
                if (text.includes('remember')) {
                    $(element).removeClass('text-danger').addClass('text-warning');
                }
                if (text.includes('absent')) {
                    $(element).addClass('text-danger');
                }

            })
        });
    </script>
@endsection
