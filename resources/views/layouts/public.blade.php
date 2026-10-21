<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Premium')</title>
    <!-- Anda bisa menaruh link CSS kustom Anda di sini nanti -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            color: #333;
        }
        /* Navbar Publik Sederhana */
        .public-navbar {
            background-color: #790D16;
            padding: 16px 24px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .public-navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-left: 16px;
        }
    </style>
</head>
<body>

    <!-- Navbar Khusus Publik -->
    <nav class="public-navbar">
        <div style="font-size: 20px; font-weight: 900;">🛒 Toko Premium</div>
        <div>
            <a href="{{ route('home') }}">Beranda</a>
            @auth
                <a href="{{ route('dashboard') }}">Dashboard Saya</a>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </nav>

    <!-- Konten Halaman -->
    <main style="min-height: 80vh;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer style="text-align: center; padding: 24px; background: #222; color: #aaa; margin-top: 40px;">
        &copy; {{ date('Y') }} Toko Premium. All rights reserved.
    </footer>

</body>
</html>