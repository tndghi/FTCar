<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập quản trị · FTCars</title>
    <style>
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; display: grid; grid-template-columns: 1.1fr .9fr; background: #f3f5f2; color: #172521; font-family: "Segoe UI", Arial, sans-serif; }
        .visual { padding: 48px; display: flex; flex-direction: column; justify-content: space-between; background: #104c3c; color: #f2f7f2; position: relative; overflow: hidden; }
        .visual::after { content: ""; position: absolute; width: 430px; height: 430px; right: -100px; bottom: -180px; border: 1px solid rgba(215,237,159,.28); border-radius: 50%; box-shadow: 0 0 0 45px rgba(215,237,159,.04), 0 0 0 100px rgba(215,237,159,.03); }
        .brand { display: flex; align-items: center; gap: 11px; font-weight: 700; position: relative; z-index: 1; }
        .mark { width: 38px; height: 38px; display: grid; place-items: center; border-radius: 10px; background: #d7ed9f; color: #104c3c; }
        .visual-copy { max-width: 480px; position: relative; z-index: 1; }
        .visual-copy small { color: #c4d8c8; text-transform: uppercase; font-weight: 700; }
        .visual-copy h1 { margin: 14px 0; font-size: 50px; line-height: 1.04; letter-spacing: 0; }
        .visual-copy p { color: #c4d8c8; line-height: 1.7; }
        .visual footer { color: #a9c2b5; font-size: 12px; position: relative; z-index: 1; }
        .form-side { display: grid; place-items: center; padding: 30px; }
        .login { width: min(390px, 100%); }
        .login h2 { margin: 0; font-size: 27px; letter-spacing: 0; }
        .login > p { margin: 8px 0 28px; color: #78837e; }
        label { display: block; margin: 16px 0 7px; font-size: 12px; font-weight: 700; }
        input { width: 100%; height: 45px; padding: 0 12px; border: 1px solid #d5ded7; border-radius: 5px; background: white; color: #172521; font: inherit; }
        input:focus { outline: 2px solid rgba(23,107,81,.2); border-color: #176b51; }
        .remember { display: flex; align-items: center; gap: 8px; margin: 16px 0 20px; color: #78837e; font-size: 12px; }
        .remember input { width: 15px; height: 15px; accent-color: #176b51; }
        button { width: 100%; min-height: 45px; border: 0; border-radius: 5px; background: #176b51; color: white; font: inherit; font-weight: 700; cursor: pointer; }
        button:hover { background: #104c3c; }
        .error { margin-top: 6px; color: #b6413a; font-size: 12px; }
        .alert { margin-bottom: 14px; padding: 10px 12px; border-radius: 5px; background: #fff1ef; color: #b6413a; font-size: 13px; }
        @media (max-width: 760px) { body { display: block; } .visual { min-height: 220px; padding: 22px; } .visual-copy { margin-top: 30px; } .visual-copy h1 { font-size: 32px; } .visual footer { display: none; } .form-side { padding: 38px 22px; } }
    </style>
</head>
<body>
    <section class="visual">
        <a class="brand" href="{{ route('home') }}"><span class="mark">FT</span> FTCars · Showroom</a>
        <div class="visual-copy"><small>Không gian quản trị</small><h1>Vận hành showroom, gọn trong một nơi.</h1><p>Quản lý kho xe, mô hình 3D và lịch hẹn xem xe.</p></div>
        <footer>FTCars · Hệ thống quản lý showroom</footer>
    </section>
    <main class="form-side">
        <form class="login" method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <h2>Đăng nhập</h2>
            <p>Dành cho tài khoản quản trị showroom.</p>
            @if ($errors->has('email')) <div class="alert">{{ $errors->first('email') }}</div> @endif
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            <label for="password">Mật khẩu</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">
            <label class="remember"><input type="checkbox" name="remember" value="1"> Ghi nhớ đăng nhập</label>
            <button type="submit">Đăng nhập quản trị</button>
        </form>
    </main>
</body>
</html>