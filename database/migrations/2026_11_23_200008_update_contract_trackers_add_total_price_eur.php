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
        Schema::table('contract_trackers', function (Blueprint $table): void {
            $table->decimal('total_price_eur', 10, 2)->default(0.00)->after('applicant_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_trackers', function (Blueprint $table): void {
            $table->dropColumn('total_price_eur');
        });
    }
};
