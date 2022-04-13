@if ($errors->any())
    <div role="alert" class="alert alert-danger py-2 ">
        <ul class="py-0 m-0">
            @foreach ($errors->all() as $error)
                <li>
                    {{$error}}
                </li>
            @endforeach
        </ul>
    </div>
@endif
