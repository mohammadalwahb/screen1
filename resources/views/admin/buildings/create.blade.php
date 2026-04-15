@extends('layouts.admin')

@section('content')
    <h1>Create Building</h1>
    <form action="{{ route('admin.buildings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.buildings._form')
    </form>
@endsection
