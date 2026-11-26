<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contract_trackers', function (Blueprint $table): void {
            $table->foreignId('supplier_id')
                ->nullable()
                ->after('total_price_eur')
                ->constrained('suppliers')
                ->nullOnDelete();
            $table->decimal('supplier_cost_eur', 12, 2)
                ->nullable()
                ->after('supplier_id');
        });
    }

    public function down(): void
    {
        Schema::table('contract_trackers', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('supplier_id');
            $table->dropColumn('supplier_cost_eur');
        });
    }
};
