<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plan_prices', function (Blueprint $table): void {
            $table->unsignedSmallInteger('trial_days')->default(0)->after('is_active');
        });

        DB::table('plan_prices')
            ->whereIn('plan_key', [
                'g2_monthly', 'g2_yearly',
                'g3_monthly', 'g3_yearly',
                'g4_monthly', 'g4_yearly',
            ])
            ->update(['trial_days' => 30]);
    }

    public function down(): void
    {
        Schema::table('plan_prices', function (Blueprint $table): void {
            $table->dropColumn('trial_days');
        });
    }
};
