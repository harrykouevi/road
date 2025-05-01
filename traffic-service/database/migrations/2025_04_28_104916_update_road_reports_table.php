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
        
        Schema::table('road_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id'); // ID de l'utilisateur
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('road_reports', function (Blueprint $table) {
            // Drop the foreign key constraint in reverse migration
            $table->dropColumn('user_id') ;
            
        });
    }
};
