<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * GET /api/cars
     * Query params hỗ trợ:
     *   ?search=vios          tìm theo tên/hãng/model
     *   ?category=sedan       lọc theo slug danh mục
     *   ?brand=Toyota         lọc theo hãng
     *   ?min_price=100000000  &max_price=800000000
     *   ?sort=price_asc | price_desc | newest
     *   ?per_page=12
     */
    public function index(Request $request)
    {
        $query = Car::query()
            ->published()
            ->with('category');

        if ($search = $request->string('search')->trim()->value()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($category = $request->string('category')->value()) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        if ($brand = $request->string('brand')->value()) {
            $query->where('brand', $brand);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        match ($request->string('sort')->value()) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default => $query->latest(),
        };

        $perPage = min((int) $request->input('per_page', 12), 50);

        return CarResource::collection($query->paginate($perPage));
    }

    /**
     * GET /api/cars/{slug}
     * Trả kèm parts (dùng cho 3D viewer raycasting)
     */
    public function show(string $slug)
    {
        $car = Car::query()
            ->published()
            ->with(['category', 'parts'])
            ->where('slug', $slug)
            ->firstOrFail();

        return new CarResource($car);
    }
}