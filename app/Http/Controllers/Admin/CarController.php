<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CarController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();
        $cars = Car::query()
            ->with('category')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.cars.index', [
            'cars' => $cars,
            'search' => $search,
            'stats' => [
                'total' => Car::count(),
                'published' => Car::where('status', 'published')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.cars.form', [
            'car' => new Car(),
            'categories' => CarCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['thumbnail'] = $request->file('thumbnail')?->store('cars', 'public');
        $data['model_3d_path'] = $request->file('model_3d')?->store('models', 'public');
        $data['has_3d_model'] = filled($data['model_3d_path']);

        Car::create($data);

        return redirect()->route('admin.cars.index')->with('success', 'Đã thêm xe mới.');
    }

    public function edit(Car $car): View
    {
        $car->load('parts');

        return view('admin.cars.form', [
            'car' => $car,
            'categories' => CarCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Car $car): RedirectResponse
    {
        $data = $this->validatedData($request, $car);
        $parts = null;

        if ($request->boolean('manage_parts')) {
            $parts = $request->validate([
                'parts' => ['nullable', 'array', 'max:40'],
                'parts.*.object_name' => ['required', 'string', 'max:255', 'distinct'],
                'parts.*.label' => ['required', 'string', 'max:255'],
                'parts.*.description' => ['nullable', 'string', 'max:1000'],
                'parts.*.extra_price' => ['nullable', 'numeric', 'min:0'],
            ])['parts'] ?? [];
        }

        $oldThumbnail = $car->thumbnail;
        $oldModel = $car->model_3d_path;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('cars', 'public');
        }

        if ($request->hasFile('model_3d')) {
            $data['model_3d_path'] = $request->file('model_3d')->store('models', 'public');
        } elseif ($request->boolean('remove_model_3d')) {
            $data['model_3d_path'] = null;
        } else {
            $data['model_3d_path'] = $oldModel;
        }

        $data['has_3d_model'] = filled($data['model_3d_path']);
        $car->update($data);

        if ($parts !== null) {
            $car->parts()->delete();
            foreach ($parts as $part) {
                $car->parts()->create($part);
            }
        }

        if ($request->hasFile('thumbnail') && $oldThumbnail) {
            Storage::disk('public')->delete($oldThumbnail);
        }
        if ($oldModel && $oldModel !== $data['model_3d_path']) {
            Storage::disk('public')->delete($oldModel);
        }

        return redirect()->route('admin.cars.index')->with('success', 'Đã cập nhật thông tin xe.');
    }

    public function destroy(Car $car): RedirectResponse
    {
        $car->delete();

        return back()->with('success', 'Đã xóa xe khỏi danh sách quản lý.');
    }

    private function validatedData(Request $request, ?Car $car = null): array
    {
        $data = $request->validate([
            'car_category_id' => ['required', 'exists:car_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('cars', 'slug')->ignore($car)],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'between:1900,'.(now()->year + 2)],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:10000'],
            'thumbnail' => ['nullable', 'image', 'max:5120'],
            'model_3d' => ['nullable', 'file', 'extensions:glb', 'max:76800'],
            'status' => ['required', Rule::in(['draft', 'published', 'sold_out'])],
            'stock' => ['required', 'integer', 'min:0'],
            'remove_model_3d' => ['sometimes', 'boolean'],
        ]);

        unset($data['model_3d'], $data['remove_model_3d']);

        return $data;
    }
}