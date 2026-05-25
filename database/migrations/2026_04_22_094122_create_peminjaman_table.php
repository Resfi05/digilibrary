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
        $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
        $table->foreignId('book_id')->constrained('books')->restrictOnDelete();
        $table->date('tanggal_pinjam');
        $table->date('tanggal_kembali');
        $table->timestamp('tanggal_dikembalikan')->nullable();
        $table->enum('status', ['pending','dipinjam','dikembalikan','ditolak','terlambat'])->default('pending');
        $table->decimal('denda', 10, 2)->default(0);
        $table->boolean('bayar_denda')->default(false);
        $table->string('token', 100)->nullable()->unique();
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
