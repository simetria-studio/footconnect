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
        Schema::table('users', function (Blueprint $table): void {
            $table->string('full_name')->nullable()->after('id');
            $table->enum('role', ['player', 'scout'])->default('player')->after('full_name');

            $table->string('city')->nullable()->after('email');
            $table->string('state')->nullable()->after('city');

            // Subscription / billing fields
            $table->string('plan_type')->nullable()->after('state'); // player / scout
            $table->string('plan_interval')->nullable()->after('plan_type'); // monthly / quarterly / yearly
            $table->string('stripe_customer_id')->nullable()->after('plan_interval');
            $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
            $table->string('subscription_status')->nullable()->after('stripe_subscription_id');
            $table->timestamp('current_period_end')->nullable()->after('subscription_status');
        });

        Schema::create('player_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('position')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->unsignedSmallInteger('height_cm')->nullable();
            $table->unsignedSmallInteger('weight_kg')->nullable();
            $table->string('current_club')->nullable();

            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->enum('dominant_foot', ['right', 'left', 'both'])->nullable();

            $table->string('profile_photo_path')->nullable();
            $table->text('bio')->nullable();

            $table->timestamps();
        });

        Schema::create('scout_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('professional_type', ['empresario', 'agente', 'treinador', 'olheiro'])->nullable();
            $table->string('organization')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('website')->nullable();
            $table->text('bio')->nullable();

            $table->timestamps();
        });

        Schema::create('player_videos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('player_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title')->nullable();
            $table->string('provider')->nullable(); // youtube, vimeo, file
            $table->string('url');
            $table->unsignedSmallInteger('display_order')->default(0);

            $table->timestamps();
        });

        Schema::create('player_photos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('player_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('path');
            $table->string('caption')->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);

            $table->timestamps();
        });

        Schema::create('player_stats', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('player_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('season')->nullable();
            $table->unsignedSmallInteger('matches_played')->default(0);
            $table->unsignedSmallInteger('goals')->default(0);
            $table->unsignedSmallInteger('assists')->default(0);
            $table->unsignedInteger('minutes_played')->default(0);

            $table->timestamps();
        });

        Schema::create('conversations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_one_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('user_two_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['user_one_id', 'user_two_id']);
        });

        Schema::create('messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('conversation_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('sender_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('body');
            $table->timestamp('read_at')->nullable();

            $table->timestamps();
        });

        Schema::create('favorites', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('scout_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('player_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['scout_id', 'player_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('player_stats');
        Schema::dropIfExists('player_photos');
        Schema::dropIfExists('player_videos');
        Schema::dropIfExists('scout_profiles');
        Schema::dropIfExists('player_profiles');

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'full_name',
                'role',
                'city',
                'state',
                'plan_type',
                'plan_interval',
                'stripe_customer_id',
                'stripe_subscription_id',
                'subscription_status',
                'current_period_end',
            ]);
        });
    }
};

