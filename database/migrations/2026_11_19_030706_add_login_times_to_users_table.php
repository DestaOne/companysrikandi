<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Perubahan dua kolom ini dicatat trait LogsActivity sebagai aktivitas masuk/keluar
            $table->timestamp('last_login_at')->nullable()->after('activities_seen_at');
            $table->timestamp('last_logout_at')->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_login_at', 'last_logout_at']);
        });
    }
};
