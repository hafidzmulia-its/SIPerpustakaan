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
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 30);
            $table->string('nomor_eksemplar', 20);
            $table->timestamp('tanggal_peminjaman');
            $table->timestamp('tanggal_pengembalian')->nullable();
            $table->integer('jumlah_denda')->nullable();
            $table->timestamps();
            $table->foreign('nis')->references('nis')->on('siswas')->onDelete('cascade');
            $table->foreign('nomor_eksemplar')->references('nomor_eksemplar')->on('eksemplar_buku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
