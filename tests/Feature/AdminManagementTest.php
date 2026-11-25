<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Car;
use App\Models\CarCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_require_an_admin_session(): void
    {
        $this->get(route('admin.cars.index'))
            ->assertRedirect(route('admin.login'));

        $this->actingAs(User::factory()->create(['role' => 'customer']))
            ->get(route('admin.cars.index'))
            ->assertForbidden();
    }

    public function test_admin_can_log_in_and_upload_a_glb_car_model(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'password']);
        $category = CarCategory::create(['name' => 'SUV', 'slug' => 'suv']);

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.cars.index'));

        $this->post(route('admin.cars.store'), [
            'car_category_id' => $category->id,
            'name' => 'Test SUV',
            'slug' => 'test-suv',
            'brand' => 'Test Motors',
            'model' => 'S1',
            'year' => 2026,
            'price' => 500000000,
            'status' => 'published',
            'stock' => 2,
            'model_3d' => UploadedFile::fake()->create('test.glb', 100, 'model/gltf-binary'),
        ])->assertRedirect(route('admin.cars.index'));

        $car = Car::where('slug', 'test-suv')->firstOrFail();
        $this->assertTrue($car->has_3d_model);
        $this->assertTrue(Storage::disk('public')->exists($car->model_3d_path));
    }

    public function test_admin_can_create_and_edit_categories_with_a_slug_from_the_name(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.categories.index'))
            ->assertOk();

        $this->post(route('admin.categories.store'), [
            'name' => 'Xe bán tải',
            'slug' => '',
            'description' => 'Xe đa dụng',
        ])
            ->assertRedirect(route('admin.categories.index'));

        $category = CarCategory::where('name', 'Xe bán tải')->firstOrFail();
        $this->assertSame('xe-ban-tai', $category->slug);

        $this->get(route('admin.cars.create'))
            ->assertOk()
            ->assertSee('Xe bán tải');

        $this->get(route('admin.categories.edit', $category))->assertOk();

        $this->put(route('admin.categories.update', $category), [
            'name' => 'Xe địa hình',
            'slug' => '',
            'description' => 'Xe gầm cao',
        ])->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('car_categories', [
            'id' => $category->id,
            'name' => 'Xe địa hình',
            'slug' => 'xe-dia-hinh',
        ]);
    }

    public function test_category_in_use_by_a_car_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = CarCategory::create(['name' => 'SUV', 'slug' => 'suv']);
        Car::create([
            'car_category_id' => $category->id,
            'name' => 'Demo SUV',
            'slug' => 'demo-suv',
            'brand' => 'Demo',
            'price' => 100000000,
            'status' => 'published',
            'stock' => 1,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('car_categories', ['id' => $category->id]);
    }

    public function test_category_with_a_soft_deleted_car_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = CarCategory::create(['name' => 'Sedan', 'slug' => 'sedan']);
        $car = Car::create([
            'car_category_id' => $category->id,
            'name' => 'Archived Sedan',
            'slug' => 'archived-sedan',
            'brand' => 'Demo',
            'price' => 100000000,
            'status' => 'draft',
            'stock' => 0,
        ]);
        $car->delete();

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('cars', ['id' => $car->id]);
        $this->assertDatabaseHas('car_categories', ['id' => $category->id]);
    }

    public function test_guest_can_create_a_booking_with_a_note(): void
    {
        $category = CarCategory::create(['name' => 'SUV', 'slug' => 'suv']);
        $car = Car::create([
            'car_category_id' => $category->id,
            'name' => 'Demo SUV',
            'slug' => 'demo-suv-booking',
            'brand' => 'Demo',
            'price' => 100000000,
            'status' => 'published',
            'stock' => 1,
        ]);

        $this->postJson('/api/bookings', [
            'car_id' => $car->id,
            'scheduled_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'full_name' => 'Guest Customer',
            'phone' => '0900000000',
            'note' => 'Muốn xem xe vào buổi chiều.',
        ])->assertCreated()
            ->assertJsonPath('booking.note', 'Muốn xem xe vào buổi chiều.');

        $this->assertDatabaseHas('bookings', [
            'car_id' => $car->id,
            'user_id' => null,
            'note' => 'Muốn xem xe vào buổi chiều.',
        ]);
    }

    public function test_admin_can_map_a_glb_mesh_to_a_car_part_description(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = CarCategory::create(['name' => 'Coupe', 'slug' => 'coupe']);
        $car = Car::create([
            'car_category_id' => $category->id,
            'name' => 'Demo Coupe',
            'slug' => 'demo-coupe',
            'brand' => 'Demo',
            'price' => 100000000,
            'status' => 'published',
            'stock' => 1,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.cars.update', $car), [
                'car_category_id' => $category->id,
                'name' => $car->name,
                'slug' => $car->slug,
                'brand' => $car->brand,
                'model' => '',
                'year' => '',
                'price' => $car->price,
                'description' => 'Mô tả tổng thể chiếc xe.',
                'status' => 'published',
                'stock' => 1,
                'manage_parts' => 1,
                'parts' => [[
                    'object_name' => 'Object 130',
                    'label' => 'Gương chiếu hậu',
                    'description' => 'Gương chỉnh điện.',
                    'extra_price' => '',
                ]],
            ])->assertRedirect(route('admin.cars.index'));

        $this->assertDatabaseHas('car_parts', [
            'car_id' => $car->id,
            'object_name' => 'Object 130',
            'label' => 'Gương chiếu hậu',
            'description' => 'Gương chỉnh điện.',
        ]);

        $this->get(route('admin.cars.edit', $car))
            ->assertOk()
            ->assertSee('Thông tin bộ phận 3D')
            ->assertSee('Object 130');

        $this->get(route('cars.show', $car->slug))
            ->assertOk()
            ->assertSee('Object 130')
            ->assertSee('Gương chiếu hậu');
    }

    public function test_admin_can_update_a_booking_status_and_guest_bookings_are_supported(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = CarCategory::create(['name' => 'Sedan', 'slug' => 'sedan']);
        $car = Car::create([
            'car_category_id' => $category->id,
            'name' => 'Demo Sedan',
            'slug' => 'demo-sedan',
            'brand' => 'Demo',
            'price' => 100000000,
            'status' => 'published',
            'stock' => 1,
        ]);
        $booking = Booking::create([
            'user_id' => null,
            'car_id' => $car->id,
            'scheduled_at' => now()->addDay(),
            'full_name' => 'Guest Customer',
            'phone' => '0900000000',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.bookings.update', $booking), ['status' => 'confirmed'])
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'user_id' => null,
            'status' => 'confirmed',
        ]);
    }

}