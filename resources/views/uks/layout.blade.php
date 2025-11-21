@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6">UKS - @yield('uks-title')</h1>
        @yield('uks-content')
    </div>
@endsection
