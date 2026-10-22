<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Admin Panel') - QITSPREM</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        :root {
            --bg-color: #FCF1D0; 
            --header-bg: #790D16;
            --text-main: #222222;
            --text-muted: #888888;
            --brand-color: #ffffff;
            --card-border: #111;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            
            --badge-green-bg: #e9f5ec; --badge-green-text: #49a85d;
            --badge-red-bg: #fee2e2;    --badge-red-text: #dc2626;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-main); -webkit-font-smoothing: antialiased; }

        .app-layout { display: flex; min-height: 100vh; width: 100%; overflow-x: hidden; position: relative; }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px; background-color: #790D16; border-right: 1px solid var(--card-border);
            display: flex; flex-direction: column; padding: 24px 16px;
            transition: left 0.3s ease; flex-shrink: 0; height: 100vh; position: sticky; top: 0; z-index: 1000;
        }

        .sidebar-header { display: flex; align-items: center; gap: 16px; margin-bottom: 40px; padding-left: 8px; }
        
        .close-btn { 
            display: none; 
            background: none; border: none; font-size: 20px; font-weight: bold; color: #fff; cursor: pointer; transition: 0.2s; 
        }
        
        .logo-container { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .logo-icon { width: 28px; height: 28px; background-color: var(--brand-color); border-radius: 8px; display: flex; justify-content: center; align-items: center; color: #790D16; font-size: 16px; font-weight: bold; flex-shrink: 0; }
        .logo-text { font-size: 18px; font-weight: 800; letter-spacing: 0.5px; color: #fff; white-space: nowrap; }

        .sidebar-section-title { font-size: 11px; font-weight: 600; color: #fff; letter-spacing: 0.5px; margin: 20px 0 8px 12px; }
        .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 8px; text-decoration: none; color: #fff; font-size: 14px; font-weight: 500; transition: 0.2s; cursor: pointer; width: 100%; }
        .sidebar-link:hover { background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-link.active { background-color: #FCF1D0; color: #790D16; font-weight: 700; }
        .sidebar-icon { font-size: 16px; }

        /* --- AREA KANAN --- */
        .main-wrapper { flex: 1; display: flex; flex-direction: column; min-width: 0; height: 100vh; overflow-y: auto; }
        .top-header { background-color: var(--header-bg); padding: 16px 24px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        
        /* Bagian Kiri Header (Hamburger + Logo untuk HP) */
        .header-left { display: flex; align-items: center; gap: 12px; }
        .menu-toggle { background: none; border: none; font-size: 22px; cursor: pointer; color: #fff; display: none; }

        /* Sembunyikan Logo di Header saat Desktop, Tampilkan saat Mode HP */
        .header-logo { display: none; }

        .profile-menu-container { position: relative; display: flex; align-items: center; }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; background-color: #ccc; overflow: hidden; border: 2px solid white; cursor: pointer; padding: 0; outline: none; }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .dropdown-menu { position: absolute; top: 48px; right: 0; background-color: #1f2937; border-radius: 6px; width: 160px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); display: none; flex-direction: column; overflow: hidden; z-index: 999; }
        .dropdown-menu.show { display: flex; }
        .dropdown-item { padding: 12px 16px; color: #e5e7eb; font-size: 13px; text-decoration: none; text-align: left; background: none; border: none; width: 100%; cursor: pointer; }
        .dropdown-item:hover { background-color: #374151; }

        .container { max-width: 1000px; margin: 0 auto; padding: 32px; width: 100%; }
        
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
        .alert-success { background-color: var(--badge-green-bg); color: var(--badge-green-text); border: 1px solid #bbf7d0; }
        .alert-danger { background-color: var(--badge-red-bg); color: var(--badge-red-text); border: 1px solid #fecaca; }
        .admin-section { background-color: #ffffff; border: 1px solid var(--card-border); border-radius: 16px; padding: 28px; margin-bottom: 30px; box-shadow: var(--card-shadow); }
        .section-title { font-size: 18px; font-weight: 700; margin-bottom: 24px; }
        .btn { padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; }
        .btn-primary { background-color: #790D16; color: white; }
        .btn-danger { background-color: var(--badge-red-text); color: white; padding: 6px 12px; font-size: 12px; }

        /* --- Overlay Latar Gelap --- */
        .sidebar-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); z-index: 999;
            display: none; opacity: 0; transition: opacity 0.3s ease;
        }

        /* --- PENGATURAN LAYAR HP (MOBILE) --- */
        @media (max-width: 768px) {
            .sidebar { 
                position: fixed; 
                left: -260px; 
            }
            .sidebar.active {
                left: 0; 
            }
            .main-wrapper {
                width: 100%;
            }
            .menu-toggle { display: block; } /* Munculkan hamburger */
            .close-btn { display: block; } /* Munculkan tanda X di sidebar */
            .header-logo { display: flex; } /* Munculkan logo dan teks QITSPREM di header */
            
            .sidebar-overlay.active { display: block; opacity: 1; }
            .container { padding: 16px; }
            .top-header { padding: 16px 16px; }
        }
    </style>
    @yield('styles')
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="app-layout">
        <aside class="sidebar" id="sidebarMenu">
            <div class="sidebar-header">
                <button class="close-btn" id="closeSidebar">✕</button>
                <a href="{{ route('home') }}" class="logo-container">
                    <div class="logo-icon">❖</div>
                    <div class="logo-text">QITSPREM</div>
                </a>
            </div>

            <div class="sidebar-section-title">MENU UTAMA</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="sidebar-icon">🏠</span> Dashboard
            </a>
            <a href="{{ route('admin.products') }}" class="sidebar-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                <span class="sidebar-icon">📦</span> Kelola Produk
            </a>
            <a href="{{ route('admin.orders') }}" class="sidebar-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <span class="sidebar-icon">🧾</span> Kelola Pesanan
            </a>
            <a href="{{ route('admin.customers') }}" class="sidebar-link {{ request()->routeIs('admin.customers') ? 'active' : '' }}">
                <span class="sidebar-icon">👥</span> Kelola Customer
            </a>

            <div class="sidebar-section-title">AKUN</div>
            <a href="{{ route('profile.edit') }}" class="sidebar-link">
                <span class="sidebar-icon">👤</span> Profil Admin
            </a>
            <form method="POST" action="{{ route('logout') }}" style="width: 100%; margin: 0;">
                @csrf
                <button type="submit" class="sidebar-link" style="background: none; border: none; text-align: left;">
                    <span class="sidebar-icon">🚪</span> Keluar
                </button>
            </form>
        </aside>

        <div class="main-wrapper">
            <header class="top-header">
                <!-- Bagian Kiri: Tombol Hamburger + Logo & Tulisan QITSPREM (Muncul di HP) -->
                <div class="header-left">
                    <button class="menu-toggle" id="openSidebar">☰</button>
                    <a href="{{ route('admin.dashboard') }}" class="logo-container header-logo">
                        <div class="logo-icon">❖</div>
                        <div class="logo-text">QITSPREM</div>
                    </a>
                </div>
                
                <!-- Bagian Kanan: Profil Admin -->
                <div class="profile-menu-container">
                    <button class="user-avatar" id="profile-btn">
                        <img src="https://ui-avatars.com/api/?name=Admin+Profile&background=333&color=fff" alt="Avatar">
                    </button>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">Profile</a>
                        <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                            @csrf
                            <button type="submit" class="dropdown-item">Log Out</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="container">
                @if(session('success'))
                    <div class="alert alert-success">✅ {{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">❌ Terdapat kesalahan input data. Periksa kembali form Anda.</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileBtn = document.getElementById('profile-btn');
            const dropdownMenu = document.getElementById('dropdown-menu');
            if(profileBtn && dropdownMenu) {
                profileBtn.addEventListener('click', (e) => { 
                    e.stopPropagation(); 
                    dropdownMenu.classList.toggle('show'); 
                });
                window.addEventListener('click', () => {
                    dropdownMenu.classList.remove('show');
                });
            }

            const sidebarMenu = document.getElementById('sidebarMenu');
            const openSidebarBtn = document.getElementById('openSidebar');
            const closeSidebarBtn = document.getElementById('closeSidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                sidebarMenu.classList.add('active');
                sidebarOverlay.classList.add('active');
            }

            function closeSidebar() {
                sidebarMenu.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            }

            if(openSidebarBtn) openSidebarBtn.addEventListener('click', openSidebar);
            if(closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
            if(sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);
        });
    </script>
    
    @yield('scripts')
</body>
</html>