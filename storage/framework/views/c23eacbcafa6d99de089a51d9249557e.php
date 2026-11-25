

<?php $__env->startSection('title', 'Danh mục xe'); ?>
<?php $__env->startSection('section', 'Danh mục xe'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-heading">
        <div><h1>Danh mục xe</h1><p>Tạo nhóm xe để phân loại trên trang showroom.</p></div>
        <a class="button" href="<?php echo e(route('admin.categories.create')); ?>"><span aria-hidden="true">＋</span> Thêm danh mục</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Tên danh mục</th><th>Đường dẫn</th><th>Mô tả</th><th>Số xe</th><th>Thao tác</th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><strong><?php echo e($category->name); ?></strong></td>
                    <td><?php echo e($category->slug); ?></td>
                    <td><?php echo e($category->description ?: '—'); ?></td>
                    <td><?php echo e($category->cars_count); ?></td>
                    <td><div class="actions">
                        <a class="button secondary small" href="<?php echo e(route('admin.categories.edit', $category)); ?>">Sửa</a>
                        <?php if($category->cars_count === 0): ?>
                            <form method="POST" action="<?php echo e(route('admin.categories.destroy', $category)); ?>" onsubmit="return confirm('Xóa danh mục này?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="button danger small" type="submit">Xóa</button>
                            </form>
                        <?php else: ?>
                            <span class="hint">Đang được sử dụng</span>
                        <?php endif; ?>
                    </div></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td class="empty" colspan="5">Chưa có danh mục. Thêm Sedan, SUV hoặc loại xe bạn muốn demo.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($categories->hasPages()): ?>
        <div class="pagination">
            <?php if($categories->onFirstPage()): ?> <span>Trước</span> <?php else: ?> <a href="<?php echo e($categories->previousPageUrl()); ?>">Trước</a> <?php endif; ?>
            <?php $__currentLoopData = $categories->getUrlRange(max(1, $categories->currentPage() - 2), min($categories->lastPage(), $categories->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($page === $categories->currentPage()): ?> <span class="active"><?php echo e($page); ?></span> <?php else: ?> <a href="<?php echo e($url); ?>"><?php echo e($page); ?></a> <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($categories->hasMorePages()): ?> <a href="<?php echo e($categories->nextPageUrl()); ?>">Sau</a> <?php else: ?> <span>Sau</span> <?php endif; ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laravel-projects\Web-Ban-Xe\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>