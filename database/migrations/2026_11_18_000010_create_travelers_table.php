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
        Schema::create('travelers', function (Blueprint $table) {
            $table->id();

            // بيانات المسافر الأساسية
            $table->string('name'); // اسم المسافر (مثل: أحمد صابر عبد السلام)
            $table->string('governorate')->nullable(); // محافظته بمصر (مثل: محافظة الغربية)
            $table->string('job_title'); // المسمى الوظيفي بإيطاليا (مثل: فني تشييد وبناء بروما)

            // بيانات المعلم والموقع بإيطاليا
            $table->string('location')->nullable(); // المعلم أو المدينة (مثل: روما - الكولوسيوم / ميلانو - ساحة الدومو)
            $table->string('badge')->nullable(); // شارة التوثيق بالكارد (مثل: عقد دائم موثق / شيف مطعم إيطالي)

            // الصورتان الموجودتان في الكارد
            $table->string('landmark_image')->nullable(); // صورة المعلم الإيطالي الكبيرة (مثل: travelers-colosseum.jpg)
            $table->string('avatar')->nullable(); // الصورة الشخصية للمسافر (مثل: person1.jpg)

            // قصة وتجربة السفر
            $table->text('story'); // نص التجربة والرأي
            $table->string('contract_code')->nullable(); // كود العقد الموثق (مثل: HS-5120)
            $table->unsignedTinyInteger('rating')->default(5); // عدد النجوم (1 إلى 5)

            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travelers');
    }
};
