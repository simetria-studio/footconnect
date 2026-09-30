<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('player_profiles', function (Blueprint $table) {
            $table->text('story')->nullable()->after('characteristics');
        });

        Schema::table('scout_profiles', function (Blueprint $table) {
            $table->text('story')->nullable()->after('bio');
            $table->string('video_url')->nullable()->after('story');
        });
    }

    public function down(): void
    {
        Schema::table('player_profiles', function (Blueprint $table) {
            $table->dropColumn('story');
        });

        Schema::table('scout_profiles', function (Blueprint $table) {
            $table->dropColumn(['story', 'video_url']);
        });
    }
};
