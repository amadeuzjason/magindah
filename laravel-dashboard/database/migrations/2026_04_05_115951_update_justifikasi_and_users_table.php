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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'lokasi_branch')) {
                $table->string('lokasi_branch')->nullable()->after('username');
            }
        });

        Schema::table('records_current', function (Blueprint $table) {
            $table->longText('justifikasi_objective')->nullable()->change();
            $table->longText('justifikasi_alasan')->nullable()->change();
            $table->longText('justifikasi_distribusi')->nullable()->change();
            $table->longText('justifikasi_lingkup')->nullable()->change();
            $table->longText('justifikasi_teknis')->nullable()->change();
            $table->longText('justifikasi_rab')->nullable()->change();
            $table->longText('justifikasi_summary')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('lokasi_branch');
        });

        Schema::table('records_current', function (Blueprint $table) {
            $table->text('justifikasi_objective')->nullable()->change();
            $table->text('justifikasi_alasan')->nullable()->change();
            $table->text('justifikasi_distribusi')->nullable()->change();
            $table->text('justifikasi_lingkup')->nullable()->change();
            $table->text('justifikasi_teknis')->nullable()->change();
            $table->text('justifikasi_rab')->nullable()->change();
            $table->text('justifikasi_summary')->nullable()->change();
        });
    }
};
