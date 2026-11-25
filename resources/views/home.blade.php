<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f4f5f1">
    <title>FTCars — Chọn xe theo cách của bạn</title>
    <style>
        :root {
            --canvas: #f4f5f1;
            --surface: #fff;
            --ink: #1d2925;
            --muted: #78817b;
            --line: #dfe4de;
            --green: #214f40;
            --green-deep: #173c32;
            --clay: #c56242;
            --lime: #d8e6bf;
            --content: 1240px;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; background: var(--canvas); color: var(--ink); font-family: "Segoe UI", Arial, sans-serif; }
        a { color: inherit; text-decoration: none; }
        button, input, select { font: inherit; }
        .site-header { height: 72px; padding: 0 max(24px, calc((100vw - var(--content)) / 2)); display: flex; align-items: center; justify-content: space-between; gap: 24px; background: var(--surface); border-bottom: 1px solid var(--line); }
        .brand { display: flex; align-items: center; gap: 11px; font-size: 15px; font-weight: 750; }
        .brand-mark { width: 36px; height: 36px; display: grid; place-items: center; background: var(--green); color: white; border-radius: 5px; font-size: 12px; letter-spacing: 0; }
        .brand small { display: block; margin-top: 2px; color: var(--muted); font-size: 10px; font-weight: 500; }
        .header-nav { display: flex; align-items: center; gap: 28px; color: #4f5d55; font-size: 13px; }
        .header-nav a:hover { color: var(--clay); }
        .header-nav .admin-link { color: var(--green); font-weight: 700; }
        .hero { max-width: var(--content); min-height: 330px; margin: 28px auto 0; padding: 0 26px; display: grid; grid-template-columns: .9fr 1.1fr; align-items: center; overflow: hidden; background: #e6ebe2; }
        .hero-copy { position: relative; z-index: 1; padding: 34px 0; }
        .eyebrow { color: var(--clay); font-size: 11px; font-weight: 750; text-transform: uppercase; letter-spacing: 1.4px; }
        .hero h1 { max-width: 480px; margin: 12px 0 10px; color: var(--green-deep); font-family: "Segoe UI", Arial, sans-serif; font-size: 40px; font-weight: 650; line-height: 1.12; letter-spacing: 0; }
        .hero-copy p { max-width: 410px; margin: 0; color: #59665f; font-size: 14px; line-height: 1.7; }
        .hero-actions { display: flex; align-items: center; gap: 16px; margin-top: 24px; }
        .button { display: inline-flex; min-height: 42px; align-items: center; justify-content: center; padding: 0 17px; border: 1px solid var(--green); background: var(--green); color: #fff; font-size: 13px; font-weight: 700; transition: background .16s ease, border-color .16s ease; }
        .button:hover { border-color: var(--green-deep); background: var(--green-deep); }
        .text-link { color: var(--green); font-size: 13px; font-weight: 700; }
        .hero-visual { position: relative; align-self: stretch; min-height: 292px; margin: 14px -26px 14px 0; overflow: hidden; background: #d0d8cf; }
        .hero-visual img { width: 100%; height: 100%; position: absolute; inset: 0; object-fit: cover; }
        .hero-caption { position: absolute; right: 16px; bottom: 16px; left: 16px; display: flex; align-items: end; justify-content: space-between; gap: 12px; padding: 15px 17px; background: rgba(23,60,50,.92); color: white; }
        .hero-caption small { display: block; margin-bottom: 4px; color: #c7d7ca; font-size: 10px; text-transform: uppercase; letter-spacing: .8px; }
        .hero-caption strong { font-size: 15px; }
        .hero-caption .hero-price { white-space: nowrap; color: var(--lime); font-size: 14px; font-weight: 700; }
        .showroom { max-width: var(--content); margin: 0 auto; padding: 46px 24px 68px; }
        .section-heading { display: flex; align-items: end; justify-content: space-between; gap: 20px; margin-bottom: 20px; }
        .section-heading h2 { margin: 7px 0 0; font-family: "Segoe UI", Arial, sans-serif; font-size: 30px; font-weight: 650; letter-spacing: 0; }
        .result-count { color: var(--muted); font-size: 13px; white-space: nowrap; }
        .filter-bar { display: grid; grid-template-columns: minmax(220px, 1.5fr) minmax(150px, .8fr) minmax(120px, .65fr) minmax(120px, .65fr) minmax(155px, .8fr) auto; gap: 8px; margin-bottom: 22px; padding: 10px; background: var(--surface); border: 1px solid var(--line); }
        .filter-bar input, .filter-bar select { min-width: 0; height: 42px; padding: 0 11px; border: 1px solid transparent; outline: none; background: #f5f6f3; color: var(--ink); font-size: 12px; }
        .filter-bar input:focus, .filter-bar select:focus { border-color: var(--green); }
        .filter-bar input::placeholder { color: #8b948e; }
        .filter-bar button { min-height: 42px; padding: 0 18px; border: 0; background: var(--clay); color: white; font-size: 12px; font-weight: 750; cursor: pointer; }
        .filter-bar button:hover { background: #a94c31; }
        .filter-bar :focus-visible, .button:focus-visible, .text-link:focus-visible { outline: 2px solid var(--clay); outline-offset: 2px; }
        .car-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
        .car-card { min-width: 0; display: flex; flex-direction: column; background: var(--surface); border: 1px solid var(--line); transition: border-color .16s ease, transform .16s ease; }
        .car-card:hover { border-color: #9aa99b; transform: translateY(-2px); }
        .car-image { position: relative; width: 100%; aspect-ratio: 16 / 10; overflow: hidden; background: #e2e6e1; }
        .car-image img { width: 100%; height: 100%; display: block; object-fit: cover; transition: transform .3s ease; }
        .car-card:hover .car-image img { transform: scale(1.025); }
        .model-tag { position: absolute; top: 11px; left: 11px; padding: 5px 8px; background: var(--green); color: white; font-size: 10px; font-weight: 700; }
        .car-body { display: flex; flex: 1; flex-direction: column; padding: 15px 16px 16px; }
        .car-meta { display: flex; justify-content: space-between; gap: 10px; color: var(--muted); font-size: 11px; }
        .car-brand { color: var(--clay); font-weight: 750; text-transform: uppercase; }
        .car-name { margin: 8px 0 14px; font-size: 17px; font-weight: 650; line-height: 1.3; }
        .car-bottom { display: flex; align-items: end; justify-content: space-between; gap: 12px; margin-top: auto; padding-top: 12px; border-top: 1px solid #edf0ec; }
        .price-label { display: block; margin-bottom: 4px; color: var(--muted); font-size: 10px; }
        .car-price { color: var(--green); font-size: 17px; font-weight: 750; white-space: nowrap; }
        .detail-link { color: var(--green); font-size: 12px; font-weight: 700; white-space: nowrap; }
        .empty { padding: 54px 22px; border: 1px solid var(--line); background: var(--surface); text-align: center; }
        .empty h3 { margin: 0 0 8px; font-family: "Segoe UI", Arial, sans-serif; font-size: 24px; font-weight: 650; }
        .empty p { margin: 0 0 18px; color: var(--muted); }
        .pagination { display: flex; align-items: center; justify-content: center; gap: 10px; margin-top: 28px; }
        .pagination a, .pagination span { min-width: 38px; min-height: 36px; display: grid; place-items: center; padding: 0 10px; border: 1px solid var(--line); background: var(--surface); color: var(--muted); font-size: 12px; }
        .pagination a:hover { border-color: var(--green); color: var(--green); }
        .pagination .current { border-color: var(--green); background: var(--green); color: white; }
        .site-footer { padding: 22px 24px; border-top: 1px solid var(--line); color: var(--muted); font-size: 11px; }
        .footer-inner { max-width: var(--content); margin: auto; display: flex; justify-content: space-between; gap: 12px; }
        @media (max-width: 980px) {
            .hero { margin: 18px 18px 0; grid-template-columns: .9fr 1.1fr; }
            .filter-bar { grid-template-columns: 1.3fr 1fr 1fr; }
            .filter-bar input[type="search"] { grid-column: span 2; }
            .filter-bar button { grid-column: 3; grid-row: 2; }
            .car-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 640px) {
            .site-header { height: 60px; padding: 0 16px; }
            .header-nav { gap: 13px; font-size: 11px; }
            .brand { gap: 8px; font-size: 13px; }
            .brand-mark { width: 32px; height: 32px; }
            .hero { min-height: auto; margin: 10px 10px 0; padding: 0 18px; grid-template-columns: 1fr; }
            .hero-copy { padding: 28px 0 20px; }
            .hero h1 { max-width: 390px; margin-top: 10px; font-size: 36px; }
            .hero-copy p { max-width: 390px; font-size: 13px; }
            .hero-actions { margin-top: 17px; }
            .hero-visual { min-height: 195px; margin: 0 -18px 0; }
            .hero-caption { right: 10px; bottom: 10px; left: 10px; padding: 10px 12px; }
            .hero-caption strong { font-size: 13px; }
            .hero-caption .hero-price { font-size: 12px; }
            .showroom { padding: 34px 14px 48px; }
            .section-heading { align-items: start; }
            .section-heading h2 { font-size: 26px; }
            .result-count { padding-top: 18px; font-size: 11px; }
            .filter-bar { grid-template-columns: 1fr 1fr; gap: 7px; padding: 8px; }
            .filter-bar input[type="search"] { grid-column: 1 / -1; }
            .filter-bar button { grid-column: 1 / -1; grid-row: auto; }
            .car-grid { gap: 10px; }
            .car-body { padding: 11px; }
            .car-meta { flex-wrap: wrap; font-size: 9px; }
            .car-name { min-height: 40px; margin: 7px 0 10px; font-size: 14px; }
            .car-bottom { align-items: start; flex-direction: column; gap: 8px; padding-top: 9px; }
            .car-price { font-size: 14px; }
            .detail-link { font-size: 11px; }
            .model-tag { top: 7px; left: 7px; padding: 4px 6px; font-size: 9px; }
            .footer-inner { flex-direction: column; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <a class="brand" href="{{ route('home') }}">
            <span class="brand-mark">FT</span>
            <span>FTCars<small>Showroom xe</small></span>
        </a>
        <nav class="header-nav" aria-label="Điều hướng chính">
            <a href="#inventory">Danh sách xe</a>
            <a class="admin-link" href="{{ route('admin.login') }}">Quản trị</a>
        </nav>
    </header>

    <main>
        <section class="hero" aria-labelledby="hero-title">
            <div class="hero-copy">
                <span class="eyebrow">FTCars · Chọn xe theo cách của bạn</span>
                <h1 id="hero-title">Tìm chiếc xe hợp nhịp sống của bạn.</h1>
                <p>Khám phá những mẫu xe phù hợp với nhịp sống và ngân sách của bạn.</p>
                <div class="hero-actions">
                    <a class="button" href="#inventory">Khám phá xe</a>
                    @if ($featuredCar)
                        <a class="text-link" href="{{ route('cars.show', $featuredCar->slug) }}">Xe nổi bật <span aria-hidden="true">↗</span></a>
                    @endif
                </div>
            </div>
            <a class="hero-visual" href="{{ $featuredCar ? route('cars.show', $featuredCar->slug) : '#inventory' }}" aria-label="{{ $featuredCar ? 'Xem '.$featuredCar->name : 'Khám phá danh sách xe' }}">
                <img src="{{ $featuredCar?->thumbnail ? asset('storage/'.$featuredCar->thumbnail) : 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1400&q=85' }}" alt="{{ $featuredCar?->name ?? 'Xe trưng bày tại FTCars' }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1400&q=85'">
                @if ($featuredCar)
                    <span class="hero-caption">
                        <span><small>Xe mới tại showroom</small><strong>{{ $featuredCar->name }}</strong></span>
                        <span class="hero-price">{{ number_format($featuredCar->price, 0, ',', '.') }} đ</span>
                    </span>
                @endif
            </a>
        </section>

        <section class="showroom" id="inventory" aria-labelledby="inventory-title">
            <div class="section-heading">
                <div><span class="eyebrow">Bộ sưu tập</span><h2 id="inventory-title">Tìm chiếc xe của bạn</h2></div>
                <span class="result-count">{{ number_format($cars->total()) }} xe phù hợp</span>
            </div>

            <form class="filter-bar" method="GET" action="{{ route('home') }}">
                <input type="search" name="search" placeholder="Tên xe, hãng xe..." aria-label="Tìm xe theo tên hoặc hãng" value="{{ $filters['search'] ?? '' }}">
                <select name="category" aria-label="Lọc theo danh mục">
                    <option value="">Tất cả danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(($filters['category'] ?? null) === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="min_price" min="0" step="1000000" placeholder="Giá từ" aria-label="Giá thấp nhất" value="{{ $filters['min_price'] ?? '' }}">
                <input type="number" name="max_price" min="0" step="1000000" placeholder="Giá đến" aria-label="Giá cao nhất" value="{{ $filters['max_price'] ?? '' }}">
                <select name="sort" aria-label="Sắp xếp xe">
                    <option value="">Mới nhất</option>
                    <option value="price_asc" @selected(($filters['sort'] ?? null) === 'price_asc')>Giá tăng dần</option>
                    <option value="price_desc" @selected(($filters['sort'] ?? null) === 'price_desc')>Giá giảm dần</option>
                </select>
                <button type="submit">Lọc xe</button>
            </form>

            @if ($cars->isEmpty())
                <div class="empty">
                    <h3>Chưa tìm thấy mẫu xe phù hợp</h3>
                    <p>Thử thay đổi từ khóa hoặc điều kiện lọc.</p>
                    <a class="button" href="{{ route('home') }}">Xem tất cả xe</a>
                </div>
            @else
                <div class="car-grid">
                    @foreach ($cars as $car)
                        <a class="car-card" href="{{ route('cars.show', $car->slug) }}">
                            <div class="car-image">
                                <img src="{{ $car->thumbnail ? asset('storage/'.$car->thumbnail) : 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $car->name }}" loading="lazy" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=900&q=80'">
                                @if ($car->has_3d_model)<span class="model-tag">Mô hình 3D</span>@endif
                            </div>
                            <div class="car-body">
                                <div class="car-meta"><span class="car-brand">{{ $car->brand }}{{ $car->model ? ' · '.$car->model : '' }}</span><span>{{ $car->year ?? 'Đời mới' }}</span></div>
                                <h3 class="car-name">{{ $car->name }}</h3>
                                <div class="car-bottom">
                                    <span><span class="price-label">Giá bán</span><span class="car-price">{{ number_format($car->price, 0, ',', '.') }} đ</span></span>
                                    <span class="detail-link">Chi tiết <span aria-hidden="true">↗</span></span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if ($cars->hasPages())
                    <nav class="pagination" aria-label="Phân trang danh sách xe">
                        @if ($cars->previousPageUrl()) <a href="{{ $cars->previousPageUrl() }}" aria-label="Trang trước">←</a> @endif
                        <span class="current">{{ $cars->currentPage() }}</span>
                        <span> / {{ $cars->lastPage() }}</span>
                        @if ($cars->nextPageUrl()) <a href="{{ $cars->nextPageUrl() }}" aria-label="Trang sau">→</a> @endif
                    </nav>
                @endif
            @endif
        </section>
    </main>

    <footer class="site-footer"><div class="footer-inner"><span>© {{ now()->year }} FTCars · Showroom xe</span><a href="{{ route('admin.login') }}">Quản trị showroom</a></div></footer>
</body>
</html>