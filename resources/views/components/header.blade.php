@if (Auth::check())
    <div class="container my-2">
        <div class="row">
            <div class="col d-flex justify-content-end align-items-end">
                <div class="d-flex align-items-end">
                    <span class="mx-1" id="user" data-id="{{ Auth::user()->id }}">hello, <a
                            href="/user/{{ Auth::user()->id }}">{{ Auth::user()->name }}</a></span>
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm" id="btn-logout">log out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
