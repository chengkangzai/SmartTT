@if (session('status'))
    <div role="alert" class="alert alert-success py-2 ">
        <ul class="py-0 m-0">
            <li>{!! session('status') !!}</li>
        </ul>
    </div>
@endif
