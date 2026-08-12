<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * GET /api/bookings (yêu cầu đăng nhập) — lịch sử đặt lịch của user
     */
    public function index(Request $request)
    {
        $bookings = Booking::query()
            ->where('user_id', $request->user()->id)
            ->with('car:id,name,slug,thumbnail')
            ->latest()
            ->paginate(10);

        return response()->json($bookings);
    }

    /**
     * POST /api/bookings — đặt lịch xem xe
     */
    public function store(StoreBookingRequest $request)
    {
        $booking = Booking::create([
            ...$request->validated(),
            'user_id' => Auth::id(), // null nếu khách chưa đăng nhập
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Đặt lịch thành công, chúng tôi sẽ liên hệ xác nhận sớm.',
            'booking' => $booking,
        ], 201);
    }
}