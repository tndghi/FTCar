<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('car_parts')) {
            $hasUniquePartsIndex = collect(Schema::getIndexes('car_parts'))->contains(
                fn (array $index) => $index['unique'] && $index['columns'] === ['car_id', 'object_name'],
            );

            if (! $hasUniquePartsIndex) {
                Schema::table('car_parts', function (Blueprint $table) {
                    $table->unique(['car_id', 'object_name']);
                });
            }

            return;
        }

        Schema::create('car_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();

            // object_name PHẢI khớp với tên object trong file .glb (đặt tên trong Blender)
            // VD: "wheel_front_left", "headlight_right", "mirror_left"
            $table->string('object_name');

            $table->string('label');            // tên hiển thị: "Bánh trước trái"
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // ảnh cận cảnh bộ phận (tuỳ chọn)
            $table->decimal('extra_price', 15, 2)->nullable(); // nếu bộ phận có phụ phí/tùy chọn

            $table->timestamps();

            $table->unique(['car_id', 'object_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_parts');
    }
};