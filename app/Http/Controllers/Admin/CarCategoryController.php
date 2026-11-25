<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CarCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => CarCategory::query()
                ->withCount(['cars' => fn ($query) => $query->withTrashed()])
                ->orderBy('name')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', ['category' => new CarCategory()]);
    }

    public function store(Request $request): RedirectResponse
    {
        CarCategory::create($this->validatedData($request));

        return redirect()->route('admin.categories.index')->with('success', 'Đã thêm danh mục.');
    }

    public function edit(CarCategory $category): View
    {
        return view('admin.categories.form', ['category' => $category]);
    }

    public function update(Request $request, CarCategory $category): RedirectResponse
    {
        $category->update($this->validatedData($request, $category));

        return redirect()->route('admin.categories.index')->with('success', 'Đã cập nhật danh mục.');
    }

    public function destroy(CarCategory $category): RedirectResponse
    {
        if ($category->cars()->withTrashed()->exists()) {
            return back()->with('error', 'Danh mục đang có xe sử dụng nên không thể xóa.');
        }

        $category->delete();

        return back()->with('success', 'Đã xóa danh mục.');
    }

    private function validatedData(Request $request, ?CarCategory $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);

        Validator::make($data, [
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('car_categories', 'slug')->ignore($category)],
        ])->validate();

        return $data;
    }
}