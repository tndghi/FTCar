<?php $__env->startSection('title', 'Lịch hẹn xem xe'); ?>
<?php $__env->startSection('section', 'Lịch hẹn'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-heading"><div><h1>Lịch hẹn xem xe</h1><p>Theo dõi và cập nhật trạng thái các yêu cầu từ khách hàng.</p></div></div>

    <div class="stats">
        <div class="stat"><span class="stat-mark">◷</span><div><small>Chờ xác nhận</small><strong><?php echo e($counts->get('pending', 0)); ?></strong></div></div>
        <div class="stat"><span class="stat-mark">✓</span><div><small>Đã xác nhận</small><strong><?php echo e($counts->get('confirmed', 0)); ?></strong></div></div>
    </div>

    <div class="toolbar">
        <form method="GET" action="<?php echo e(route('admin.bookings.index')); ?>">
            <input class="field" type="search" name="search" value="<?php echo e($search); ?>" placeholder="Tìm tên, số điện thoại hoặc xe">
            <select class="select" name="status" aria-label="Lọc trạng thái">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" <?php if($status === 'pending'): echo 'selected'; endif; ?>>Chờ xác nhận</option>
                <option value="confirmed" <?php if($status === 'confirmed'): echo 'selected'; endif; ?>>Đã xác nhận</option>
                <option value="completed" <?php if($status === 'completed'): echo 'selected'; endif; ?>>Hoàn tất</option>
                <option value="cancelled" <?php if($status === 'cancelled'): echo 'selected'; endif; ?>>Đã hủy</option>
            </select>
            <button class="button secondary" type="submit">Lọc</button>
        </form>
        <span><?php echo e($bookings->total()); ?> lịch hẹn</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Khách hàng</th><th>Xe quan tâm</th><th>Thời gian hẹn</th><th>Ghi chú</th><th>Trạng thái</th><th>Cập nhật</th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><strong><?php echo e($booking->full_name); ?></strong><small><?php echo e($booking->phone); ?></small><?php if($booking->user): ?><small><?php echo e($booking->user->email); ?></small><?php endif; ?></td>
                    <td><?php echo e($booking->car?->name ?? 'Xe không còn trong danh sách'); ?></td>
                    <td><?php echo e($booking->scheduled_at->format('d/m/Y H:i')); ?></td>
                    <td><?php echo e($booking->note ?: '—'); ?></td>
                    <td><span class="badge <?php echo e($booking->status); ?>"><?php echo e(['pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'completed' => 'Hoàn tất', 'cancelled' => 'Đã hủy'][$booking->status]); ?></span></td>
                    <td><form class="inline-form" method="POST" action="<?php echo e(route('admin.bookings.update', $booking)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <select class="select" name="status" aria-label="Trạng thái lịch hẹn">
                            <option value="pending" <?php if($booking->status === 'pending'): echo 'selected'; endif; ?>>Chờ xác nhận</option>
                            <option value="confirmed" <?php if($booking->status === 'confirmed'): echo 'selected'; endif; ?>>Đã xác nhận</option>
                            <option value="completed" <?php if($booking->status === 'completed'): echo 'selected'; endif; ?>>Hoàn tất</option>
                            <option value="cancelled" <?php if($booking->status === 'cancelled'): echo 'selected'; endif; ?>>Đã hủy</option>
                        </select>
                        <button class="button small" type="submit">Lưu</button>
                    </form></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td class="empty" colspan="6"><?php echo e($search || $status ? 'Không tìm thấy lịch hẹn phù hợp.' : 'Chưa có lịch hẹn nào.'); ?></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($bookings->hasPages()): ?>
        <div class="pagination">
            <?php if($bookings->onFirstPage()): ?> <span>Trước</span> <?php else: ?> <a href="<?php echo e($bookings->previousPageUrl()); ?>">Trước</a> <?php endif; ?>
            <?php $__currentLoopData = $bookings->getUrlRange(max(1, $bookings->currentPage() - 2), min($bookings->lastPage(), $bookings->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($page === $bookings->currentPage()): ?> <span class="active"><?php echo e($page); ?></span> <?php else: ?> <a href="<?php echo e($url); ?>"><?php echo e($page); ?></a> <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($bookings->hasMorePages()): ?> <a href="<?php echo e($bookings->nextPageUrl()); ?>">Sau</a> <?php else: ?> <span>Sau</span> <?php endif; ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laravel-projects\Web-Ban-Xe\resources\views/admin/bookings/index.blade.php ENDPATH**/ ?>