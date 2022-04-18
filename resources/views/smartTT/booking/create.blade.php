@extends('layouts.app')
@section('title')
    Create Booking - {{ config('app.name') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">{{ __('Bookings') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ __('Create Booking') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('bookings.store') }}" method="POST" id="createForm">
                @include('partials.error-alert')
                @csrf
                <div class="mb-3">
                    <label for="package_id" class="form-label">{{ __('Packages') }}</label>
                    <select name="package_id" class="form-control select2 " id="package_id" required>
                        @foreach ($packages as $package)
                            <option value="{{ $package->id }}" data-price="{{ $package->price }}"
                                @checked(old('package_id') == $package->id)>
                                {{ $package->tour->name }} ({{ $package->depart_time }}) (${{ $package->fee }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="adult" class="form-label">{{ __('Adult') }}</label>
                        <input type="number" name="adult" class="form-control" id="adult" min="0"
                            value="{{ old('adult', 0) }}" step="1" placeholder="{{ __('Enter Total adult Number') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="child" class="form-label">{{ __('Child') }}</label>
                        <small class="text-sm">
                            {{ __('Child is defined as children that is smaller than 12 years old') }}
                        </small>
                        <input type="number" name="child" class="form-control" id="child" min="0"
                            value="{{ old('child', 0) }}" step="1" placeholder="{{ __('Enter Total Child Number') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="user_id" class="form-label">{{ __('Customer') }}</label>
                    <select name="user_id" class="form-control select2 " id="user_id" required>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="discount" class="form-label">{{ __('Discount') }}</label>
                    <input type="number" name="discount" class="form-control" id="discount" min="0"
                        value="{{ old('discount', 0) }}" step="1" placeholder="{{ __('Please enter Discount') }}" />
                </div>
                <div class="form-group">
                    <label>{{ __('Total Price :') }} <span id="fee">RM 0</span></label>
                </div>
            </form>
            <div class="card-footer">
                <input form="createForm" type="submit" class="btn btn-primary" value="{{ __('Submit') }}">
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $.ajax({
            type: "POST",
            url: "{{ route('select2.user.getCustomer') }}",
            success: function(response) {
                $("#user_id").select2({
                    data: response
                });
            }
        });
        const packageId = $('#package_id');
        const child = $('#child');
        const adult = $('#adult');
        const discount = $('#discount');

        function updatePrice() {
            const adultVal = adult.val();
            const package = packageId.val();
            if (adultVal === 0 || package === null) {
                return;
            }
            $.ajax({
                type: "POST",
                url: "{{ route('bookings.calculatePrice') }}",
                data: {
                    package_id: packageId.val(),
                    child: child.val(),
                    adult: adult.val(),
                    discount: discount.val()
                },
                success: function(response) {
                    $('#fee').text('RM ' + response)
                }
            })
        }

        discount.on('change', updatePrice);
        child.on('change', updatePrice);
        adult.on('change', updatePrice);
        packageId.on('change', updatePrice)
    </script>
@endsection
