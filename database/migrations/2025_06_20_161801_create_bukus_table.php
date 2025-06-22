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
        // Create the 'buku' table
        Schema::create('bukus', function (Blueprint $table) {
            $table->string('kode_buku', 10)->primary();
            $table->string('judul', 100);
            $table->string('cover')->nullable()->default('img/no-cover.png');
            $table->string('pengarang', 100);
            $table->year('tahun_terbit');

            $table->timestamps();
        });
        Schema::table('bukus', function(Blueprint $table){
            $table->softDeletes();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
