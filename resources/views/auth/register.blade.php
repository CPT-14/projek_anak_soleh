<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QITSPREM - Daftar Akun</title>
    <style>
        /* --- GLOBAL STYLES --- */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        :root {
            --primary-color: #790D16; /* Warna pink utama */
            --header-bg: #790D16;     /* Warna latar header */
            --bg-color: #fef3c7;
            --text-dark: #333333;
            --text-gray: #777777;
            --input-border: #e0e0e0;
            --logo-bg: #ffff;
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
            align-items: center;
            border-bottom: 2px solid #790D16;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo-icon {
            width: 28px;
            height: 28px;
            background-color: var(--logo-bg);
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #790D16;
            font-size: 16px;
            font-weight: bold;
        }

        .second-logo-icon {
            width: 28px;
            height: 28px;
            background-color: var(--primary-color);
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #ffff;
            font-size: 16px;
            font-weight: bold;
        }


        .logo-text {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #fff;
        }

        .second-logo-text {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #790D16;
        }

        /* --- MAIN CONTENT & CARD --- */
        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Agar tidak terlalu ke tengah secara vertikal */
            padding: 40px 20px;
            background-color: #fcfcfc; /* Warna latar belakang sedikit lebih terang dari header */
        }

        .register-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 450px;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
        }

        /* --- CARD LOGO & TITLE --- */
        .card-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-bottom: 30px;
        }
        
        .card-logo .second-logo-icon {
            width: 24px;
            height: 24px;
            font-size: 14px;
            border-radius: 6px;
        }

        .card-logo .second-logo-text {
            font-size: 18px;
        }

        .register-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 24px;
            color: #222;
        }

        /* --- FORM ELEMENTS --- */
        .input-group {
            margin-bottom: 16px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-gray);
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--input-border);
            border-radius: 8px;
            font-size: 13px;
            outline: none;
            transition: border 0.3s ease;
            background-color: #fafafa;
        }

        input:focus {
            border-color: var(--primary-color);
            background-color: #fff;
        }

        input::placeholder {
            color: #b0b0b0;
        }

        .eye-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
            cursor: pointer;
            font-size: 14px;
        }

        /* --- ERRORS --- */
        .text-error {
            color: var(--primary-color);
            font-size: 11px;
            margin-top: 4px;
            font-weight: 500;
        }

        /* --- LINKS & BUTTONS --- */
        .forgot-password {
            display: block;
            text-align: right;
            font-size: 11px;
            color: var(--primary-color);
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 30px; /* Bentuk pil / kapsul */
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(192, 108, 116, 0.3);
            transition: background 0.3s, transform 0.2s;
            margin-bottom: 16px;
        }

        .btn-primary:hover {
            background-color: #a85860;
            transform: translateY(-2px);
        }

        /* --- DIVIDER --- */
        .divider {
            text-align: center;
            font-size: 11px;
            color: #999;
            margin: 16px 0;
        }

        /* --- GOOGLE BUTTON --- */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px;
            background-color: white;
            color: #333;
            border: 1px solid var(--input-border);
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-bottom: 30px;
        }

        .btn-google:hover {
            background-color: #f9f9f9;
        }

        .btn-google img {
            width: 16px;
            height: 16px;
        }

        /* --- FOOTER LOGIN LINK --- */
        .login-link {
            text-align: center;
            font-size: 12px;
            color: var(--text-gray);
        }

        .login-link a {
            color: var(--primary-color);
            font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <!-- HEADER TOP -->
    <header>
        <div class="logo-container">
            <div class="logo-icon">❖</div>
            <div class="logo-text">QITSPREM</div>
        </div>
    </header>

    <!-- MAIN KONTEN -->
    <main>
        <div class="register-card">
            
            <!-- Logo dalam Card -->
            <div class="card-logo">
                <div class="second-logo-icon">❖</div>
                <div class="second-logo-text">QITSPREM</div>
            </div>

            <h1 class="register-title">Daftar Akun</h1>

            <!-- FORM REGISTER KE SISTEM LARAVEL -->
            <form method="POST" action="{{ route('register') }}">
                @csrf <!-- Token wajib keamanan Laravel -->

                <!-- NAMA LENGKAP -->
                <div class="input-group">
                    <label>Nama Lengkap</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" required autofocus>
                    </div>
                    @error('name')
                        <div class="text-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- EMAIL -->
                <div class="input-group">
                    <label>Email</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="p6rcelaina@gmail.com" required>
                    </div>
                    @error('email')
                        <div class="text-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- KATA SANDI -->
                <div class="input-group">
                    <label>Kata Sandi</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" placeholder="********" required>
                        <span class="eye-icon" id="toggle-password">👁</span>
                    </div>
                    @error('password')
                        <div class="text-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- KONFIRMASI KATA SANDI -->
                <div class="input-group">
                    <label>Konfirmasi Kata Sandi</label>
                    <div class="input-wrapper">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="********" required>
                        <span class="eye-icon" id="toggle-password-confirm">👁</span>
                    </div>
                    @error('password_confirmation')
                        <div class="text-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Lupa Kata Sandi (Sesuai referensi gambar) -->
                <a href="{{ route('password.request') }}" class="forgot-password">Lupa Kata Sandi?</a>

                <button type="submit" class="btn-primary">Daftar</button>
            </form>

            <div class="divider">atau lanjutkan dengan</div>

            <!-- Tombol Google Login Sederhana -->
            <button class="btn-google">
                <!-- SVG Icon Google -->
                <svg viewBox="0 0 24 24" width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                    <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                        <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"/>
                        <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.369 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z"/>
                        <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"/>
                        <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.109 -17.884 43.989 -14.754 43.989 Z"/>
                    </g>
                </svg>
                Google
            </button>

            <!-- Teks Pindah ke Login -->
            <div class="login-link">
                Sudah memiliki akun? <a href="{{ route('login') }}">Masuk</a>
            </div>

        </div>
    </main>

    <!-- SCRIPT JAVASCRIPT UNTUK ICON MATA -->
    <script>
        // Setup fungsi untuk toggle (mata) password
        function setupPasswordToggle(toggleId, inputId) {
            const toggleBtn = document.getElementById(toggleId);
            const inputField = document.getElementById(inputId);

            toggleBtn.addEventListener('click', function () {
                const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
                inputField.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁' : '👁‍🗨';
            });
        }

        // Jalankan fungsi untuk kedua kolom password
        setupPasswordToggle('toggle-password', 'password');
        setupPasswordToggle('toggle-password-confirm', 'password_confirmation');
    </script>
</body>
</html>