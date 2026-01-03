@extends('dashboard.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

    <div class="text-lg">
        Welcome, {{ auth()->user()->name }}
    </div>

@endsection
