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
        Schema::create('sales_order_2022', function (Blueprint $table) {
            $table->id();
            $table->string('pid')->length(255);
            $table->string('site_id_tenant')->length(255);
            $table->string('site_name')->length(255);
            $table->string('regional')->length(255);
            $table->enum('pulau', ['BALINUSRA', 'JAWA', 'KALIMANTAN', 'SULMAPUA', 'SUMATRA']);
            $table->enum('area', ['Area 1', 'Area 2', 'Area 3', 'Area 4']);
            $table->enum('sow2', ['B2S', 'COLO']);
            $table->enum('kat_tower', ['Bangun Mandiri', 'Titan', 'Edelweiss 1A', 'Edelweiss 1B', 'Edelweiss 2', 'Edelweiss 3', 'UNO', 'Akuisisi', 'Telkom Group'])->nullable();
            $table->enum('demografi', ['Urban', 'Sub urban', 'Rural']);
            $table->integer('tenant_existing')->nullable();
            $table->enum('final_status_site', ['RFI', 'On Going', 'DROP'])->length(255);
            $table->enum('status_xl', ['On Going', 'RFI-NY BAUF', 'RFI-BAUF DONE', 'BAK Completed', 'Invoice Done', 'DROP']);
            $table->string('status_lms')->length(255);
            $table->date('rfi_date')->nullable();
            $table->integer('aging_rfi_to_bak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_2022');
    }
};
