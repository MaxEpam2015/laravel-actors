<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Actor Submitter' }}</title>

    @vite('resources/js/app.ts')
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
<div class="container mx-auto max-w-3xl px-4 py-8">
    <header class="mb-6">
        <h1 class="text-2xl font-bold">Actor Submitter</h1>
    </header>

    @if ($errors->any())
        <div class="mb-6 rounded border border-red-300 bg-red-50 p-4 text-red-800">
            <ul class="list-disc pl-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>
