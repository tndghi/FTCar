<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->value();
        $search = $request->string('search')->trim()->value();

        $bookings = Booking::query()
            ->with(['car:id,name,slug', 'user:id,name,email'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('full_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('car', fn ($carQuery) => $carQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('scheduled_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'status' => $status,
            'search' => $search,
            'counts' => Booking::query()
                ->selectRaw("status, count(*) as aggregate")
                ->groupBy('status')
                ->pluck('aggregate', 'status'),
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled', 'completed'])],
        ]);

        $booking->update($data);

        return back()->with('success', 'Đã cập nhật trạng thái lịch hẹn.');
    }
}