

<?php $__env->startSection('title', $category->exists ? 'Sửa danh mục' : 'Thêm danh mục'); ?>
<?php $__env->startSection('section', 'Danh mục xe'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-heading">
        <div><h1><?php echo e($category->exists ? 'Sửa danh mục' : 'Thêm danh mục'); ?></h1><p>Đặt tên và đường dẫn cho nhóm xe.</p></div>
        <a class="button secondary" href="<?php echo e(route('admin.categories.index')); ?>">Quay lại</a>
    </div>

    <form class="form-panel" method="POST" action="<?php echo e($category->exists ? route('admin.categories.update', $category) : route('admin.categories.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php if($category->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
        <div class="form-grid">
            <div class="form-field"><label for="name">Tên danh mục *</label><input class="field" id="name" name="name" value="<?php echo e(old('name', $category->name)); ?>" required autofocus placeholder="Ví dụ: SUV"><div class="hint">Tên hiển thị trong trang showroom và form xe.</div><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <div class="form-field"><label for="slug">Đường dẫn</label><input class="field" id="slug" name="slug" value="<?php echo e(old('slug', $category->slug)); ?>" placeholder="Tự tạo từ tên danh mục"><div class="hint">Để trống để tự tạo, ví dụ “Xe bán tải” thành “xe-ban-tai”.</div><?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <div class="form-field full"><label for="description">Mô tả</label><textarea id="description" name="description" placeholder="Mô tả ngắn về nhóm xe"><?php echo e(old('description', $category->description)); ?></textarea><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
        </div>
        <div class="form-actions"><a class="button secondary" href="<?php echo e(route('admin.categories.index')); ?>">Hủy</a><button class="button" type="submit"><?php echo e($category->exists ? 'Lưu thay đổi' : 'Thêm danh mục'); ?></button></div>
    </form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laravel-projects\Web-Ban-Xe\resources\views/admin/categories/form.blade.php ENDPATH**/ ?>