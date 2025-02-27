<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKehadiransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // database/migrations/xxxx_xx_xx_create_kehadirans_table.php
        Schema::create('kehadirans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pegawai_id');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->string('nomor_duduk')->nullable();
            $table->enum('status', ['hadir', 'tidak hadir'])->nullable();
            $table->enum('keterangan', ['tepat waktu', 'terlambat', 'alpha', 'izin'])->nullable();
            $table->timestamps();

            // Foreign key ke tabel pegawai
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kehadirans');
    }
}