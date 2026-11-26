<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduled_items', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->enum('type', ['expense', 'revenue'])->default('expense');
            $table->enum('frequency', ['monthly', 'yearly', 'once'])->default('monthly');
            $table->decimal('amount', 15, 2);
            $table->enum('currency', ['EGP', 'EUR'])->default('EGP');
            $table->date('next_due_date');
            $table->date('last_paid_at')->nullable();
            $table->foreignId('treasury_id')->nullable()->constrained('treasuries')->nullOnDelete();
            $table->foreignId('expense_type_id')->nullable()->constrained('expense_types')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_items');
    }
};
