<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_highlights', function (Blueprint $table) {
            $table->id();
            $table->string('category', 20); // player, coach, businessman
            $table->string('name');
            $table->string('role_title')->nullable();
            $table->string('organization')->nullable();
            $table->text('story');
            $table->string('photo_path')->nullable();
            $table->string('video_path')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['is_active', 'year', 'month']);
            $table->index(['category', 'year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_highlights');
    }
};
