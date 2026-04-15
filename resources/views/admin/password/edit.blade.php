@extends('layouts.admin')

@section('content')
    <h1>Change Password</h1>

    <form action="{{ route('admin.password.update') }}" method="POST" style="max-width: 500px;">
        @csrf
        @method('PUT')

        <label for="current_password">Current Password</label>
        <input type="password" id="current_password" name="current_password" required>

        <label for="password">New Password</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirmation">Confirm New Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>

        <button type="submit">Update Password</button>
    </form>
@endsection
