    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateTblRekamMedisTable extends Migration
    {
        public function up()
        {
            Schema::create('tbl_rekam_medis', function (Blueprint $table) {
                $table->id('id_rekam_medis');
                $table->foreignId('id_siswa')->constrained('tbl_siswa', 'id_siswa')->onUpdate('cascade')->onDelete('cascade');
                $table->foreignId('id_kunjungan')->constrained('tbl_kunjungan_uks', 'id_kunjungan')->onUpdate('cascade')->onDelete('cascade')->nullable();
                $table->date('tanggal');
                $table->text('keluhan');
                $table->text('diagnosis')->nullable();
                $table->text('tindakan')->nullable();
                $table->text('catatan')->nullable();
                $table->string('obat_diberikan', 50)->nullable();
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('tbl_rekam_medis');
        }
    }