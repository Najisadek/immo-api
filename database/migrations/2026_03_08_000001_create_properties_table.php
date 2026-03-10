<?php

declare(strict_types=1);

use Illuminate\Database\{Migrations\Migration, Schema\Blueprint};
use App\Enums\{PropertyStatus, PropertyType};
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('type', PropertyType::values())->default(PropertyType::Apartment->value);
            $table->integer('rooms')->nullable();
            $table->decimal('surface', 10, 2);
            $table->decimal('price', 15, 2);
            $table->string('city');
            $table->text('description');
            $table->enum('status', PropertyStatus::values())->default(PropertyStatus::Available->value);
            $table->boolean('is_published')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
