@extends('layouts.layout')
@section('css')
    <link rel="stylesheet" href="/css/calendar.css">
@endsection
@section('title', 'leave')
@section('content')
    @include('components.sider')
    <div class="container my-2">
        <div class="row">
            <div class="col d-flex justify-content-between align-items-end">
                <h2 class="m-0">leave</h2>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-12 my-2 fw-bold d-flex align-items-center">
                <a class="text-decoration-none mx-1 d-flex align-items-center"
                    href="/?y={{ $calendar['query']->copy()->subMonths(1)->format('Y') }}&m={{ $calendar['query']->copy()->subMonths(1)->format('m') }}">
                    <span class="material-symbols-outlined fs-2">
                        chevron_left
                    </span>
                </a>
                <h3 class="m-0">{{ $calendar['query']->copy()->format('Y / m F') }}</h3>
                <a class="text-decoration-none mx-1 d-flex align-items-center"
                    href="/?y={{ $calendar['query']->copy()->addMonths(1)->format('Y') }}&m={{ $calendar['query']->copy()->addMonths(1)->format('m') }}">
                    <span class="material-symbols-outlined fs-2">
                        chevron_right
                    </span>
                </a>
            </div>
            <div class="col my-4">
                <div class="calendar-wrapper d-flex flex-wrap">
                    <div class="calendar-cell calendar-title">Sunday</div>
                    <div class="calendar-cell calendar-title">Monday</div>
                    <div class="calendar-cell calendar-title">Tuesday</div>
                    <div class="calendar-cell calendar-title">Wednesday</div>
                    <div class="calendar-cell calendar-title">Thursday</div>
                    <div class="calendar-cell calendar-title">Friday</div>
                    <div class="calendar-cell calendar-title">Saturday</div>
                    @if ($calendar['holidays']->isEmpty())
                        <div class="col-12 p-5 d-felx align-items-center justify-content-center text-center fs-2">The
                            schedule of year you
                            pick is not ready yet</div>
                    @else
                        @foreach ($calendar['dates'] as $date)
                            @php
                                $isCurrnetMonth = $date['date']->format('m') == $calendar['query']->format('m');
                                $isTextRed = $date['dayoff'];
                                $isToday = $date['date']->format('Y-m-d') == Illuminate\Support\Carbon::now()->format('Y-m-d');
                            @endphp
                            <div @class([
                                'calendar-cell',
                                'calendar-date',
                                'fw-bold',
                                'fs-5',
                                'text-danger' => $isTextRed,
                                'grayscale' => !$isCurrnetMonth,
                                'today' => $isToday,
                            ]) data-date="{{ $date['date']->format('Y-m-d') }}">
                                <div class="row w-100">
                                    <div class="col-auto">{{ $date['date']->format('d') }}</div>
                                    <div @class([
                                        'col',
                                        'p-0',
                                        'text-nowrap',
                                        'd-flex',
                                        'justify-content-center',
                                        'align-items-center',
                                        'calendar-annotation',
                                        'text-danger' => $isTextRed,
                                    ])>
                                        {{ $date['annotation'] }}
                                    </div>
                                </div>
                                <div class="tag-wrapper">
                                    @foreach ($date['events'] as $event)
                                        <div class="border border-1 rounded event-tag my-1 mx-2 ">
                                            <div class="d-flex">
                                                <div class="col-auto p-0 mx-1 fw-bold text-dark">
                                                    {{ $event['member']['name'] }}</div>
                                                <div class="col-auto p-0"><span
                                                        class="badge bg-warning text-dark">{{ $event['type'] }}</span>
                                                </div>
                                            </div>
                                            <div class="col p-0 mx-1">{{ $event['description'] }}</div>
                                            @if ($canReview && !$event['approval'])
                                                <div class="text-danger fw-bold fs-xs mx-1">wait for reviewing</div>
                                            @endif
                                            <div class="tag-tool my-1">
                                                <span title="information" class="material-symbols-outlined mx-1 btn-detail"
                                                    data-id="{{ $event['id'] }}" data-start="{{ $event['start'] }}"
                                                    data-end="{{ $event['end'] }}" data-type="{{ $event['type']->key }}"
                                                    data-approval="{{ $event['approval'] }}"
                                                    data-hours="{{ $event['hours'] }}"
                                                    data-member="{{ $event['member']['id'] }}"
                                                    data-description="{{ $event['description'] }}">
                                                    info
                                                </span>
                                                <form id="form-delete-{{ $event['id'] }}"
                                                    action="/leave/{{ $event['id'] }}" method="POST"
                                                    class="d-flex align-items-center">
                                                    @method('DELETE')
                                                    @csrf
                                                    @php
                                                        $isAuthor = $id == $event['member']['id'];
                                                    @endphp
                                                    <span title="delete" @class([
                                                        'material-symbols-outlined',
                                                        'mx-1',
                                                        'btn-delete',
                                                        'd-none' => !$isAuthor,
                                                    ])
                                                        data-id="{{ $event['id'] }}">
                                                        delete
                                                    </span>
                                                </form>
                                                @if ($canReview && !$event['approval'])
                                                    <form id="form-approve-{{ $event['id'] }}"
                                                        action="/leave/approve/{{ $event['id'] }}" method="POST"
                                                        class="d-flex align-items-center">
                                                        @method('PATCH')
                                                        @csrf
                                                        <span title="approve"
                                                            class="material-symbols-outlined mx-1 btn-approve"
                                                            data-id="{{ $event['id'] }}">
                                                            task_alt
                                                        </span>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @php
                                    $now = Illuminate\Support\Carbon::now();
                                    $canAdd = $now->diffInDays($date['date'], false) >= 0;
                                    $canAdd = $date['dayoff'] ? false : $canAdd;
                                @endphp
                                <span @class([
                                    'material-symbols-outlined',
                                    'calendar-add',
                                    'fs-3',
                                    'd-none' => !$canAdd,
                                ]) data-date="{{ $date['date']->format('Y-m-d') }}">
                                    add_circle
                                </span>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- form Modal -->
    <div class="modal fade" id="form-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true" data-bs-target="#form-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/leave" method="POST" id="form-data">
                    @csrf
                    <input type="hidden" name="_method" id="method" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">New event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <select class="form-control" name="name" id="name" disabled readonly>
                                <option value="">{{ $name }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label"><span class="text-danger required">*</span>Type</label>
                            <select class="form-control need-calc" name="type" id="type" value="">
                                <option value="" disabled selected>選擇假別</option>
                                <option value="annual">特休</option>
                                <option value="comp">補休</option>
                                <option value="personal">事假</option>
                                <option value="offical">公假</option>
                                <option value="marriage">婚假</option>
                                <option value="funeral">喪假</option>
                                <option value="menstrul">生理假</option>
                                <option value="maternity">產假</option>
                                <option value="paternity">陪產假</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start" class="form-label"><span
                                    class="text-danger required">*</span>Start</label>
                            <div class="d-flex align-items-center">
                                <span id="start-from" class="text-nowrap mx-2"></span>
                                <input class="form-control need-calc" type="date" name="start-date"
                                    value="{{ old('start-date') }}" id="start-date">
                                <select class="form-control ms-2 need-calc" name="start-time" id="start-time"
                                    value="">
                                    <option value="09:00:00" selected>09:00</option>
                                    <option value="14:00:00">14:00</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="end" class="form-label"><span
                                    class="text-danger required">*</span>End</label>
                            <div class="d-flex align-items-center">
                                <input class="form-control need-calc" type="date" name="end-date"
                                    value="{{ old('end-date') }}" id="end-date">
                                <select class="form-control ms-2 need-calc" name="end-time" id="end-time"
                                    value="">
                                    <option value="13:00:00">13:00</option>
                                    <option value="18:00:00">18:00</option>
                                </select>
                            </div>

                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input class="form-control need-calc" type="text" name="description" id="description"
                                value="{{ old('description') }}">
                        </div>
                        <div id="usage-block" class="row">
                            <div class="col">
                                <label for="usage" class="form-label">usage</label>
                                <div class="d-flex align-items-center">
                                    <input id="usage" type="text" name="usage" class="form-control"
                                        value="" disabled>
                                    <span id="time-unit" class="mx-2">days</span>
                                </div>
                            </div>
                            <div class="col">
                                <label for="approve" class="form-label">approval</label>
                                <div class="d-flex align-items-center">
                                    <input id="approve" type="text" name="approve" class="form-control"
                                        value="" disabled>
                                </div>
                            </div>
                        </div>
                        <div id="total" class="text-danger text-white mt-1">you will use XX days for XX</div>
                        <div class="col d-flex justify-content-between">
                            <div>
                                <div class="col text-danger" id="error-msg">
                                    @if ($errors->any())
                                        <ul class="list-unstyled border border-2 border-danger rounded p-1 my-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-edit" class="btn btn-primary">edit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">close</button>
                        <div>
                            <button id="btn-submit" type="submit" class="btn btn-primary mx-1" disabled>submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- form Modal -->

    <div id="data" class="d-none" data-holidays="{{ json_encode($calendar['holidays']) }}"></div>

@endsection
@section('script')
    <script>
        $(function() {

            modalMsg('modal', 'modal-body', window.location.href);
            modalMsg('form-modal', 'error-msg', '');
            let errorMsg = $("#error-msg").text().trim();
            if (errorMsg) {
                toEditMode();
                $("#modal-title").text('Event detail');
                $("#start-date").show();
                $("#start-from").hide();
            }

            $(".calendar-add").on("click", function() {
                const date = $(this).data('date');
                $("#start-date").val(date);
                $("#end-date").val(date);
                const dateReform = date.replaceAll('-', '/');
                $("#start-from").text(dateReform);
                $("#total").addClass('text-white');

                $("#modal-title").text('New event');

                $("#form-data").attr('action', '/leave');
                $("#method").val('POST');
                $("#error-msg").empty();

                toEditMode();
                resetValue();

                const modal = new bootstrap.Modal(document.getElementById('form-modal'));
                modal.show();
            })

            $(".need-calc").on("change", function() {
                let leaveCount = 0;
                const type = $("#type").val();

                const startDate = $("#start-date").val().replaceAll('/', '-');
                const endDate = $("#end-date").val();
                const startTime = $("#start-time").val();
                const endTime = $("#end-time").val();

                const startStamp = moment(startDate + ' ' + startTime, "YYYY-MM-DD hh:mm:ss");
                const endStamp = moment(endDate + ' ' + endTime, "YYYY-MM-DD hh:mm:ss");

                $("#btn-submit").prop('disabled', true);

                if (startStamp.diff(endStamp) >= 0) {
                    $("#total").text('end time must greater than start time').removeClass('text-white');
                    return;
                }

                if (!type) {
                    $("#total").text('please select type').removeClass('text-white');
                    return;
                }

                let dayoff = $("#data").data('holidays');
                dayoff = Object.fromEntries(Object.entries(dayoff).filter(([key, value]) => {
                    return value.dayoff == 1;
                }));

                let days = endStamp.diff(startStamp, 'days');

                let range = [];

                for (let i = 0; i <= days; i++) {
                    let clone = startStamp.clone();
                    let date = clone.add(i, 'days').format('YYYY-MM-DD');
                    if (!(date in dayoff)) {
                        range.push(date);
                    }
                }

                let lastIndex = range.length - 1;

                for (let i = 0; i <= lastIndex; i++) {

                    let date = range[i];

                    const afternoon = moment(date + ' 13:00:00', "YYYY-MM-DD hh:mm:ss");

                    if (i == 0) {

                        if (lastIndex == 0) {
                            const isEndAfterNoon = (afternoon.diff(endStamp) < 0);
                            leaveCount += isEndAfterNoon ? 1 : 0.5;
                            continue;
                        }

                        const isStartFromMorning = (afternoon.diff(startStamp) > 0);
                        leaveCount += isStartFromMorning ? 1 : 0.5;
                        continue;
                    }

                    if (i == lastIndex) {
                        const isEndAfterNoon = (afternoon.diff(endStamp) < 0);
                        if (startTime == endTime && startTime == '09:00:00') {
                            leaveCount += 0;
                            continue;
                        }

                        leaveCount += isEndAfterNoon ? 1 : 0.5;
                        continue;
                    }

                    leaveCount += 1;

                }

                let unit = (leaveCount > 1) ? 'days' : 'day';
                let msg = `you will use <b>${leaveCount} ${unit}</b> for <b>${type} leave</b>`;
                $("#total").html(msg).removeClass('text-white');
                $("#btn-submit").prop('disabled', false);
            })

            $(".btn-delete").on("click", function() {
                const id = $(this).data('id');

                const checkBtn =
                    `<button id="btn-delete-check" type="button" class="btn btn-danger" data-id="${id}">Check</button>`;
                $("#modal .modal-footer #btn-delete-check").remove();
                $("#modal .modal-footer #btn-approve-check").remove();
                $("#modal .modal-footer").prepend(checkBtn);

                $("#modal-body").empty().text('going to delete this event?');
                modalMsg('modal', 'modal-body', '');
            });

            $(".btn-approve").on("click", function() {
                const id = $(this).data('id');

                const checkBtn =
                    `<button id="btn-approve-check" type="button" class="btn btn-danger" data-id="${id}">Check</button>`;
                $("#modal .modal-footer #btn-delete-check").remove();
                $("#modal .modal-footer #btn-approve-check").remove();
                $("#modal .modal-footer").prepend(checkBtn);

                $("#modal-body").empty().text('going to approve this leave?');
                modalMsg('modal', 'modal-body', '');
            });

            $("#modal").on("click", "#btn-delete-check", function() {
                const id = $(this).data('id');
                $(`#form-delete-${id}`).submit();
            });

            $("#modal").on("click", "#btn-approve-check", function() {
                const id = $(this).data('id');
                $(`#form-approve-${id}`).submit();
            });

            $(".btn-detail").on("click", function() {
                const data = $(this).data();
                $("#form-data").attr('action', `/leave/${data.id}`);
                $("#method").val('PATCH');

                $("#modal-title").text('Event detail');
                $("#error-msg").empty();

                const eventOwner = data.member;
                const currentUser = $("#user").data('id');
                (currentUser == eventOwner) ? $("#btn-edit").removeClass('d-none') : $("#btn-edit").addClass('d-none');

                filledWithData(data);
                toReadOnlyMode();

                const modal = new bootstrap.Modal(document.getElementById('form-modal'));
                modal.show();
            });

            $("#btn-edit").on("click", function() {
                toEditMode();
                $("#start-date").show();
                $("#start-from").hide();
                $("#usage-block").addClass('d-flex');
            });
        });

        function toEditMode() {
            $("#total").addClass('text-white');
            $("#start-from").show();
            $("#start-date").hide();
            $("#btn-edit").hide();
            $("#btn-submit").show();
            $("#usage-block").removeClass('d-flex');
            $(".required").show();
            $("#form-data input").prop('readonly', false);
            $("#form-data select").prop('disabled', false);
        }

        function toReadOnlyMode() {
            $("#total").addClass('text-white');
            $("#start-from").hide();
            $("#start-date").show();
            $("#btn-edit").show();
            $("#btn-submit").hide();
            $("#usage-block").addClass('d-flex');
            $(".required").hide();
            $("#form-data input").prop('readonly', true);
            $("#form-data select").prop('disabled', true);
        }

        function filledWithData(data) {
            const startDate = data.start.split(' ')[0];
            const endDate = data.end.split(' ')[0];
            const startTime = data.start.split(' ')[1];
            const endTime = data.end.split(' ')[1];
            $("#start-date").val(startDate);
            $("#end-date").val(endDate);
            $("#start-time").val(startTime);
            $("#end-time").val(endTime);

            const type = data.type;
            $("#type").val(type);

            const description = data.description;
            $("#description").val(description);

            const days = (data.hours) / 24;
            $("#usage").val(days);

            const approve = (data.approval) ? 'approved' : 'reviewing';
            $("#approve").val(approve);
        }

        function resetValue() {
            $("#start-time").val('09:00:00');
            $("#end-time").val('13:00:00');
            $("#type").val('');
            $("#description").val('');
        }
    </script>
@endsection
