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
        Schema::table('records_current', function (Blueprint $table) {
            // Justification Sub-fields
            $table->text('justifikasi_objective')->nullable();
            $table->text('justifikasi_alasan')->nullable();
            $table->text('justifikasi_distribusi')->nullable();
            $table->text('justifikasi_lingkup')->nullable();
            $table->text('justifikasi_teknis')->nullable();
            $table->text('justifikasi_rab')->nullable();
            $table->text('justifikasi_summary')->nullable();

            // Signatures (storing name/text or base64 data, using text for flexibility)
            $table->text('sign_user')->nullable();
            $table->text('sign_manager_nop')->nullable();
            $table->text('sign_manager_sqa_mba')->nullable();
            $table->text('sign_manager_nos')->nullable();
            $table->text('sign_gm')->nullable();

            // Approval Status Tracking
            $table->string('approval_stage')->default('Submitted')->after('STATUS'); 
            // Stages: Submitted -> Manager NOP -> SQA/MBA -> Manager NOS -> GM -> Approved
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('records_current', function (Blueprint $table) {
            $table->dropColumn([
                'justifikasi_objective', 'justifikasi_alasan', 'justifikasi_distribusi',
                'justifikasi_lingkup', 'justifikasi_teknis', 'justifikasi_rab', 'justifikasi_summary',
                'sign_user', 'sign_manager_nop', 'sign_manager_sqa_mba', 'sign_manager_nos', 'sign_gm',
                'approval_stage'
            ]);
        });
    }
};
