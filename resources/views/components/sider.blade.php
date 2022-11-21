<input class="d-none" type="checkbox" name="btn-toggle" id="btn-toggle">
<div class="toggle-icon rounded d-flex align-items-center vh-100">
    <label for="btn-toggle" class=" d-flex align-items-center">
        <span class="material-symbols-outlined toggle-left">
            arrow_left
        </span>
        <span class="material-symbols-outlined toggle-right">
            arrow_right
        </span>
    </label>
</div>

<div class="position-fixed border border-1 rounded h-100 d-flex flex-column py-5 px-2 text-dark sidebar-wrapper">
    <div class="border-bottom border-1 my-3">
        <a href="/" class="text-decoration-none text-dark d-flex align-items-center p-3">
            <span class="material-symbols-outlined fs-3">
                apps
            </span>
        </a>
    </div>
    <div class="position-relative my-2">
        <a href="/" class="text-decoration-none text-dark d-flex align-items-center p-3 rounded sidebar-btn">
            <span class="material-symbols-outlined fs-3">
                punch_clock
            </span>
        </a>
        <div class="position-absolute text-nowrap sidebar-label rounded p-2">punch card</div>
    </div>
    <div class="position-relative my-2">
        <a href="/attend" class="text-decoration-none text-dark d-flex align-items-center p-3 rounded sidebar-btn">
            <span class="material-symbols-outlined fs-3">
                event_note
            </span>
        </a>
        <div class="position-absolute text-nowrap sidebar-label rounded p-2">attendace</div>
    </div>
    <div class="position-relative my-2">
        <a href="/leave" class="text-decoration-none text-dark d-flex align-items-center p-3 rounded sidebar-btn">
            <span class="material-symbols-outlined fs-3">
                event_busy
            </span>
        </a>
        <div class="position-absolute text-nowrap sidebar-label rounded p-2">leaves</div>
    </div>
</div>
