<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * GET / — trang chủ, danh sách xe + tìm kiếm/lọc.
     * Cùng logic lọc như Api\CarController::index() nhưng trả về Blade thay vì JSON.
     */
    public function index(Request $request): View
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

        $cars = $query->paginate(9)->withQueryString();
        $categories = CarCategory::orderBy('name')->get();

        return view('home', [
            'cars' => $cars,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'min_price', 'max_price', 'sort']),
        ]);
    }
}