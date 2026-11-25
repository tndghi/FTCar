@extends('admin.layout')

@section('title', 'Danh mục xe')
@section('section', 'Danh mục xe')

@section('content')
    <div class="page-heading">
        <div><h1>Danh mục xe</h1><p>Tạo nhóm xe để phân loại trên trang showroom.</p></div>
        <a class="button" href="{{ route('admin.categories.create') }}"><span aria-hidden="true">＋</span> Thêm danh mục</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Tên danh mục</th><th>Đường dẫn</th><th>Mô tả</th><th>Số xe</th><th>Thao tác</th></tr></thead>
            <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->description ?: '—' }}</td>
                    <td>{{ $category->cars_count }}</td>
                    <td><div class="actions">
                        <a class="button secondary small" href="{{ route('admin.categories.edit', $category) }}">Sửa</a>
                        @if ($category->cars_count === 0)
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Xóa danh mục này?')">
                                @csrf @method('DELETE')
                                <button class="button danger small" type="submit">Xóa</button>
                            </form>
                        @else
                            <span class="hint">Đang được sử dụng</span>
                        @endif
                    </div></td>
                </tr>
            @empty
                <tr><td class="empty" colspan="5">Chưa có danh mục. Thêm Sedan, SUV hoặc loại xe bạn muốn demo.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if ($categories->hasPages())
        <div class="pagination">
            @if ($categories->onFirstPage()) <span>Trước</span> @else <a href="{{ $categories->previousPageUrl() }}">Trước</a> @endif
            @foreach ($categories->getUrlRange(max(1, $categories->currentPage() - 2), min($categories->lastPage(), $categories->currentPage() + 2)) as $page => $url)
                @if ($page === $categories->currentPage()) <span class="active">{{ $page }}</span> @else <a href="{{ $url }}">{{ $page }}</a> @endif
            @endforeach
            @if ($categories->hasMorePages()) <a href="{{ $categories->nextPageUrl() }}">Sau</a> @else <span>Sau</span> @endif
        </div>
    @endif
@endsection