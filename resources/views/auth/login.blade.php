<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f7fb; display: grid; place-items: center; min-height: 100vh; }
        .card { width: 360px; background: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 8px; margin-top: 6px; border: 1px solid #d1d5db; border-radius: 6px; }
        button { margin-top: 12px; width: 100%; padding: 10px; border: 0; border-radius: 6px; background: #2563eb; color: white; cursor: pointer; }
        .error { color: #b91c1c; margin-top: 8px; }
    </style>
</head>
<body>
<div class="card">
    <h2>Admin Login</h2>
    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif
    <form action="{{ route('login.store') }}" method="POST">
        @csrf
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label>
            <input type="checkbox" name="remember" value="1">
            Remember me
        </label>

        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
