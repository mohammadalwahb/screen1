@extends('layouts.admin')

@section('content')
    <h1>Create Service</h1>
    <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.services._form', ['buildings' => $buildings])
    </form>
@endsection
