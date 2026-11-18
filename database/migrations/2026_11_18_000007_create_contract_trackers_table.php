<?php

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
        Schema::create('contract_trackers', function (Blueprint $table) {
            $table->id();
            $table->string('contract_code')->unique()->index(); // e.g. "HS-78921"
            $table->string('passport_number')->index(); // e.g. "A12345678"
            $table->string('applicant_name');
            $table->string('job_title');
            $table->string('city_name')->nullable();

            // Progress step (1..5)
            // 1: استلام وفحص الأوراق
            // 2: التقديم بنظام فلوتسي (Decreto Flussi)
            // 3: صدور الموافقة الأمنية (Nulla Osta)
            // 4: استلام التأشيرة من السفارة الإيطالية
            // 5: السفر والاستقبال في إيطاليا
            $table->unsignedTinyInteger('step')->default(1);
            $table->string('status_text');
            $table->date('issue_date')->nullable();

            $table->string('embassy_appointment')->nullable();
            $table->text('flight_details')->nullable();
            $table->text('notes')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_trackers');
    }
};
