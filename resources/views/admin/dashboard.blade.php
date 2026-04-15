@extends('layouts.admin')

@section('content')
    <h1>Admin Dashboard</h1>
    <p>Buildings: <strong>{{ $buildingsCount }}</strong></p>
    <p>Services: <strong>{{ $servicesCount }}</strong></p>
    <p>Active Services: <strong>{{ $activeServicesCount }}</strong></p>
@endsection
