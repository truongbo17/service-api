@extends(backpack_view('blank'))

@php
    $breadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      'Log System' => false,
    ];
@endphp

@section('header')
    <section class="container-fluid">
        <h2>Log System</h2>
    </section>
@endsection

@section('content')
    <style>
        iframe {
            overflow: scroll;
            height: 100vh;
            border: 1px solid #f1f4f800;
        }
    </style>

    <iframe src="{{route('blv.index')}}" class="w-100"></iframe>
@endsection
