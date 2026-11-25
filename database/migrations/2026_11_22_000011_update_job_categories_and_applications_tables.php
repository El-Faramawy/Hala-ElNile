<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_categories', function (Blueprint $table) {
            $table->string('display_location')->default('both')->after('sort_order');
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('code', 20)->nullable()->unique()->after('id');
            $table->string('passport_number')->nullable()->after('passport_situation');
            $table->text('cancellation_reason')->nullable()->after('status');
            $table->json('stage_history')->nullable()->after('cancellation_reason');
            $table->foreignId('contract_tracker_id')->nullable()->after('stage_history')->constrained('contract_trackers')->nullOnDelete();
            $table->timestamp('converted_to_contract_at')->nullable()->after('contract_tracker_id');
        });

        Schema::table('contract_trackers', function (Blueprint $table) {
            $table->foreignId('job_application_id')->nullable()->after('id')->constrained('job_applications')->nullOnDelete();
            $table->json('stage_history')->nullable()->after('notes');
        });

        // Backfill existing applications with numeric codes and initial stage_history
        $applications = DB::table('job_applications')->get();
        foreach ($applications as $application) {
            $numericCode = (string) (100000 + (int) $application->id);
            $stageHistory = json_encode([
                [
                    'stage' => $application->status ?? 'stage_1',
                    'title' => 'المرحلة 1: استلام وفحص الأوراق',
                    'changed_at' => $application->created_at ?? now()->toDateTimeString(),
                    'admin_name' => 'النظام',
                    'notes' => 'تسجيل الطلب الأولي',
                ],
            ], JSON_UNESCAPED_UNICODE);

            DB::table('job_applications')->where('id', $application->id)->update([
                'code' => $numericCode,
                'stage_history' => $stageHistory,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_trackers', function (Blueprint $table) {
            $table->dropForeign(['job_application_id']);
            $table->dropColumn(['job_application_id', 'stage_history']);
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['contract_tracker_id']);
            $table->dropColumn([
                'code',
                'passport_number',
                'cancellation_reason',
                'stage_history',
                'contract_tracker_id',
                'converted_to_contract_at',
            ]);
        });

        Schema::table('job_categories', function (Blueprint $table) {
            $table->dropColumn('display_location');
        });
    }
};
