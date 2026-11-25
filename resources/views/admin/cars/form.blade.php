@extends('admin.layout')

@section('title', $car->exists ? 'Sửa xe' : 'Thêm xe')
@section('section', 'Danh sách xe')

@section('content')
    <div class="page-heading">
        <div><h1>{{ $car->exists ? 'Sửa thông tin xe' : 'Thêm xe mới' }}</h1><p>Thông tin này sẽ được dùng trên trang showroom.</p></div>
        <a class="button secondary" href="{{ route('admin.cars.index') }}">Quay lại</a>
    </div>

    <form class="form-panel" method="POST" enctype="multipart/form-data" action="{{ $car->exists ? route('admin.cars.update', $car) : route('admin.cars.store') }}">
        @csrf
        @if ($car->exists) @method('PUT') @endif
        <div class="form-grid">
            <div class="form-field"><label for="name">Tên xe *</label><input class="field" id="name" name="name" value="{{ old('name', $car->name) }}" required>@error('name')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label for="slug">Đường dẫn (slug) *</label><input class="field" id="slug" name="slug" value="{{ old('slug', $car->slug) }}" required>@error('slug')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label for="brand">Hãng xe *</label><input class="field" id="brand" name="brand" value="{{ old('brand', $car->brand) }}" required>@error('brand')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label for="model">Dòng xe</label><input class="field" id="model" name="model" value="{{ old('model', $car->model) }}"></div>
            <div class="form-field"><label for="car_category_id">Danh mục *</label><select class="select" id="car_category_id" name="car_category_id" required><option value="">Chọn danh mục</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected((string) old('car_category_id', $car->car_category_id) === (string) $category->id)>{{ $category->name }}</option>@endforeach</select><div class="hint"><a href="{{ route('admin.categories.create') }}" target="_blank" rel="noopener">＋ Tạo danh mục mới</a></div>@error('car_category_id')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label for="year">Năm sản xuất</label><input class="field" id="year" type="number" name="year" min="1900" max="{{ now()->year + 2 }}" value="{{ old('year', $car->year) }}"></div>
            <div class="form-field"><label for="price">Giá bán (đ) *</label><input class="field" id="price" type="number" name="price" min="0" step="1000" value="{{ old('price', $car->price) }}" required>@error('price')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label for="stock">Số lượng tồn kho *</label><input class="field" id="stock" type="number" name="stock" min="0" value="{{ old('stock', $car->exists ? $car->stock : 0) }}" required></div>
            <div class="form-field"><label for="status">Trạng thái *</label><select class="select" id="status" name="status" required><option value="draft" @selected(old('status', $car->status ?: 'draft') === 'draft')>Bản nháp</option><option value="published" @selected(old('status', $car->status) === 'published')>Đang bán</option><option value="sold_out" @selected(old('status', $car->status) === 'sold_out')>Hết hàng</option></select></div>
            <div class="form-field"><label for="thumbnail">Ảnh đại diện</label><input class="field" id="thumbnail" type="file" name="thumbnail" accept="image/*"><div class="hint">JPG, PNG hoặc WebP. Tối đa 5 MB.</div>@if ($car->thumbnail)<div class="current-file">Ảnh hiện tại: {{ basename($car->thumbnail) }}</div>@endif @error('thumbnail')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label for="model_3d">Mô hình 3D (.glb)</label><input class="field" id="model_3d" type="file" name="model_3d" accept=".glb,model/gltf-binary"><div class="hint">Chỉ nhận file GLB, tối đa 75 MB.</div>@if ($car->model_3d_path)<div class="current-file">Đang dùng: {{ basename($car->model_3d_path) }}</div><label class="check-row"><input type="checkbox" name="remove_model_3d" value="1"> Gỡ mô hình 3D hiện tại</label>@endif @error('model_3d')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field full"><label for="description">Mô tả</label><textarea id="description" name="description">{{ old('description', $car->description) }}</textarea>@error('description')<div class="error">{{ $message }}</div>@enderror</div>
            @if ($car->exists)
                @php($partRows = old('parts', $car->parts->toArray()))
                <div class="form-field full parts-editor">
                    <input type="hidden" name="manage_parts" value="1">
                    <h2>Thông tin bộ phận 3D</h2>
                    <p>Click bộ phận trên trang chi tiết để xem tên object trong GLB, sau đó nhập đúng tên tại đây. Ví dụ: mirror, wheel hoặc engine. Lưu tên, nhãn và mô tả riêng cho từng mesh.</p>
                    <div class="parts-list" id="parts-list" data-next-index="{{ count($partRows) }}">
                        @foreach ($partRows as $index => $part)
                            <div class="part-row">
                                <div><label for="part-object-{{ $index }}">Tên object trong GLB *</label><input class="field" id="part-object-{{ $index }}" name="parts[{{ $index }}][object_name]" value="{{ $part['object_name'] ?? '' }}" required placeholder="Object 130"></div>
                                <div><label for="part-label-{{ $index }}">Tên hiển thị *</label><input class="field" id="part-label-{{ $index }}" name="parts[{{ $index }}][label]" value="{{ $part['label'] ?? '' }}" required placeholder="Gương chiếu hậu"></div>
                                <div class="description-field"><label for="part-description-{{ $index }}">Mô tả</label><input class="field" id="part-description-{{ $index }}" name="parts[{{ $index }}][description]" value="{{ $part['description'] ?? '' }}" placeholder="Mô tả bộ phận"></div>
                                <div><label for="part-price-{{ $index }}">Phụ phí (đ)</label><input class="field" id="part-price-{{ $index }}" name="parts[{{ $index }}][extra_price]" type="number" min="0" step="1000" value="{{ $part['extra_price'] ?? '' }}" placeholder="Không bắt buộc"></div>
                                <button class="remove-part" type="button" aria-label="Xóa bộ phận">Xóa</button>
                            </div>
                        @endforeach
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
                    @error('parts.*.object_name')<div class="error">{{ $message }}</div>@enderror
                    @error('parts.*.label')<div class="error">{{ $message }}</div>@enderror
                </div>
            @endif
        </div>
        <div class="form-actions"><a class="button secondary" href="{{ route('admin.cars.index') }}">Hủy</a><button class="button" type="submit">{{ $car->exists ? 'Lưu thay đổi' : 'Thêm xe' }}</button></div>
    </form>
    @if ($car->exists)
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
    @endif
@endsection