@if (session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1040">
        <div id="liveToast" class="toast bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">{{config('app.name')}}</strong>
                <button type="button" class="btn-close" data-coreui-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif
