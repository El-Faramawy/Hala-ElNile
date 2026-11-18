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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->nullable()->constrained('jobs')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete(); // المحافظة من جدول cities

            $table->string('job_title')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('passport_situation')->nullable(); // موقف جواز السفر (ساري أكثر من سنتين، أقل من سنتين، قيد الاستخراج، لا يوجد)
            $table->text('notes')->nullable();

            $table->string('status')->default('pending'); // pending, contacted, accepted, rejected
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
