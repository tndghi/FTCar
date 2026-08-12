<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarCategory;
use App\Models\CarPart;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $sedan = CarCategory::create([
            'name' => 'Sedan',
            'slug' => 'sedan',
        ]);

        $car = Car::create([
            'car_category_id' => $sedan->id,
            'name' => 'Toyota Vios 2026',
            'slug' => 'toyota-vios-2026',
            'brand' => 'Toyota',
            'model' => 'Vios',
            'year' => 2026,
            'price' => 550000000,
            'description' => 'Sedan hạng B bán chạy, tiết kiệm nhiên liệu.',
            'thumbnail' => 'cars/vios-2026.jpg',
            'has_3d_model' => true,
            'model_3d_path' => 'models/toyota-vios-2026.glb',
            'status' => 'published',
            'stock' => 5,
        ]);

        // object_name PHẢI khớp chính xác với tên object trong file .glb
        $parts = [
            ['object_name' => 'wheel_front_left', 'label' => 'Bánh trước trái'],
            ['object_name' => 'wheel_front_right', 'label' => 'Bánh trước phải'],
            ['object_name' => 'headlight_left', 'label' => 'Đèn pha trái'],
            ['object_name' => 'headlight_right', 'label' => 'Đèn pha phải'],
            ['object_name' => 'mirror_left', 'label' => 'Gương chiếu hậu trái'],
            ['object_name' => 'mirror_right', 'label' => 'Gương chiếu hậu phải'],
        ];

        foreach ($parts as $part) {
            CarPart::create($part + ['car_id' => $car->id]);
        }
    }
}