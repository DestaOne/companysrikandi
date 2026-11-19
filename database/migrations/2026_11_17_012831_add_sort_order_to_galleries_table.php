<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('image');
        });

        // Urutan awal sama seperti tampilan sebelumnya: yang terbaru di depan
        $ids = DB::table('galleries')->orderByDesc('created_at')->orderByDesc('id')->pluck('id');

        foreach ($ids as $position => $id) {
            DB::table('galleries')->where('id', $id)->update(['sort_order' => $position + 1]);
        }
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
