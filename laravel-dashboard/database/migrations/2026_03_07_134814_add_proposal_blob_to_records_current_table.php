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
            // Using LONGBLOB to store up to 4GB, though MEDIUMBLOB (16MB) is likely enough for 5MB limit
            $table->binary('proposal_blob')->nullable()->after('PROPOSAL');
            $table->string('proposal_mime_type')->nullable()->after('proposal_blob');
            $table->string('proposal_filename')->nullable()->after('proposal_mime_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('records_current', function (Blueprint $table) {
            $table->dropColumn(['proposal_blob', 'proposal_mime_type', 'proposal_filename']);
        });
    }
};
