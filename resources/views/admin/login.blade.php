<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            height: 100vh;
            background: #f1f3f2;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            width: 900px;
            height: 500px;
            display: flex;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-radius: 12px;
            overflow: hidden;
        }

        /* KIRI */
        .login-left {
            width: 50%;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }

        .login-left img {
            width: 140px;
            margin-bottom: 20px;
        }

        .login-left h2 {
            color: #c62828; /* merah bintang */
            margin-bottom: 5px;
        }

        .login-left p {
            color: #555;
            font-size: 14px;
        }

        /* KANAN */
        .login-right {
            width: 50%;
            background: #0288d1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            width: 80%;
            color: white;
        }

        .login-box h2 {
            margin-bottom: 20px;
            color: #e3f2fd;
        }

        .login-box label {
            font-size: 14px;
            margin-bottom: 6px;
            display: block;
        }

        .login-box input {
            width: 100%;
            padding: 11px;
            border-radius: 6px;
            border: none;
            margin-bottom: 16px;
            outline: none;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background: #00456bff; /* biru spiral */
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-box button:hover {
            background: #035c8cff;
        }

        .error {
            background: #ffebee;
            color: #b71c1c;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-container">

    <!-- KIRI -->
    <div class="login-left">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Moya Kristal">
    </div>

    <!-- KANAN -->
    <div class="login-right">
        <div class="login-box">

            <h2>Login Admin</h2>

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/admin/login">
                @csrf

                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan email" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>

                <button type="submit">LOGIN</button>
            </form>

        </div>
    </div>

</div>

</body>
</html>
