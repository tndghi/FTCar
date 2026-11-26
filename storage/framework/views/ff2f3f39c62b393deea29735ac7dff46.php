<?php $__env->startSection('title', $car->exists ? 'Sửa xe' : 'Thêm xe'); ?>
<?php $__env->startSection('section', 'Danh sách xe'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-heading">
        <div><h1><?php echo e($car->exists ? 'Sửa thông tin xe' : 'Thêm xe mới'); ?></h1><p>Thông tin này sẽ được dùng trên trang showroom.</p></div>
        <a class="button secondary" href="<?php echo e(route('admin.cars.index')); ?>">Quay lại</a>
    </div>

    <form class="form-panel" method="POST" enctype="multipart/form-data" action="<?php echo e($car->exists ? route('admin.cars.update', $car) : route('admin.cars.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php if($car->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
        <div class="form-grid">
            <div class="form-field"><label for="name">Tên xe *</label><input class="field" id="name" name="name" value="<?php echo e(old('name', $car->name)); ?>" required><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <div class="form-field"><label for="slug">Đường dẫn (slug) *</label><input class="field" id="slug" name="slug" value="<?php echo e(old('slug', $car->slug)); ?>" required><?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <div class="form-field"><label for="brand">Hãng xe *</label><input class="field" id="brand" name="brand" value="<?php echo e(old('brand', $car->brand)); ?>" required><?php $__errorArgs = ['brand'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <div class="form-field"><label for="model">Dòng xe</label><input class="field" id="model" name="model" value="<?php echo e(old('model', $car->model)); ?>"></div>
            <div class="form-field"><label for="car_category_id">Danh mục *</label><select class="select" id="car_category_id" name="car_category_id" required><option value="">Chọn danh mục</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->id); ?>" <?php if((string) old('car_category_id', $car->car_category_id) === (string) $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><div class="hint"><a href="<?php echo e(route('admin.categories.create')); ?>" target="_blank" rel="noopener">＋ Tạo danh mục mới</a></div><?php $__errorArgs = ['car_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <div class="form-field"><label for="year">Năm sản xuất</label><input class="field" id="year" type="number" name="year" min="1900" max="<?php echo e(now()->year + 2); ?>" value="<?php echo e(old('year', $car->year)); ?>"></div>
            <div class="form-field"><label for="price">Giá bán (đ) *</label><input class="field" id="price" type="number" name="price" min="0" step="1000" value="<?php echo e(old('price', $car->price)); ?>" required><?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <div class="form-field"><label for="stock">Số lượng tồn kho *</label><input class="field" id="stock" type="number" name="stock" min="0" value="<?php echo e(old('stock', $car->exists ? $car->stock : 0)); ?>" required></div>
            <div class="form-field"><label for="status">Trạng thái *</label><select class="select" id="status" name="status" required><option value="draft" <?php if(old('status', $car->status ?: 'draft') === 'draft'): echo 'selected'; endif; ?>>Bản nháp</option><option value="published" <?php if(old('status', $car->status) === 'published'): echo 'selected'; endif; ?>>Đang bán</option><option value="sold_out" <?php if(old('status', $car->status) === 'sold_out'): echo 'selected'; endif; ?>>Hết hàng</option></select></div>
            <div class="form-field"><label for="thumbnail">Ảnh đại diện</label><input class="field" id="thumbnail" type="file" name="thumbnail" accept="image/*"><div class="hint">JPG, PNG hoặc WebP. Tối đa 5 MB.</div><?php if($car->thumbnail): ?><div class="current-file">Ảnh hiện tại: <?php echo e(basename($car->thumbnail)); ?></div><?php endif; ?> <?php $__errorArgs = ['thumbnail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <div class="form-field"><label for="model_3d">Mô hình 3D (.glb)</label><input class="field" id="model_3d" type="file" name="model_3d" accept=".glb,model/gltf-binary"><div class="hint">Chỉ nhận file GLB, tối đa 75 MB.</div><?php if($car->model_3d_path): ?><div class="current-file">Đang dùng: <?php echo e(basename($car->model_3d_path)); ?></div><label class="check-row"><input type="checkbox" name="remove_model_3d" value="1"> Gỡ mô hình 3D hiện tại</label><?php endif; ?> <?php $__errorArgs = ['model_3d'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <div class="form-field full"><label for="description">Mô tả</label><textarea id="description" name="description"><?php echo e(old('description', $car->description)); ?></textarea><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
            <?php if($car->exists): ?>
                <?php ($partRows = old('parts', $car->parts->toArray())); ?>
                <div class="form-field full parts-editor">
                    <input type="hidden" name="manage_parts" value="1">
                    <h2>Thông tin bộ phận 3D</h2>
                    <p>Click bộ phận trên trang chi tiết để xem tên object trong GLB, sau đó nhập đúng tên tại đây. Ví dụ: mirror, wheel hoặc engine. Lưu tên, nhãn và mô tả riêng cho từng mesh.</p>
                    <div class="parts-list" id="parts-list" data-next-index="<?php echo e(count($partRows)); ?>">
                        <?php $__currentLoopData = $partRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="part-row">
                                <div><label for="part-object-<?php echo e($index); ?>">Tên object trong GLB *</label><input class="field" id="part-object-<?php echo e($index); ?>" name="parts[<?php echo e($index); ?>][object_name]" value="<?php echo e($part['object_name'] ?? ''); ?>" required placeholder="Object 130"></div>
                                <div><label for="part-label-<?php echo e($index); ?>">Tên hiển thị *</label><input class="field" id="part-label-<?php echo e($index); ?>" name="parts[<?php echo e($index); ?>][label]" value="<?php echo e($part['label'] ?? ''); ?>" required placeholder="Gương chiếu hậu"></div>
                                <div class="description-field"><label for="part-description-<?php echo e($index); ?>">Mô tả</label><input class="field" id="part-description-<?php echo e($index); ?>" name="parts[<?php echo e($index); ?>][description]" value="<?php echo e($part['description'] ?? ''); ?>" placeholder="Mô tả bộ phận"></div>
                                <div><label for="part-price-<?php echo e($index); ?>">Phụ phí (đ)</label><input class="field" id="part-price-<?php echo e($index); ?>" name="parts[<?php echo e($index); ?>][extra_price]" type="number" min="0" step="1000" value="<?php echo e($part['extra_price'] ?? ''); ?>" placeholder="Không bắt buộc"></div>
                                <button class="remove-part" type="button" aria-label="Xóa bộ phận">Xóa</button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <template id="part-row-template">
                        <div class="part-row">
                            <div><label for="part-object-__INDEX__">Tên object trong GLB *</label><input class="field" id="part-object-__INDEX__" name="parts[__INDEX__][object_name]" required placeholder="Object 130"></div>
                            <div><label for="part-label-__INDEX__">Tên hiển thị *</label><input class="field" id="part-label-__INDEX__" name="parts[__INDEX__][label]" required placeholder="Gương chiếu hậu"></div>
                            <div class="description-field"><label for="part-description-__INDEX__">Mô tả</label><input class="field" id="part-description-__INDEX__" name="parts[__INDEX__][description]" placeholder="Mô tả bộ phận"></div>
                            <div><label for="part-price-__INDEX__">Phụ phí (đ)</label><input class="field" id="part-price-__INDEX__" name="parts[__INDEX__][extra_price]" type="number" min="0" step="1000" placeholder="Không bắt buộc"></div>
                            <button class="remove-part" type="button" aria-label="Xóa bộ phận">Xóa</button>
                        </div>
                    </template>
                    <button class="button secondary small" id="add-part" type="button">＋ Thêm bộ phận</button>
                    <?php $__errorArgs = ['parts.*.object_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['parts.*.label'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-actions"><a class="button secondary" href="<?php echo e(route('admin.cars.index')); ?>">Hủy</a><button class="button" type="submit"><?php echo e($car->exists ? 'Lưu thay đổi' : 'Thêm xe'); ?></button></div>
    </form>
    <?php if($car->exists): ?>
        <script>
            const partsList = document.getElementById('parts-list');
            const partTemplate = document.getElementById('part-row-template');
            let nextPartIndex = Number(partsList.dataset.nextIndex);

            document.getElementById('add-part').addEventListener('click', () => {
                partsList.insertAdjacentHTML('beforeend', partTemplate.innerHTML.replaceAll('__INDEX__', nextPartIndex++));
            });
            partsList.addEventListener('click', (event) => {
                if (event.target.closest('.remove-part')) event.target.closest('.part-row').remove();
            });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laravel-projects\Web-Ban-Xe\resources\views/admin/cars/form.blade.php ENDPATH**/ ?>