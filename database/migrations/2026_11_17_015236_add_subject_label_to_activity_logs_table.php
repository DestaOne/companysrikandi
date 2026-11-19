<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Nama data saat aktivitas terjadi (mis. judul layanan), agar tetap terbaca setelah datanya dihapus
            $table->string('subject_label')->nullable()->after('subject_id');
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn('subject_label');
        });
    }
};
