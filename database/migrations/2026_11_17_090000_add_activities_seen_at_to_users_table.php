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
            // Kapan admin terakhir membuka menu Aktivitas; aktivitas setelahnya dihitung "baru" di lonceng
            $table->timestamp('activities_seen_at')->nullable()->after('remember_token');
        });

        // Admin yang sudah ada mulai dari nol, bukan dari seluruh riwayat aktivitas
        DB::table('users')->update(['activities_seen_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('activities_seen_at');
        });
    }
};
