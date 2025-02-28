<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->string('nip', 30);
            $table->enum('jabatan', ['kabalmon', 'katim', 'ppk', 'staff', 'staff_pelayanan', 'security', 'cs', 'driver', 'magang'])->default('staff');
            $table->enum('tim', ['monev', 'penerbitan', 'pkip', 'tu'])->nullable();
            $table->text('face_data')->nullable();
            $table->text('face_descriptor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pegawais');
    }
}
