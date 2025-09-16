<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    {{-- Bootstrap CSS (CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Custom CSS (optional) --}}
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        <div class="sidebar">
            <h4 class="text-center text-white py-3">Admin</h4>
            <a href="{{ route('admin.projects.index') }}">📂 Projects</a>
            <a href="#">👤 Users</a>
            <a href="#">⚙️ Settings</a>
            <a href="#">📊 Reports</a>
        </div>

        {{-- Main Content --}}
        <div class="flex-grow-1">
            {{-- Navbar --}}
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('projects.index') }}">Admin Panel</a>
                    <div class="d-flex">
                        <span class="navbar-text me-3">
                            {{ Auth::user()->name ?? 'Guest' }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">Logout</button>
                        </form>
                    </div>
                </div>
            </nav>

            <div class="content">
                {{-- Alerts --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Page Content --}}
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Bootstrap JS (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
