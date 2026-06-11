<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('country')->nullable()->after('state');
        });

        Schema::table('player_profiles', function (Blueprint $table): void {
            $table->string('modality', 32)->nullable()->after('user_id');
            $table->string('gender', 16)->nullable()->after('modality');
            $table->date('birth_date')->nullable()->after('age');
            $table->text('characteristics')->nullable()->after('bio');
            $table->boolean('is_student')->default(false)->after('characteristics');
            $table->string('school_name')->nullable()->after('is_student');
            $table->string('school_grade')->nullable()->after('school_name');
            $table->string('country')->nullable()->after('state');
            $table->string('institution_type', 32)->nullable()->after('current_club');
            $table->string('institution_name')->nullable()->after('institution_type');
            $table->boolean('is_federated')->default(false)->after('institution_name');
            $table->boolean('has_awards')->default(false)->after('is_federated');
            $table->text('awards_description')->nullable()->after('has_awards');
        });

        Schema::table('scout_profiles', function (Blueprint $table): void {
            $table->unsignedTinyInteger('age')->nullable()->after('user_id');
            $table->string('country')->nullable()->after('state');
            $table->boolean('has_company')->default(false)->after('organization');
            $table->string('company_name')->nullable()->after('has_company');
            $table->string('scope', 32)->nullable()->after('company_name');
            $table->boolean('is_federated')->default(false)->after('scope');
            $table->string('federation_name')->nullable()->after('is_federated');
        });

        DB::table('player_profiles')
            ->whereNotNull('bio')
            ->whereNull('characteristics')
            ->update(['characteristics' => DB::raw('bio')]);

        DB::table('scout_profiles')
            ->whereNotNull('organization')
            ->whereNull('company_name')
            ->update([
                'company_name' => DB::raw('organization'),
                'has_company' => true,
            ]);
    }

    public function down(): void
    {
        Schema::table('scout_profiles', function (Blueprint $table): void {
            $table->dropColumn([
                'age',
                'country',
                'has_company',
                'company_name',
                'scope',
                'is_federated',
                'federation_name',
            ]);
        });

        Schema::table('player_profiles', function (Blueprint $table): void {
            $table->dropColumn([
                'modality',
                'gender',
                'birth_date',
                'characteristics',
                'is_student',
                'school_name',
                'school_grade',
                'country',
                'institution_type',
                'institution_name',
                'is_federated',
                'has_awards',
                'awards_description',
            ]);
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('country');
        });
    }
};
