@extends('admin.layout')

@section('title', 'Quản lý xe')
@section('section', 'Danh sách xe')

@section('content')
    <div class="page-heading">
        <div><h1>Danh sách xe</h1><p>Quản lý thông tin, trạng thái và mô hình 3D của xe.</p></div>
        <a class="button" href="{{ route('admin.cars.create') }}"><span aria-hidden="true">＋</span> Thêm xe</a>
    </div>

    <div class="stats">
        <div class="stat"><span class="stat-mark">▣</span><div><small>Tổng số xe</small><strong>{{ number_format($stats['total']) }}</strong></div></div>
        <div class="stat"><span class="stat-mark">●</span><div><small>Đang hiển thị</small><strong>{{ number_format($stats['published']) }}</strong></div></div>
    </div>

    <div class="toolbar">
        <form method="GET" action="{{ route('admin.cars.index') }}">
            <input class="field" type="search" name="search" value="{{ $search }}" placeholder="Tìm theo tên xe, hãng hoặc dòng xe">
            <button class="button secondary" type="submit">Tìm kiếm</button>
        </form>
        <span>{{ $cars->total() }} xe</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Xe</th><th>Danh mục</th><th>Giá bán</th><th>Tồn kho</th><th>Trạng thái</th><th>Mô hình</th><th>Thao tác</th></tr></thead>
            <tbody>
            @forelse ($cars as $car)
                <tr>
                    <td><div class="vehicle">
                        @if ($car->thumbnail)<img src="{{ asset('storage/'.$car->thumbnail) }}" alt="{{ $car->name }}">@else<span class="vehicle-placeholder">Chưa có ảnh</span>@endif
                        <div><strong>{{ $car->name }}</strong><small>{{ $car->brand }}{{ $car->model ? ' · '.$car->model : '' }}{{ $car->year ? ' · '.$car->year : '' }}</small></div>
                    </div></td>
                    <td>{{ $car->category?->name ?? '—' }}</td>
                    <td class="money">{{ number_format($car->price, 0, ',', '.') }} đ</td>
                    <td>{{ $car->stock }}</td>
                    <td><span class="badge {{ $car->status }}">{{ ['draft' => 'Bản nháp', 'published' => 'Đang bán', 'sold_out' => 'Hết hàng'][$car->status] }}</span></td>
                    <td>{{ $car->has_3d_model ? 'GLB' : 'Chưa có' }}</td>
                    <td><div class="actions">
                        <a class="button secondary small" href="{{ route('admin.cars.edit', $car) }}">Sửa</a>
                        <form method="POST" action="{{ route('admin.cars.destroy', $car) }}" onsubmit="return confirm('Xóa xe này khỏi danh sách?')">
                            @csrf @method('DELETE')
                            <button class="button danger small" type="submit">Xóa</button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td class="empty" colspan="7">{{ $search ? 'Không tìm thấy xe phù hợp.' : 'Chưa có xe trong danh sách.' }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($cars->hasPages())
        <div class="pagination">
            @if ($cars->onFirstPage()) <span>Trước</span> @else <a href="{{ $cars->previousPageUrl() }}">Trước</a> @endif
            @foreach ($cars->getUrlRange(max(1, $cars->currentPage() - 2), min($cars->lastPage(), $cars->currentPage() + 2)) as $page => $url)
                @if ($page === $cars->currentPage()) <span class="active">{{ $page }}</span> @else <a href="{{ $url }}">{{ $page }}</a> @endif
            @endforeach
            @if ($cars->hasMorePages()) <a href="{{ $cars->nextPageUrl() }}">Sau</a> @else <span>Sau</span> @endif
        </div>
    @endif
@endsection