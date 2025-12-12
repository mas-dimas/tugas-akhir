<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            if (!Schema::hasColumn('competitions', 'title')) {
                $table->string('title')->after('id');
            }
            if (!Schema::hasColumn('competitions', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('competitions', 'stages')) {
                $table->text('stages')->nullable()->after('description');
            }
            if (!Schema::hasColumn('competitions', 'source_link')) {
                $table->string('source_link')->nullable()->after('stages');
            }
            if (!Schema::hasColumn('competitions', 'poster_path')) {
                $table->string('poster_path')->nullable()->after('source_link');
            }
            if (!Schema::hasColumn('competitions', 'guidebook_path')) {
                $table->string('guidebook_path')->nullable()->after('poster_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            if (Schema::hasColumn('competitions', 'guidebook_path')) $table->dropColumn('guidebook_path');
            if (Schema::hasColumn('competitions', 'poster_path')) $table->dropColumn('poster_path');
            if (Schema::hasColumn('competitions', 'source_link')) $table->dropColumn('source_link');
            if (Schema::hasColumn('competitions', 'stages')) $table->dropColumn('stages');
            if (Schema::hasColumn('competitions', 'description')) $table->dropColumn('description');
            if (Schema::hasColumn('competitions', 'title')) $table->dropColumn('title');
        });
    }
};
