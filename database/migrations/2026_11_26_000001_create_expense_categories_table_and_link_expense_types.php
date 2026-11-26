<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('expense_types', function (Blueprint $table): void {
            $table->foreignId('expense_category_id')
                ->nullable()
                ->after('id')
                ->constrained('expense_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('expense_types', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('expense_category_id');
        });

        Schema::dropIfExists('expense_categories');
    }
};
