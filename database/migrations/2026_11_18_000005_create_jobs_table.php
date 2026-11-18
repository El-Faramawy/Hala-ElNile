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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_category_id')->constrained('job_categories')->cascadeOnDelete();

            $table->string('title');
            $table->string('city')->nullable(); // موقع العمل في إيطاليا كـ text (مثل: "ميلانو - لومبارديا" أو "إقليم توسكانا - فلورنسا")
            $table->unsignedInteger('salary_min')->nullable();
            $table->unsignedInteger('salary_max')->nullable();
            $table->string('salary_text')->nullable(); // e.g. "€1,550 - €1,900"
            $table->string('salary_period')->nullable()->default('صافي شهرياً + حوافز');
            $table->string('contract_type')->nullable(); // e.g. "عقد دائم (غير موسمي)" / "عقد موسمي (فلوتسي 9 أشهر)"
            $table->string('badge')->nullable(); // e.g. "مطلوب عاجل - 14 مقعداً"
            $table->string('image')->nullable();

            // Job Perks & Inclusions
            $table->string('housing')->nullable(); // e.g. "سكن مؤثث مجاني شامل الفواتير"
            $table->string('meals')->nullable(); // e.g. "وجبات طعام يومية أثناء العمل"
            $table->string('insurance')->nullable(); // e.g. "تأمين صحي إيطالي شامل (SSN)"
            $table->string('working_hours')->nullable(); // e.g. "8 ساعات يومياً - يوم ونصف راحة"
            $table->string('experience')->nullable(); // e.g. "خبرة لا تقل عن سنتين بمجال المطابخ"
            $table->unsignedInteger('available_seats')->nullable()->default(1);

            // Detailed Content
            $table->longText('description')->nullable();
            $table->json('requirements')->nullable(); // Array of requirement strings

            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
