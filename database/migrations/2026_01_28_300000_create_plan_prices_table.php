<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_prices', function (Blueprint $table): void {
            $table->id();
            $table->string('plan_key', 64)->unique();
            $table->unsignedInteger('amount_cents');
            $table->string('currency', 8)->default('brl');
            $table->string('interval', 16)->default('month');
            $table->unsignedTinyInteger('interval_count')->default(1);
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('display_label')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_prices');
    }
};
