<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable()->after('email');
            $table->foreignId('teacher_id')->nullable()->after('role')->constrained('teachers')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->after('teacher_id')->constrained('parents')->nullOnDelete();
        });

        // Every account had full access before roles existed; keep it that way.
        DB::table('users')->update(['role' => 'admin']);

        // No default: an account created without a role fails instead of
        // silently getting one.
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
            $table->dropConstrainedForeignId('teacher_id');
            $table->dropColumn('role');
        });
    }
};
