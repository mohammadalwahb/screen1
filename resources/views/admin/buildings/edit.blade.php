@extends('layouts.admin')

@section('content')
    <h1>Edit Building</h1>
    <form action="{{ route('admin.buildings.update', $building) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.buildings._form', ['building' => $building])
    </form>
@endsection
