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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')
                    ->constrained('anggota')
                    ->cascadeOnDelete();

            $table->foreignId('buku_id')
                    ->constrained('buku')
                    ->cascadeOnDelete();

            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();

            $table->enum('status', [
                'Dipinjam',
                'Dikembalikan'
                ])
            ->default('Dipinjam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
