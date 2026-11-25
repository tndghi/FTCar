@extends('admin.layout')

@section('title', 'Lịch hẹn xem xe')
@section('section', 'Lịch hẹn')

@section('content')
    <div class="page-heading"><div><h1>Lịch hẹn xem xe</h1><p>Theo dõi và cập nhật trạng thái các yêu cầu từ khách hàng.</p></div></div>

    <div class="stats">
        <div class="stat"><span class="stat-mark">◷</span><div><small>Chờ xác nhận</small><strong>{{ $counts->get('pending', 0) }}</strong></div></div>
        <div class="stat"><span class="stat-mark">✓</span><div><small>Đã xác nhận</small><strong>{{ $counts->get('confirmed', 0) }}</strong></div></div>
    </div>

    <div class="toolbar">
        <form method="GET" action="{{ route('admin.bookings.index') }}">
            <input class="field" type="search" name="search" value="{{ $search }}" placeholder="Tìm tên, số điện thoại hoặc xe">
            <select class="select" name="status" aria-label="Lọc trạng thái">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" @selected($status === 'pending')>Chờ xác nhận</option>
                <option value="confirmed" @selected($status === 'confirmed')>Đã xác nhận</option>
                <option value="completed" @selected($status === 'completed')>Hoàn tất</option>
                <option value="cancelled" @selected($status === 'cancelled')>Đã hủy</option>
            </select>
            <button class="button secondary" type="submit">Lọc</button>
        </form>
        <span>{{ $bookings->total() }} lịch hẹn</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Khách hàng</th><th>Xe quan tâm</th><th>Thời gian hẹn</th><th>Ghi chú</th><th>Trạng thái</th><th>Cập nhật</th></tr></thead>
            <tbody>
            @forelse ($bookings as $booking)
                <tr>
                    <td><strong>{{ $booking->full_name }}</strong><small>{{ $booking->phone }}</small>@if ($booking->user)<small>{{ $booking->user->email }}</small>@endif</td>
                    <td>{{ $booking->car?->name ?? 'Xe không còn trong danh sách' }}</td>
                    <td>{{ $booking->scheduled_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $booking->note ?: '—' }}</td>
                    <td><span class="badge {{ $booking->status }}">{{ ['pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'completed' => 'Hoàn tất', 'cancelled' => 'Đã hủy'][$booking->status] }}</span></td>
                    <td><form class="inline-form" method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                        @csrf @method('PATCH')
                        <select class="select" name="status" aria-label="Trạng thái lịch hẹn">
                            <option value="pending" @selected($booking->status === 'pending')>Chờ xác nhận</option>
                            <option value="confirmed" @selected($booking->status === 'confirmed')>Đã xác nhận</option>
                            <option value="completed" @selected($booking->status === 'completed')>Hoàn tất</option>
                            <option value="cancelled" @selected($booking->status === 'cancelled')>Đã hủy</option>
                        </select>
                        <button class="button small" type="submit">Lưu</button>
                    </form></td>
                </tr>
            @empty
                <tr><td class="empty" colspan="6">{{ $search || $status ? 'Không tìm thấy lịch hẹn phù hợp.' : 'Chưa có lịch hẹn nào.' }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($bookings->hasPages())
        <div class="pagination">
            @if ($bookings->onFirstPage()) <span>Trước</span> @else <a href="{{ $bookings->previousPageUrl() }}">Trước</a> @endif
            @foreach ($bookings->getUrlRange(max(1, $bookings->currentPage() - 2), min($bookings->lastPage(), $bookings->currentPage() + 2)) as $page => $url)
                @if ($page === $bookings->currentPage()) <span class="active">{{ $page }}</span> @else <a href="{{ $url }}">{{ $page }}</a> @endif
            @endforeach
            @if ($bookings->hasMorePages()) <a href="{{ $bookings->nextPageUrl() }}">Sau</a> @else <span>Sau</span> @endif
        </div>
    @endif
@endsection