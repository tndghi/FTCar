<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Showroom xe — Trang chủ</title>
    <style>
        :root {
            --bg: #0b0d10;
            --panel: #14171b;
            --line: #23272c;
            --text: #eceff1;
            --text-dim: #9aa3ab;
            --accent: #f5a623;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        a { color: inherit; text-decoration: none; }

        .hero {
            padding: 72px 24px 56px;
            background: radial-gradient(ellipse at 50% 0%, #1a1e23 0%, var(--bg) 65%);
            text-align: center;
        }
        .hero h1 { font-size: 34px; margin: 0 0 10px; }
        .hero p { color: var(--text-dim); margin: 0 0 32px; font-size: 15px; }

        .filter-bar {
            max-width: 900px; margin: 0 auto;
            background: var(--panel); border: 1px solid var(--line); border-radius: 14px;
            padding: 16px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;
        }
        .filter-bar input, .filter-bar select {
            background: #0f1215; border: 1px solid var(--line); color: var(--text);
            padding: 10px 12px; border-radius: 8px; font-size: 14px;
        }
        .filter-bar input[type="text"] { flex: 1; min-width: 180px; }
        .filter-bar button {
            background: var(--accent); color: #1a1200; border: none; font-weight: 700;
            padding: 10px 20px; border-radius: 8px; cursor: pointer; font-size: 14px;
        }
        .filter-bar button:focus-visible, .filter-bar input:focus-visible, .filter-bar select:focus-visible {
            outline: 2px solid var(--accent); outline-offset: 2px;
        }

        .results-info {
            max-width: 1100px; margin: 32px auto 16px; padding: 0 24px;
            color: var(--text-dim); font-size: 14px;
        }

        .car-grid {
            max-width: 1100px; margin: 0 auto; padding: 0 24px 60px;
            display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;
        }
        .car-card {
            background: var(--panel); border: 1px solid var(--line); border-radius: 14px;
            overflow: hidden; display: flex; flex-direction: column;
            transition: border-color .15s;
        }
        .car-card:hover { border-color: var(--accent); }
        .car-card .thumb {
            width: 100%; aspect-ratio: 16/10; background: #1a1e23;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-dim); font-size: 13px;
        }
        .car-card .thumb img { width: 100%; height: 100%; object-fit: cover; }
        .car-card .body { padding: 16px; display: flex; flex-direction: column; gap: 6px; }
        .car-card .brand { font-size: 12px; text-transform: uppercase; letter-spacing: .06em; color: var(--accent); }
        .car-card .name { font-size: 16px; font-weight: 600; }
        .car-card .price { font-size: 17px; font-weight: 700; color: var(--accent); margin-top: 4px; }
        .car-card .badge-3d {
            align-self: flex-start; font-size: 11px; color: var(--text-dim);
            border: 1px solid var(--line); padding: 2px 8px; border-radius: 999px; margin-top: 4px;
        }

        .empty { max-width: 600px; margin: 60px auto; text-align: center; color: var(--text-dim); }

        .pagination { max-width: 1100px; margin: 0 auto 60px; padding: 0 24px; display: flex; justify-content: center; gap: 12px; }
        .pagination a, .pagination span {
            padding: 8px 16px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; color: var(--text-dim);
        }
        .pagination a:hover { border-color: var(--accent); color: var(--text); }
    </style>
</head>
<body>
    <section class="hero">
        <h1>Tìm chiếc xe phù hợp với bạn</h1>
        <p>Xem trước xe bằng mô hình 3D — xoay 360°, chạm vào từng bộ phận để biết chi tiết.</p>

        <form class="filter-bar" method="GET" action="<?php echo e(route('home')); ?>">
            <input type="text" name="search" placeholder="Tìm theo tên, hãng, dòng xe..."
                   value="<?php echo e($filters['search'] ?? ''); ?>">

            <select name="category">
                <option value="">Tất cả danh mục</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->slug); ?>" <?php if(($filters['category'] ?? null) === $category->slug): echo 'selected'; endif; ?>>
                        <?php echo e($category->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="sort">
                <option value="">Mới nhất</option>
                <option value="price_asc" <?php if(($filters['sort'] ?? null) === 'price_asc'): echo 'selected'; endif; ?>>Giá tăng dần</option>
                <option value="price_desc" <?php if(($filters['sort'] ?? null) === 'price_desc'): echo 'selected'; endif; ?>>Giá giảm dần</option>
            </select>

            <button type="submit">Tìm xe</button>
        </form>
    </section>

    <div class="results-info">
        <?php echo e($cars->total()); ?> xe phù hợp
    </div>

    <?php if($cars->isEmpty()): ?>
        <div class="empty">
            Không tìm thấy xe nào khớp bộ lọc. Thử bỏ bớt điều kiện tìm kiếm xem sao.
        </div>
    <?php else: ?>
        <div class="car-grid">
            <?php $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="car-card" href="<?php echo e(route('cars.show', $car->slug)); ?>">
                    <div class="thumb">
                        <?php if($car->thumbnail): ?>
                            <img src="<?php echo e(asset('storage/'.$car->thumbnail)); ?>" alt="<?php echo e($car->name); ?>">
                        <?php else: ?>
                            Chưa có ảnh
                        <?php endif; ?>
                    </div>
                    <div class="body">
                        <div class="brand"><?php echo e($car->brand); ?><?php echo e($car->model ? ' · '.$car->model : ''); ?></div>
                        <div class="name"><?php echo e($car->name); ?></div>
                        <div class="price"><?php echo e(number_format($car->price, 0, ',', '.')); ?> đ</div>
                        <?php if($car->has_3d_model): ?>
                            <span class="badge-3d">Xem 3D</span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="pagination">
            <?php if($cars->previousPageUrl()): ?>
                <a href="<?php echo e($cars->previousPageUrl()); ?>">&larr; Trước</a>
            <?php endif; ?>
            <span>Trang <?php echo e($cars->currentPage()); ?> / <?php echo e($cars->lastPage()); ?></span>
            <?php if($cars->nextPageUrl()): ?>
                <a href="<?php echo e($cars->nextPageUrl()); ?>">Sau &rarr;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html><?php /**PATH C:\laravel-projects\my-app\Web-Ban-Xe\resources\views/home.blade.php ENDPATH**/ ?>