

<?php $__env->startSection('title', 'Quản lý xe'); ?>
<?php $__env->startSection('section', 'Danh sách xe'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-heading">
        <div><h1>Danh sách xe</h1><p>Quản lý thông tin, trạng thái và mô hình 3D của xe.</p></div>
        <a class="button" href="<?php echo e(route('admin.cars.create')); ?>"><span aria-hidden="true">＋</span> Thêm xe</a>
    </div>

    <div class="stats">
        <div class="stat"><span class="stat-mark">▣</span><div><small>Tổng số xe</small><strong><?php echo e(number_format($stats['total'])); ?></strong></div></div>
        <div class="stat"><span class="stat-mark">●</span><div><small>Đang hiển thị</small><strong><?php echo e(number_format($stats['published'])); ?></strong></div></div>
    </div>

    <div class="toolbar">
        <form method="GET" action="<?php echo e(route('admin.cars.index')); ?>">
            <input class="field" type="search" name="search" value="<?php echo e($search); ?>" placeholder="Tìm theo tên xe, hãng hoặc dòng xe">
            <button class="button secondary" type="submit">Tìm kiếm</button>
        </form>
        <span><?php echo e($cars->total()); ?> xe</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Xe</th><th>Danh mục</th><th>Giá bán</th><th>Tồn kho</th><th>Trạng thái</th><th>Mô hình</th><th>Thao tác</th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><div class="vehicle">
                        <?php if($car->thumbnail): ?><img src="<?php echo e(asset('storage/'.$car->thumbnail)); ?>" alt="<?php echo e($car->name); ?>"><?php else: ?><span class="vehicle-placeholder">Chưa có ảnh</span><?php endif; ?>
                        <div><strong><?php echo e($car->name); ?></strong><small><?php echo e($car->brand); ?><?php echo e($car->model ? ' · '.$car->model : ''); ?><?php echo e($car->year ? ' · '.$car->year : ''); ?></small></div>
                    </div></td>
                    <td><?php echo e($car->category?->name ?? '—'); ?></td>
                    <td class="money"><?php echo e(number_format($car->price, 0, ',', '.')); ?> đ</td>
                    <td><?php echo e($car->stock); ?></td>
                    <td><span class="badge <?php echo e($car->status); ?>"><?php echo e(['draft' => 'Bản nháp', 'published' => 'Đang bán', 'sold_out' => 'Hết hàng'][$car->status]); ?></span></td>
                    <td><?php echo e($car->has_3d_model ? 'GLB' : 'Chưa có'); ?></td>
                    <td><div class="actions">
                        <a class="button secondary small" href="<?php echo e(route('admin.cars.edit', $car)); ?>">Sửa</a>
                        <form method="POST" action="<?php echo e(route('admin.cars.destroy', $car)); ?>" onsubmit="return confirm('Xóa xe này khỏi danh sách?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="button danger small" type="submit">Xóa</button>
                        </form>
                    </div></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td class="empty" colspan="7"><?php echo e($search ? 'Không tìm thấy xe phù hợp.' : 'Chưa có xe trong danh sách.'); ?></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($cars->hasPages()): ?>
        <div class="pagination">
            <?php if($cars->onFirstPage()): ?> <span>Trước</span> <?php else: ?> <a href="<?php echo e($cars->previousPageUrl()); ?>">Trước</a> <?php endif; ?>
            <?php $__currentLoopData = $cars->getUrlRange(max(1, $cars->currentPage() - 2), min($cars->lastPage(), $cars->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($page === $cars->currentPage()): ?> <span class="active"><?php echo e($page); ?></span> <?php else: ?> <a href="<?php echo e($url); ?>"><?php echo e($page); ?></a> <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($cars->hasMorePages()): ?> <a href="<?php echo e($cars->nextPageUrl()); ?>">Sau</a> <?php else: ?> <span>Sau</span> <?php endif; ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laravel-projects\Web-Ban-Xe\resources\views/admin/cars/index.blade.php ENDPATH**/ ?>