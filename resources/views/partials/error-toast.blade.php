@if ($errors->any())
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1040">
        <div id="liveToast" class="toast text-danger" role="alert" aria-live="assertive" aria-atomic="true"
            data-coreui-autohide="false">
            <div class="toast-header">
                <strong class="me-auto">{{ config('app.name') }}</strong>
                <button type="button" class="btn-close" data-coreui-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
