<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('player', 'scout', 'influencer') NOT NULL DEFAULT 'player'");
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::table('users')->where('role', 'influencer')->update(['role' => 'player']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('player', 'scout') NOT NULL DEFAULT 'player'");
    }
};
