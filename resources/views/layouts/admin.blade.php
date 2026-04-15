<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f5f7fb; color: #17202a; }
        header { background: #0f172a; color: white; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; }
        main { max-width: 1100px; margin: 20px auto; background: white; border-radius: 10px; padding: 20px; }
        a { color: #2563eb; text-decoration: none; }
        nav a { color: white; margin-right: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 10px; text-align: left; }
        input, textarea, select { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 12px; border: 1px solid #d1d5db; border-radius: 6px; }
        button { background: #2563eb; color: white; border: 0; border-radius: 6px; padding: 8px 12px; cursor: pointer; }
        .secondary { background: #4b5563; }
        .danger { background: #dc2626; }
        .status { background: #dcfce7; color: #166534; padding: 10px; border-radius: 6px; margin-bottom: 12px; }
        .error { color: #b91c1c; margin: 0 0 10px; }
        .actions { display: flex; gap: 8px; align-items: center; }
        .inline { display: inline; }
    </style>
</head>
<body>
<header>
    <div>
        <nav>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.buildings.index') }}">Buildings</a>
            <a href="{{ route('admin.services.index') }}">Services</a>
            <a href="{{ route('admin.password.edit') }}">Change Password</a>
        </nav>
    </div>
    @auth
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="secondary">Logout</button>
        </form>
    @endauth
</header>
<main>
    @include('admin.partials.flash')
    @yield('content')
</main>
</body>
</html>
