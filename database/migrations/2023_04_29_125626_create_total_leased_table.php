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
        Schema::create('total_leased', function (Blueprint $table) {
            $table->id();
            $table->string('site_id_tenant');
            $table->string('site_name');
            $table->string('regional');
            $table->enum('pulau', ['BALINUSRA', 'JAWA', 'KALIMANTAN', 'SULMAPUA', 'SUMATRA']);
            $table->enum('area', ['Area 1', 'Area 2', 'Area 3', 'Area 4']);
            $table->enum('kat_jenis_order', ['New Build', 'Existing']);
            $table->enum('sow2', ['B2S', 'COLO'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('total_leased');
    }
};
