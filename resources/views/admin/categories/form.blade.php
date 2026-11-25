@extends('admin.layout')

@section('title', $category->exists ? 'Sửa danh mục' : 'Thêm danh mục')
@section('section', 'Danh mục xe')

@section('content')
    <div class="page-heading">
        <div><h1>{{ $category->exists ? 'Sửa danh mục' : 'Thêm danh mục' }}</h1><p>Đặt tên và đường dẫn cho nhóm xe.</p></div>
        <a class="button secondary" href="{{ route('admin.categories.index') }}">Quay lại</a>
    </div>

    <form class="form-panel" method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
        @csrf
        @if ($category->exists) @method('PUT') @endif
        <div class="form-grid">
            <div class="form-field"><label for="name">Tên danh mục *</label><input class="field" id="name" name="name" value="{{ old('name', $category->name) }}" required autofocus placeholder="Ví dụ: SUV"><div class="hint">Tên hiển thị trong trang showroom và form xe.</div>@error('name')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label for="slug">Đường dẫn</label><input class="field" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" placeholder="Tự tạo từ tên danh mục"><div class="hint">Để trống để tự tạo, ví dụ “Xe bán tải” thành “xe-ban-tai”.</div>@error('slug')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field full"><label for="description">Mô tả</label><textarea id="description" name="description" placeholder="Mô tả ngắn về nhóm xe">{{ old('description', $category->description) }}</textarea>@error('description')<div class="error">{{ $message }}</div>@enderror</div>
        </div>
        <div class="form-actions"><a class="button secondary" href="{{ route('admin.categories.index') }}">Hủy</a><button class="button" type="submit">{{ $category->exists ? 'Lưu thay đổi' : 'Thêm danh mục' }}</button></div>
    </form>
@endsection