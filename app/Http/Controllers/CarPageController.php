<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\View\View;

class CarPageController extends Controller
{
    /**
     * GET /xe/{slug} — render trang Blade có nhúng 3D viewer.
     * Khác với App\Http\Controllers\Api\CarController::show() (trả JSON cho API/AJAX),
     * controller này trả HTML, dữ liệu được nhúng thẳng vào Blade qua @json($car).
     */
    public function show(string $slug): View
    {
        $car = Car::query()
            ->published()
            ->with(['category', 'parts'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('cars.show', ['car' => $car]);
    }
}