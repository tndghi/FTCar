<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('brand');                  // Toyota, Honda, VinFast...
            $table->string('model')->nullable();       // Vios, Civic...
            $table->unsignedSmallInteger('year')->nullable();
            $table->decimal('price', 15, 2);
            $table->text('description')->nullable();

            $table->string('thumbnail')->nullable();   // ảnh đại diện 2D
            $table->boolean('has_3d_model')->default(false);
            $table->string('model_3d_path')->nullable(); // đường dẫn file .glb (nếu có)

            $table->enum('status', ['draft', 'published', 'sold_out'])->default('draft');
            $table->unsignedInteger('stock')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};