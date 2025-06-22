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
        Schema::create('eksemplar_buku', function (Blueprint $table) {
            $table->string('nomor_eksemplar', 20)->primary();
            $table->string('kode_buku', 10);
            $table->enum('status_eksemplar', ['Asli','Fotokopian', 'Digital']);
            $table->enum('kondisi', ['Baik','Rusak Ringan','Rusak Berat'])->default('Baik');
            $table->timestamp('tanggal_masuk')->useCurrent();
            $table->timestamps();

            $table->foreign('kode_buku')->references('kode_buku')->on('bukus')->onDelete('cascade');
        });
        Schema::table('eksemplar_buku', function(Blueprint $table){
            $table->softDeletes();
        });
        
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eksemplar_buku');
    }
};
