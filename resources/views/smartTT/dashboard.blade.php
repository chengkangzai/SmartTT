@extends('smartTT.layout.master')
@section('title')
    Dashboard - {{config('app.name')}}
@endsection

@section('header')
    Test header
@endsection

@section('content')
    <section class="content-header">
        <h1>
            @yield('header','Page Header')
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <!-- Content Goes Here -->

    </section>
@endsection
