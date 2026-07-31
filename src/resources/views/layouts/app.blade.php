<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POS SPA</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

<div x-data="{ sidebarOpen: false }" class="relative">

    {{-- NAVBAR --}}
    @persist('navbar')
    @include('layouts.navbar')
    @endpersist

    {{-- SIDEBAR --}}
    @persist('sidebar')
    @include('layouts.sidebar')
    @endpersist

    {{-- CONTENT --}}
    <main class="p-6">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

</div>

@livewireScriptConfig
{{-- @livewireScripts --}}
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
</body>
</html>
