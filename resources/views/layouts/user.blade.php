<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Tag Viewport Mutlak untuk Responsivitas HP -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <title>@yield('title', 'QITSPREM')</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        /* CSS GLOBAL (Akan dipakai di semua halaman) */
        :root {
            --brand-color: #ffff;
            --brand-hover: #790D16;
            --bg-body: #FCF1D0;
            --card-bg: #ffffff;
            --text-dark: #1a1a1a;
            --text-muted: #777777;
            --border-color: #eaeaea;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; word-break: break-word; }
        body { background-color: var(--bg-body); color: var(--text-dark); -webkit-font-smoothing: antialiased; min-height: 100vh; }

        /* ========================================================
           SISTEM FLEX UI & RESPONSIVE GLOBAL
           ======================================================== */
        .container-responsive {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 16px;
        }

        .table-responsive table {
            width: 100%;
            min-width: 600px;
            border-collapse: collapse;
        }

        @media (max-width: 768px) {
            /* Ubah baris jadi kolom di HP */
            .flex-desktop-row {
                flex-direction: column !important;
                gap: 16px !important;
            }
            .hide-on-mobile {
                display: none !important;
            }
            /* Optimalisasi tombol dan input untuk jempol */
            input, select, button, .btn {
                min-height: 44px;
                font-size: 14px !important;
            }
        }
        /* ======================================================== */

        /* HEADER & SIDEBAR */
        header { background-color: #790D16; padding: 16px 1.5%; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ebd5d8; }
        .header-left { display: flex; align-items: center; gap: 16px; }
        .hamburger-btn { background: none; border: none; color: white; font-size: 22px; cursor: pointer; transition: 0.2s; }
        .hamburger-btn:hover { color: #f0f0f0; }
        
        .brand { display: flex; align-items: center; gap: 8px; font-size: 20px; font-weight: 800; color: white; text-decoration: none; }
        .brand-icon { width: 28px; height: 28px; background-color: var(--brand-color); color: var(--brand-hover); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; }

        .sidebar { position: fixed; top: 0; left: -300px; width: 280px; height: 100vh; background: white; z-index: 1000; transition: left 0.3s ease; box-shadow: 4px 0 15px rgba(0,0,0,0.1); display: flex; flex-direction: column; overflow-y: auto; }
        .sidebar.active { left: 0; }
        .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background: rgba(0,0,0,0.5); z-index: 999; opacity: 0; visibility: hidden; transition: 0.3s; }
        .sidebar-overlay.active { opacity: 1; visibility: visible; }
        
        .sidebar-header { padding: 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: white; z-index: 10; }
        .sidebar-header .brand { color: var(--brand-hover); }
        .sidebar-header .brand-icon { background-color: var(--brand-hover); color: white; }
        .close-btn { background: none; border: none; font-size: 20px; color: #555; cursor: pointer; }
        
        .sidebar-menu { padding: 16px 0; display: flex; flex-direction: column; }
        .sidebar-menu a, .sidebar-menu button { display: flex; align-items: center; gap: 16px; padding: 14px 24px; text-decoration: none; color: var(--text-dark); font-weight: 600; font-size: 15px; transition: 0.2s; background: none; border: none; width: 100%; cursor: pointer; text-align: left; }
        .sidebar-menu a:hover, .sidebar-menu button:hover { background-color: #fcf4f5; color: var(--brand-hover); }
        .sidebar-menu i { font-size: 18px; width: 20px; text-align: center; color: var(--text-muted); }
        .sidebar-menu a:hover i, .sidebar-menu button:hover i { color: var(--brand-hover); }

        /* Label Khusus di Sidebar */
        .sidebar-label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; padding: 10px 24px; margin-top: 10px; }

        /* POPUP PROFILE */
        .header-profile { position: relative; cursor: pointer; }
        .profile-btn { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: white; padding: 8px 12px; border-radius: 30px; transition: 0.2s; }
        .profile-btn:hover { background-color: rgba(255, 255, 255, 0.15); }
        .profile-btn i.fa-user-circle { font-size: 20px; }
        
        .profile-popup { position: absolute; top: calc(100% + 15px); right: 0; background: white; width: 200px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.15); border: 1px solid var(--border-color); opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease; z-index: 100; overflow: hidden; }
        .profile-popup.active { opacity: 1; visibility: visible; transform: translateY(0); }
        
        .profile-popup a, .profile-popup button { display: flex; align-items: center; gap: 12px; width: 100%; padding: 14px 16px; text-decoration: none; color: var(--text-dark); font-size: 13px; font-weight: 600; background: none; border: none; cursor: pointer; text-align: left; transition: 0.2s; }
        .profile-popup a:hover, .profile-popup button:hover { background-color: #f9f9f9; color: var(--brand-hover); }
        .profile-popup a i, .profile-popup button i { width: 16px; text-align: center; color: var(--text-muted); font-size: 14px; }
        .profile-popup a:hover i, .profile-popup button:hover i { color: var(--brand-hover); }
        
        .profile-popup hr { border: none; border-top: 1px solid var(--border-color); margin: 0; }
        .logout-text { color: #dc2626 !important; }
        .logout-text i { color: #dc2626 !important; }
        
        /* Tombol Login untuk Guest */
        .btn-login-outline { color: white; text-decoration: none; font-weight: 600; font-size: 14px; padding: 8px 18px; border: 1px solid white; border-radius: 30px; transition: 0.2s; }
        .btn-login-outline:hover { background: white; color: var(--brand-hover); }
    </style>
    
    @yield('styles')

</head>
<body>

    <!-- OVERLAY SIDEBAR -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- MENU SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-icon">❖</span>
                <span>QITSPREM</span>
            </a>
            <button class="close-btn" onclick="toggleSidebar()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        
        <div class="sidebar-menu">
            <div class="sidebar-label">Navigasi Utama</div>
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
            
            @auth
                <!-- Menu Jika Sudah Login -->
                <a href="{{ route('dashboard') }}"><i class="fa-solid fa-box"></i> Pesanan Saya</a>
                <a href="{{ route('profile.edit') }}"><i class="fa-solid fa-user"></i> Profile</a>
                
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge"></i> Dasbor Admin</a>
                @endif
                
                <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 10px 24px;">
                
                <!-- MENU PUSAT BANTUAN DIARAHKAN KE HALAMAN WEB BANTUAN -->
                <div class="sidebar-label">Layanan Pelanggan</div>
                <a href="{{ route('bantuan') }}" style="background-color: #f0fdf4; border-left: 4px solid #166534; padding-left: 20px;">
                    <i class="fa-solid fa-headset" style="color: #166534;"></i> 
                    <span style="color: #166534;">Pusat Bantuan</span>
                </a>
                
                <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 10px 24px;">
                
                <form method="POST" action="{{ route('logout') }}" style="margin: 0; width: 100%;">
                    @csrf
                    <button type="submit" style="color: #dc2626;">
                        <i class="fa-solid fa-right-from-bracket" style="color: #dc2626;"></i> Keluar
                    </button>
                </form>
            @else
                <!-- Menu Jika Belum Login (Guest) -->
                <div class="sidebar-label">Layanan Pelanggan</div>
                <a href="{{ route('bantuan') }}" style="background-color: #f0fdf4; border-left: 4px solid #166534; padding-left: 20px;">
                    <i class="fa-solid fa-headset" style="color: #166534;"></i> 
                    <span style="color: #166534;">Pusat Bantuan</span>
                </a>
                
                <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 10px 24px;">
                
                <div class="sidebar-label">Akun</div>
                <a href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> Masuk</a>
                <a href="{{ route('register') }}"><i class="fa-solid fa-user-plus"></i> Daftar Akun</a>
            @endauth
        </div>
    </div>

    <!-- HEADER UTAMA -->
    <header>
        <div class="header-left">
            <button class="hamburger-btn" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
            
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-icon">❖</span>
                <span class="hide-on-mobile">QITSPREM</span>
            </a>
        </div>

        @auth
            <!-- PROFILE JIKA SUDAH LOGIN -->
            <div class="header-profile" onclick="toggleProfilePopup(event)">
                <div class="profile-btn">
                    <i class="fa-solid fa-user-circle"></i> 
                    <span class="hide-on-mobile">{{ Auth::user()->name }}</span>
                    <i class="fa-solid fa-chevron-down" style="font-size: 11px; margin-left: 2px;"></i>
                </div>
                
                <div class="profile-popup" id="profilePopup">
                    <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
                    <a href="{{ route('dashboard') }}"><i class="fa-solid fa-box"></i> Pesanan Saya</a>
                    <a href="{{ route('bantuan') }}"><i class="fa-solid fa-headset"></i> Pusat Bantuan</a>
                    <a href="{{ route('profile.edit') }}"><i class="fa-solid fa-user"></i> Profile</a>
                    
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge"></i> Dasbor Admin</a>
                    @endif
                    
                    <hr>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="logout-text">
                            <i class="fa-solid fa-right-from-bracket"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- TOMBOL LOGIN JIKA BELUM LOGIN (GUEST) -->
            <div>
                <a href="{{ route('login') }}" class="btn-login-outline">Masuk</a>
            </div>
        @endauth
    </header>

    <!-- KONTEN UTAMA (Berubah-ubah tiap halaman) -->
    <main>
        @yield('content')
    </main>

    <!-- SCRIPT GLOBAL -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }

        function toggleProfilePopup(event) {
            event.stopPropagation();
            const popup = document.getElementById('profilePopup');
            if(popup) popup.classList.toggle('active');
        }

        window.addEventListener('click', function(e) {
            const profilePopup = document.getElementById('profilePopup');
            const profileBtn = document.querySelector('.header-profile');
            if (profilePopup && profilePopup.classList.contains('active') && !profileBtn.contains(e.target)) {
                profilePopup.classList.remove('active');
            }
        });
    </script>
    
    @yield('scripts')

</body>
</html>