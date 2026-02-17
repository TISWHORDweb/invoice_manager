<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: system-ui, sans-serif; margin: 0; padding: 1rem; max-width: 960px; margin-left: auto; margin-right: auto; }
        a { color: #2563eb; }
        nav { margin-bottom: 1.5rem; }
        .alert { padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.5rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
        input, select, textarea { padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; }
        button, .btn { padding: 0.5rem 1rem; cursor: pointer; border-radius: 4px; border: 1px solid #d1d5db; background: #f9fafb; text-decoration: none; display: inline-block; font-size: 1rem; }
        .btn-primary { background: #2563eb; color: white; border-color: #2563eb; }
        .btn-danger { background: #dc2626; color: white; border-color: #dc2626; }
        .filter-form { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; align-items: flex-end; }
        .filter-form label { display: block; margin-bottom: 0.25rem; font-size: 0.875rem; }
        .filter-form input, .filter-form select { margin-right: 0.5rem; }
    </style>
    @stack('styles')
</head>
<body>
    <nav>
        <a href="{{ route('invoices.index') }}">Invoices</a> |
        <a href="{{ route('invoices.create') }}">New invoice</a>
    </nav>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')

    @stack('scripts')
</body>
</html>
