@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold">Dashboard</h1>

    <p class="mt-2">Selamat datang {{ auth()->user()->name }}</p>

    @role('admin')
        <div class="mt-4 text-green-600">Role: Admin</div>
    @endrole

    @role('kasir')
        <div class="mt-4 text-blue-600">Role: Kasir</div>
    @endrole
@endsection
