<!doctype html>
<html lang="fa" dir="rtl" class="m-0 p-0 h-full w-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite('resources/css/app.css')
    @yield('head')
</head>
<body class="m-0 p-4 h-full  bg-gray-100">
    <div class="flex justify-center items-center flex-col" style="min-height: 100%">
        @yield('content')
    </div>
</body>
</html>
