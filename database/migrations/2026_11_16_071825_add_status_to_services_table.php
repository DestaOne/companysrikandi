<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Layanan yang sudah ada tetap tampil, jadi bawaannya terbit
        Schema::table('services', function (Blueprint $table) {
            $table->string('status')->default('published')->after('products');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
