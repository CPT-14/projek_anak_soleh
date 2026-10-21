<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QITSPREM - Login</title>
    <style>
        /* --- GLOBAL STYLES --- */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #eaeaea;
            color: #333;
        }

        /* --- LOGIN CARD (CONTAINER) --- */
        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 30px 24px;
        }

        /* --- LOGO SECTION --- */
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 24px;
            height: 24px;
            background-color: #790D16;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 14px;
            font-weight: bold;
        }

        .logo-text {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #790D16;
        }

        /* --- HEADING --- */
        h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 24px;
            text-align: left;
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
            color: #777;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 13px;
            outline: none;
            transition: border 0.3s ease;
        }

        input:focus {
            border-color: #790D16;
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

        /* --- LINKS & BUTTONS --- */
        .forgot-password {
            display: block;
            text-align: right;
            font-size: 11px;
            color: #790D16;
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background-color: #790D16;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(209, 105, 116, 0.3);
            transition: background 0.3s;
        }

        .btn-primary:hover {
            background-color: #790D16;
        }

        /* --- DIVIDER --- */
        .divider {
            text-align: center;
            font-size: 11px;
            color: #999;
            margin: 24px 0;
        }

        /* --- SOCIAL LOGIN --- */
        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-bottom: 40px;
        }

        .social-btn {
            background: none;
            border: none;
            font-size: 12px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
        }

        /* --- FOOTER --- */
        .signup-text {
            text-align: center;
            font-size: 11px;
            color: #777;
        }

        .signup-text a {
            color: #790D16;
            font-weight: 600;
            text-decoration: none;
        }

        /* --- ERROR & STATUS MESSAGE --- */
        .text-error {
            color: #d16974;
            font-size: 11px;
            margin-top: 4px;
            font-weight: 500;
        }
        .text-success {
            color: #10b981;
            font-size: 12px;
            margin-bottom: 16px;
            font-weight: 500;
            text-align: center;
        }

        @media (min-width: 768px) {
            body {
                background-color: #fef3c7;
            }

            .login-card {
                background-color: #ffffff;
                border-radius: 16px;
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
                padding: 40px;
            }
            
            h1 {
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-container">
            <div class="logo-icon">❖</div>
            <div class="logo-text">QITSPREM</div>
        </div>

        <h1>Welcome Back</h1>

        <!-- Notifikasi jika berhasil reset password -->
        @if (session('status'))
            <div class="text-success">
                {{ session('status') }}
            </div>
        @endif

        <!-- FORM ACTION & METHOD -->
        <form action="{{ route('login') }}" method="POST">
            <!-- TOKEN KEAMANAN WAJIB -->
            @csrf

            <div class="input-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <!-- Tambahkan name="email" dan value old -->
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="p6rcelaina@gmail.com" required autofocus>
                </div>
                <!-- Pesan error jika email salah -->
                @error('email')
                    <div class="text-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <!-- Tambahkan name="password" -->
                    <input type="password" name="password" id="password-input" placeholder="********" required>
                    <!-- Tambahkan id untuk dipanggil di Javascript -->
                    <span class="eye-icon" id="toggle-password">👁</span>
                </div>
                <!-- Pesan error jika password salah -->
                @error('password')
                    <div class="text-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Link Lupa Password -->
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
            @endif

            <!-- Hapus href="home2.html" karena form otomatis diarahkan oleh Laravel -->
            <button type="submit" class="btn-primary">Sign In</button>
        </form>

        <div class="divider">or continue with</div>

        <div class="social-buttons">
            <button class="social-btn">Google</button>
            <button class="social-btn">Apple</button>
        </div>

        <div class="signup-text">
            <!-- Link ke halaman Register -->
            Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
        </div>
    </div>

    <!-- SCRIPT TAMBAHAN AGAR MATA (EYE ICON) BISA DIKLIK -->
    <script>
        const togglePassword = document.querySelector('#toggle-password');
        const passwordInput = document.querySelector('#password-input');

        togglePassword.addEventListener('click', function () {
            // Cek apakah tipenya password atau text
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            // Ubah icon jika perlu (opsional)
            this.textContent = type === 'password' ? '👁' : '👁‍🗨';
        });
    </script>
</body>
</html>