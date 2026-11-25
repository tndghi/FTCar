<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản trị showroom') · FTCars</title>
    <style>
        :root { --ink: #172521; --muted: #78837e; --paper: #f3f5f2; --white: #fff; --line: #e2e8e3; --green: #176b51; --green-dark: #104c3c; --lime: #d7ed9f; --red: #b6413a; }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--paper); color: var(--ink); font-family: "Segoe UI", Arial, sans-serif; font-size: 14px; }
        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }
        .shell { min-height: 100vh; }
        .sidebar { position: fixed; inset: 0 auto 0 0; z-index: 3; width: 238px; padding: 26px 18px; background: var(--green-dark); color: #edf4ef; display: flex; flex-direction: column; }
        .brand { display: flex; align-items: center; gap: 11px; padding: 0 10px 32px; }
        .brand-mark { width: 36px; height: 36px; display: grid; place-items: center; border-radius: 10px; background: var(--lime); color: var(--green-dark); font-weight: 800; font-size: 15px; }
        .brand strong { display: block; font-size: 14px; }
        .brand small { display: block; margin-top: 3px; color: #a9c2b5; font-size: 11px; }
        .nav-label { padding: 0 10px; margin-bottom: 10px; color: #a9c2b5; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .nav-link { display: flex; align-items: center; gap: 12px; min-height: 42px; margin: 2px 0; padding: 0 11px; border-radius: 6px; color: #d6e5dc; }
        .nav-link { font-size: 13px; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,.12); color: white; }
        .nav-icon { width: 18px; text-align: center; font-size: 16px; }
        .sidebar-bottom { margin-top: auto; border-top: 1px solid rgba(255,255,255,.15); padding: 16px 9px 0; }
        .admin-name { margin-bottom: 12px; color: white; font-weight: 600; }
        .admin-name small { display: block; margin-top: 4px; color: #a9c2b5; font-weight: 400; }
        .logout { padding: 0; border: 0; background: transparent; color: #d6e5dc; cursor: pointer; }
        .logout:hover { color: white; }
        .main { min-height: 100vh; margin-left: 238px; }
        .topbar { height: 66px; padding: 0 34px; background: var(--white); border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; }
        .topbar .eyebrow { color: var(--muted); font-size: 12px; }
        .topbar a { color: var(--green); font-weight: 600; }
        .content { max-width: 1440px; margin: 0 auto; padding: 30px 34px 52px; }
        .page-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; margin-bottom: 24px; }
        .page-heading h1 { margin: 0; font-size: 25px; letter-spacing: 0; }
        .page-heading p { margin: 7px 0 0; color: var(--muted); }
        .button { display: inline-flex; min-height: 40px; align-items: center; justify-content: center; gap: 8px; padding: 0 15px; border: 1px solid var(--green); border-radius: 5px; background: var(--green); color: white; font-weight: 650; cursor: pointer; }
        .button:hover { background: var(--green-dark); }
        .button.secondary { background: white; color: var(--ink); border-color: var(--line); }
        .button.danger { background: white; border-color: #efcfcd; color: var(--red); }
        .button.small { min-height: 32px; padding: 0 10px; font-size: 12px; }
        .stats { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; margin-bottom: 20px; }
        .stat { display: flex; align-items: center; gap: 14px; min-height: 84px; padding: 16px 18px; background: white; border: 1px solid var(--line); border-radius: 6px; }
        .stat-mark { width: 38px; height: 38px; display: grid; place-items: center; border-radius: 6px; background: #eaf3ec; color: var(--green); font-size: 18px; }
        .stat small { display: block; color: var(--muted); }
        .stat strong { display: block; margin-top: 4px; font-size: 20px; }
        .toolbar { display: flex; align-items: center; justify-content: space-between; gap: 14px; margin: 18px 0 12px; }
        .toolbar form { display: flex; flex: 1; gap: 8px; max-width: 620px; }
        .field, .select, textarea { width: 100%; min-height: 40px; padding: 9px 11px; border: 1px solid #d5ded7; border-radius: 5px; background: white; color: var(--ink); outline: none; }
        .field:focus, .select:focus, textarea:focus { border-color: var(--green); box-shadow: 0 0 0 3px rgba(23,107,81,.1); }
        textarea { min-height: 120px; resize: vertical; }
        label { display: block; margin-bottom: 6px; font-size: 12px; font-weight: 650; }
        .table-wrap { overflow-x: auto; background: white; border: 1px solid var(--line); border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 12px 14px; background: #f8faf8; color: var(--muted); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; white-space: nowrap; }
        td { padding: 13px 14px; border-top: 1px solid #edf0ed; vertical-align: middle; }
        .vehicle { min-width: 230px; display: flex; align-items: center; gap: 11px; }
        .vehicle img, .vehicle-placeholder { width: 70px; height: 48px; flex: 0 0 auto; border-radius: 4px; object-fit: cover; background: #edf1ed; }
        .vehicle-placeholder { display: grid; place-items: center; color: #78837e; font-size: 11px; }
        .vehicle strong { display: block; font-size: 13px; }
        .vehicle small, td small { display: block; margin-top: 4px; color: var(--muted); }
        .money { white-space: nowrap; font-weight: 700; }
        .badge { display: inline-flex; align-items: center; padding: 4px 8px; border-radius: 3px; background: #eef1ed; color: #66716b; font-size: 11px; white-space: nowrap; }
        .badge.published, .badge.confirmed, .badge.completed { background: #e6f4eb; color: #236a46; }
        .badge.draft, .badge.pending { background: #fff3d8; color: #8d5e0a; }
        .badge.sold_out, .badge.cancelled { background: #fbe9e7; color: #a13b34; }
        .actions { display: flex; align-items: center; gap: 7px; }
        .inline-form { display: flex; align-items: center; gap: 6px; min-width: 235px; }
        .inline-form .select { min-width: 132px; min-height: 34px; padding: 5px 8px; font-size: 12px; }
        .empty { padding: 44px 20px; text-align: center; color: var(--muted); }
        .pagination { display: flex; justify-content: center; gap: 8px; margin-top: 18px; }
        .pagination a, .pagination span { padding: 7px 11px; border: 1px solid var(--line); border-radius: 4px; background: white; }
        .pagination .active { background: var(--green); border-color: var(--green); color: white; }
        .alert { margin-bottom: 18px; padding: 12px 14px; border: 1px solid #cfe7d5; border-radius: 5px; background: #eff8f1; color: #236a46; }
        .alert.error { border-color: #efcfcd; background: #fff1ef; color: var(--red); }
        .error { margin-top: 5px; color: var(--red); font-size: 12px; }
        .form-panel { max-width: 900px; padding: 22px; background: white; border: 1px solid var(--line); border-radius: 6px; }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
        .form-field.full { grid-column: 1 / -1; }
        .parts-editor { padding-top: 20px; border-top: 1px solid var(--line); }
        .parts-editor h2 { margin: 0 0 6px; font-size: 16px; }
        .parts-editor > p { margin: 0 0 16px; color: var(--muted); font-size: 12px; line-height: 1.5; }
        .parts-list { display: grid; gap: 14px; }
        .part-row { display: grid; grid-template-columns: 1.1fr 1fr 1.5fr .7fr auto; gap: 8px; align-items: end; padding-bottom: 14px; border-bottom: 1px solid var(--line); }
        .part-row label { font-size: 11px; }
        .part-row .field { min-width: 0; }
        .remove-part { min-height: 40px; padding: 0 10px; border: 1px solid #efcfcd; border-radius: 5px; background: white; color: var(--red); cursor: pointer; }
        .hint { margin-top: 6px; color: var(--muted); font-size: 11px; }
        .current-file { margin-top: 8px; color: var(--green); font-size: 12px; overflow-wrap: anywhere; }
        .check-row { display: flex; align-items: center; gap: 8px; margin-top: 10px; color: var(--muted); }
        .form-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--line); }
        @media (max-width: 820px) {
            .sidebar { position: static; width: auto; min-height: auto; padding: 12px 16px; flex-direction: row; align-items: center; gap: 15px; }
            .brand { padding: 0; }
            .nav-label { display: none; }
            .nav-link { min-height: 36px; margin: 0; padding: 0 8px; font-size: 12px; }
            .nav-icon { display: none; }
            .sidebar-bottom { display: flex; align-items: center; margin: 0 0 0 auto; padding: 0; border: 0; }
            .admin-name { display: none; }
            .main { margin-left: 0; }
            .topbar { height: 52px; padding: 0 18px; }
            .content { padding: 24px 18px 40px; }
        }
        @media (max-width: 560px) {
            .sidebar { gap: 8px; overflow-x: auto; }
            .brand strong { font-size: 12px; white-space: nowrap; }
            .brand small { display: none; }
            .brand-mark { width: 32px; height: 32px; }
            .nav-link { white-space: nowrap; }
            .page-heading { align-items: stretch; flex-direction: column; }
            .page-heading .button { align-self: flex-start; }
            .stats { grid-template-columns: 1fr 1fr; gap: 8px; }
            .stat { min-height: 72px; padding: 10px; gap: 8px; }
            .stat-mark { width: 30px; height: 30px; }
            .toolbar { align-items: stretch; flex-direction: column; }
            .toolbar form { max-width: none; }
            .form-grid { grid-template-columns: 1fr; }
            .form-field.full { grid-column: auto; }
            .form-panel { padding: 16px; }
            .part-row { grid-template-columns: 1fr 1fr; }
            .part-row .description-field { grid-column: 1 / -1; }
            .remove-part { justify-self: start; }
        }
    </style>
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('admin.cars.index') }}">
            <span class="brand-mark">FT</span>
            <span><strong>FTCars</strong><small>Quản trị showroom</small></span>
        </a>
        <div class="nav-label">Quản lý</div>
        <a class="nav-link {{ request()->routeIs('admin.cars.*') ? 'active' : '' }}" href="{{ route('admin.cars.index') }}"><span class="nav-icon">▣</span> Danh sách xe</a>
        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><span class="nav-icon">▤</span> Danh mục xe</a>
        <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}"><span class="nav-icon">◷</span> Lịch hẹn</a>
        <div class="sidebar-bottom">
            <div class="admin-name">{{ auth()->user()->name }}<small>{{ auth()->user()->email }}</small></div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout" type="submit">Đăng xuất</button>
            </form>
        </div>
    </aside>
    <main class="main">
        <header class="topbar">
            <span class="eyebrow">Bảng điều khiển / @yield('section', 'Quản lý')</span>
            <a href="{{ route('home') }}" target="_blank" rel="noopener">Mở trang showroom ↗</a>
        </header>
        <div class="content">
            @if (session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert error">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert error">Vui lòng kiểm tra lại thông tin đã nhập.</div>
            @endif
            @yield('content')
        </div>
    </main>
</div>
</body>
</html>