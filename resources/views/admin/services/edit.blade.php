@extends('layouts.admin')

@section('content')
    <h1>Edit Service</h1>
    <form action="{{ route('admin.services.update', $service) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.services._form', ['service' => $service, 'buildings' => $buildings])
    </form>
@endsection
