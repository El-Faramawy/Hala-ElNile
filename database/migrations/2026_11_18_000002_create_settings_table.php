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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('شركة هلا النيل لإلحاق العمالة المصرية بالخارج');
            $table->string('logo')->nullable();
            $table->string('fav_icon')->nullable();

            // Contact & Licensing Information
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('license_number')->nullable()->default('912 شركات');
            $table->string('commercial_record')->nullable()->default('104522 مكتب استثمار القاهرة');
            $table->string('tax_card')->nullable()->default('429-881-209');
            $table->string('working_hours')->nullable()->default('السبت - الخميس: 9:00 ص - 6:00 م');

            // Social Media Links
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('telegram')->nullable();
            $table->string('youtube')->nullable();

            // Static Pages and Policies (Single Language)
            $table->longText('about_us')->nullable();
            $table->longText('terms_conditions')->nullable();
            $table->longText('privacy_policy')->nullable();

            // Homepage Counter Statistics
            $table->string('stats_travelers')->nullable()->default('+1,450');
            $table->string('stats_contracts')->nullable()->default('100%');
            $table->string('stats_companies')->nullable()->default('+68');
            $table->string('stats_experience')->nullable()->default('14 عاماً');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
