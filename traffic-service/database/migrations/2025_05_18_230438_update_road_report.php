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
            if (!Schema::hasColumn('road_reports', 'status')) {
                $table->string('status', 20)->nullable();
            }
            if (!Schema::hasColumn('road_reports', 'comment')) {
                $table->string('comment', 200)->nullable();
            }

            if (Schema::hasColumn('road_reports', 'validated_at')) {
                $table->dropColumn('validated_at');
            }
            // if (Schema::hasColumn('road_reports', 'validated_at')) {
                $table->timestamp('validated_at')->nullable();
            // }


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
