<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Italian Cities Table
        Schema::create('italy_cities', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Italian Landmarks Table
        Schema::create('italy_landmarks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('italy_city_id')->constrained('italy_cities')->cascadeOnDelete();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('description', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Add relationships to travelers table
        Schema::table('travelers', function (Blueprint $table): void {
            $table->foreignId('italy_city_id')->nullable()->after('location')->constrained('italy_cities')->nullOnDelete();
            $table->foreignId('italy_landmark_id')->nullable()->after('italy_city_id')->constrained('italy_landmarks')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('travelers', function (Blueprint $table): void {
            $table->dropForeign(['italy_city_id']);
            $table->dropForeign(['italy_landmark_id']);
            $table->dropColumn(['italy_city_id', 'italy_landmark_id']);
        });

        Schema::dropIfExists('italy_landmarks');
        Schema::dropIfExists('italy_cities');
    }
};
