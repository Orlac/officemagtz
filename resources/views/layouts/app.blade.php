<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Скидки')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">Скидки</span>
        @auth
            <form method="POST" action="{{ route('logout') }}" class="d-flex align-items-center gap-3 m-0">
                @csrf
                <span class="text-white-50">{{ auth()->user()->login }}</span>
                <button class="btn btn-outline-light btn-sm">Выйти</button>
            </form>
        @endauth
    </div>
</nav>
<main class="container" style="max-width: 640px">
    @yield('content')
</main>
</body>
</html>
