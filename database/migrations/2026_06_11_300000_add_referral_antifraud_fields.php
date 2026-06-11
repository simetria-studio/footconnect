<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('referral_program_blocked')->default(false)->after('pix_key_type');
            $table->string('referral_registration_ip', 45)->nullable()->after('referral_program_blocked');
            $table->boolean('referral_is_counted')->default(true)->after('referral_registration_ip');
            $table->string('referral_invalid_reason')->nullable()->after('referral_is_counted');
        });

        Schema::table('referral_commissions', function (Blueprint $table): void {
            $table->boolean('is_counted')->default(true)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('referral_commissions', function (Blueprint $table): void {
            $table->dropColumn('is_counted');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'referral_program_blocked',
                'referral_registration_ip',
                'referral_is_counted',
                'referral_invalid_reason',
            ]);
        });
    }
};
