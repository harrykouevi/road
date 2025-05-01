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
        Schema::create('road_reports', function (Blueprint $table) {
            $table->id();
            $table->string('description', 255)->nullable();
            $table->double('latitude', 20, 17)->default(0);
            $table->double('longitude', 20, 17)->default(0);
            $table->bigInteger('report_type_id')->nullable()->unsigned();
            $table->foreign('report_type_id')->references('id')->on('report_types')->onDelete('set null')->onUpdate('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('road_reports');
    }
};
