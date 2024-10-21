<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf" content="{{ csrf_token() }}">
    <meta name="prompt" content="{{ config('web-shell.terminal.prompt') }}">
    <meta name="directory" content="{{ route(config('web-shell.route.as') . 'directory.show') }}">
    <meta name="command" content="{{ route(config('web-shell.route.as') . 'command') }}">
    <link rel="stylesheet" href="{{ route(config('web-shell.route.as') . 'asset.show', 'style.css') }}">
    <title>@yield('title', 'Web Shell')</title>
    @stack('styles')
</head>
<style>
    @font-face {
        font-family: 'Cascadia Code Regular';
        font-style: normal;
        font-weight: normal;
        src: local('Cascadia Code Regular'), url("{{ route(config('web-shell.route.as') . 'asset.show', 'cascadia.woff') }}") format('woff');
    }
</style>

<body>

    <main id='app'>
        @yield('content')
    </main>

    <script src="{{ route(config('web-shell.route.as') . 'asset.show', 'main.js') }}"></script>
    @stack('scripts')

</body>

</html>
