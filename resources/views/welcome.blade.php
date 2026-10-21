<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QITSPREM - Murah & Terpercaya</title>
    <style>
        /* --- GLOBAL STYLES --- */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        :root {
            --primary-color: #790D16; /* Warna pink utama */
            --header-bg: #790D16;     /* Warna latar header */
            --bg-color: #FCF1D0;
            --text-dark: #333333;
            --text-gray: #666666;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- HEADER --- */
        header {
            background-color: var(--header-bg);
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #790D16;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo-icon {
            width: 28px;
            height: 28px;
            background-color: var(--primary-color);
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 16px;
            font-weight: bold;
        }

        .logo-text {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #fff;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 14px;
        }

        .nav-btn {
            background-color: #ecc5c9;
            color: var(--primary-color);
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            transition: background 0.3s;
        }

        .nav-btn:hover {
            background-color: #e2b4b9;
        }

        /* --- MAIN CONTENT --- */
        main {
            flex: 1;
            padding: 24px;
            max-width: 1100px;
            margin: 0 auto;
            width: 100%;
        }

        /* --- HERO BANNER CARD --- */
        .banner-card {
            background-color: white;
            border-radius: 20px;
            padding: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
            margin-bottom: 40px;
            width: 100%;
            overflow: hidden;
        }

        .banner-card img {
            width: 100%;
            height: auto;
            border-radius: 12px;
            display: block;
        }

        /* --- BOTTOM SECTION (Tombol & Footer) --- */
        .bottom-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 40px;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 100%;
            max-width: 320px;
        }

        .btn-large {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 14px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 6px 15px rgba(192, 108, 116, 0.3);
            transition: transform 0.2s, background-color 0.2s;
        }

        .btn-large:hover {
            background-color: #a85860;
            transform: translateY(-2px);
        }

        /* --- FOOTER --- */
        footer {
            text-align: center;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .footer-logo .logo-icon {
            width: 24px;
            height: 24px;
            font-size: 14px;
        }

        .footer-logo .logo-text {
            font-size: 18px;
        }

        .copyright {
            font-size: 12px;
            color: var(--text-gray);
        }

        /* --- RESPONSIVE DESKTOP --- */
        @media (min-width: 768px) {
            .bottom-section {
                flex-direction: row;
                justify-content: space-between;
                align-items: flex-end;
            }

            .action-buttons {
                max-width: 350px;
            }

            footer {
                text-align: right;
            }

            .footer-logo {
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header>
        <div class="logo-container">
            <div class="logo-icon">❖</div>
            <div class="logo-text">QITSPREM</div>
        </div>
        
        <div class="nav-links">
            <!-- Link ke route login -->
            <a href="{{ route('login') }}" class="nav-link">Masuk</a>
            <!-- Link ke route register -->
            <a href="{{ route('register') }}" class="nav-btn">Daftar</a>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main>
        
        <!-- KOTAK BANNER TENGAH -->
        <div class="banner-card">
            <!-- 
                PENTING: Ganti URL src di bawah ini dengan nama file gambar banner asli Anda.
                Contoh jika gambar Anda taruh di folder public/images: src="{{ asset('images/banner-utama.jpg') }}" 
            -->
            <img src="{{ asset('images/masganteng.jpg') }}" alt="Promo QITSPREM">
        </div>

        <!-- BAGIAN BAWAH (Tombol & Copyright) -->
        <div class="bottom-section">
            
            <!-- Tombol Kiri -->
            <div class="action-buttons">
                <a href="{{ route('register') }}" class="btn-large">sign up</a>
                <a href="{{ route('login') }}" class="btn-large">Sign in</a>
            </div>

            <!-- Footer Kanan -->
            <footer>
                <div class="footer-logo">
                    <div class="logo-icon">❖</div>
                    <div class="logo-text">QITSPREM</div>
                </div>
                <div class="copyright">
                    Copyright &copy; 2026 QITSPREMCORPORATION. All rights reserved.
                </div>
            </footer>

        </div>
    </main>

</body>
</html>