<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('records_current', function (Blueprint $table) {
            $table->id();
            $table->string('NOP')->nullable();
            $table->string('PROGRAM')->nullable();
            $table->string('KATEGORI')->nullable();
            $table->text('JUSTIFIKASI')->nullable();
            $table->string('PROPOSAL')->nullable();
            $table->string('BUDGET')->nullable();
            $table->string('REVENUE')->nullable();
            $table->string('COST')->nullable();
            $table->string('PROFIT')->nullable();
            $table->string('REVENUE INCREMENTAL 1')->nullable();
            $table->string('REVENUE (ACTUAL)')->nullable();
            $table->string('INCREMENTAL 1')->nullable();
            $table->string('INCREMENTAL 2')->nullable();
            $table->string('INCREMENTAL 3')->nullable();
            $table->string('STATUS')->nullable();
            $table->string('PILOT')->nullable();
            $table->string('DRIVEN PROGRAM')->nullable();
            $table->string('ASSIGN BY')->nullable();
            $table->string('APPROVED BY')->nullable();
            $table->string('row_hash')->nullable();
            $table->string('ingest_timestamp')->nullable();
            $table->string('source_file')->nullable();
            
            // Following columns from subsequent migrations will be added via those migrations
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('records_current');
    }
};
