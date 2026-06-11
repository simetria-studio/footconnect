<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('referral_code', 32)->nullable()->unique()->after('plan_group');
            $table->foreignId('referred_by_id')->nullable()->after('referral_code')->constrained('users')->nullOnDelete();
            $table->string('pix_key')->nullable()->after('country');
            $table->string('pix_key_type', 16)->nullable()->after('pix_key');
        });

        Schema::create('referral_withdrawals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('amount_cents');
            $table->string('pix_key');
            $table->string('pix_key_type', 16);
            $table->string('status', 32)->default('pending');
            $table->boolean('is_automatic')->default(true);
            $table->timestamp('processed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('referral_commissions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_id')->constrained('users')->cascadeOnDelete();
            $table->string('stripe_invoice_id')->unique();
            $table->unsignedInteger('payment_cents');
            $table->unsignedTinyInteger('commission_percent')->default(25);
            $table->unsignedInteger('commission_cents');
            $table->string('status', 32)->default('pending');
            $table->timestamp('compensates_at');
            $table->timestamp('available_at')->nullable();
            $table->foreignId('withdrawal_id')->nullable()->constrained('referral_withdrawals')->nullOnDelete();
            $table->timestamps();

            $table->index(['referrer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_commissions');
        Schema::dropIfExists('referral_withdrawals');

        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['referred_by_id']);
            $table->dropColumn(['referral_code', 'referred_by_id', 'pix_key', 'pix_key_type']);
        });
    }
};
