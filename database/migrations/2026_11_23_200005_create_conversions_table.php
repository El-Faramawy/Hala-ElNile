<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conversions', function (Blueprint $table): void {
            $table->id();
            $table->decimal('total_egp', 15, 2);
            $table->decimal('expected_eur', 10, 2);
            $table->decimal('actual_eur', 10, 2);
            $table->decimal('diff_eur', 10, 2)->default(0.00);
            $table->enum('diff_type', ['matched', 'loss', 'gain'])->default('matched');
            $table->unsignedBigInteger('diff_ref_id')->nullable();
            $table->foreignId('to_treasury_id')->constrained('treasuries')->cascadeOnDelete();
            $table->date('conversion_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversions');
    }
};
