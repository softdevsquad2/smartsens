<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUserTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_user', function (Blueprint $table) {
            $table->id('id_user');
            $table->foreignId('id_wali_kelas')
                ->nullable()
                ->constrained('tbl_wali_kelas', 'id_wali_kelas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('id_siswa')
                ->nullable()
                ->constrained('tbl_siswa', 'id_siswa')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->enum('role', ['admin', 'guru', 'operator', 'siswa', 'ketua', 'toolman', 'uks', 'piket'])->nullable();
            $table->bigInteger('card_code')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_user');
    }
}
