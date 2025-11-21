<?php

namespace Tests\Feature;

use App\Models\Obat;
use App\Models\RekamMedis;
use App\Models\Siswa;
use App\Models\StokObat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekamMedisMultiObatTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'uks']);
        $this->actingAs($this->user);
    }

    public function test_can_create_rekam_medis_with_multiple_obat(): void
    {
        $siswa = Siswa::factory()->create();
        $obat1 = Obat::factory()->create(['nama_obat' => 'Paracetamol', 'kategori' => 'Analgesik']);
        $obat2 = Obat::factory()->create(['nama_obat' => 'Ibuprofen', 'kategori' => 'Analgesik']);

        // Create stock for obat
        StokObat::factory()->create(['id_obat' => $obat1->id_obat, 'jumlah' => 50]);
        StokObat::factory()->create(['id_obat' => $obat2->id_obat, 'jumlah' => 30]);

        $response = $this->post(route('uks.rekam-medis.store'), [
            'id_siswa' => $siswa->id_siswa,
            'tanggal' => now()->toDateString(),
            'keluhan' => 'Sakit kepala',
            'diagnosis' => 'Migrain',
            'obat_diberikan' => [
                'id_obat' => [$obat1->id_obat, $obat2->id_obat],
                'jumlah' => [2, 1],
                'aturan_pakai' => ['3x1 hari', '2x1 hari'],
            ],
        ]);

        $response->assertRedirect(route('uks.rekam-medis.index'));

        // Verify Rekam Medis record created
        $this->assertDatabaseHas('tbl_rekam_medis', [
            'id_siswa' => $siswa->id_siswa,
            'keluhan' => 'Sakit kepala',
        ]);

        // Verify detailed obat records created
        $this->assertDatabaseHas('tbl_rekam_medis_obat', [
            'id_obat' => $obat1->id_obat,
            'jumlah' => 2,
        ]);
        $this->assertDatabaseHas('tbl_rekam_medis_obat', [
            'id_obat' => $obat2->id_obat,
            'jumlah' => 1,
        ]);

        // Verify stock was reduced (FIFO)
        $stok1 = StokObat::where('id_obat', $obat1->id_obat)->first();
        $this->assertEquals(48, $stok1->jumlah);

        $stok2 = StokObat::where('id_obat', $obat2->id_obat)->first();
        $this->assertEquals(29, $stok2->jumlah);
    }

    public function test_requires_at_least_one_obat_if_provided(): void
    {
        $siswa = Siswa::factory()->create();

        $response = $this->post(route('uks.rekam-medis.store'), [
            'id_siswa' => $siswa->id_siswa,
            'tanggal' => now()->toDateString(),
            'keluhan' => 'Sakit',
            'diagnosis' => null,
            'obat_diberikan' => [
                'id_obat' => [],
                'jumlah' => [],
                'aturan_pakai' => [],
            ],
        ]);

        // Should still be allowed if no obat provided
        $response->assertRedirect(route('uks.rekam-medis.index'));
    }

    public function test_obat_must_exist(): void
    {
        $siswa = Siswa::factory()->create();

        $response = $this->post(route('uks.rekam-medis.store'), [
            'id_siswa' => $siswa->id_siswa,
            'tanggal' => now()->toDateString(),
            'keluhan' => 'Sakit',
            'obat_diberikan' => [
                'id_obat' => [99999],
                'jumlah' => [2],
                'aturan_pakai' => ['3x1'],
            ],
        ]);

        $response->assertSessionHasErrors('obat_diberikan.id_obat.0');
    }

    public function test_jumlah_must_be_positive(): void
    {
        $siswa = Siswa::factory()->create();
        $obat = Obat::factory()->create();

        $response = $this->post(route('uks.rekam-medis.store'), [
            'id_siswa' => $siswa->id_siswa,
            'tanggal' => now()->toDateString(),
            'keluhan' => 'Sakit',
            'obat_diberikan' => [
                'id_obat' => [$obat->id_obat],
                'jumlah' => [0],
                'aturan_pakai' => ['3x1'],
            ],
        ]);

        $response->assertSessionHasErrors('obat_diberikan.jumlah.0');
    }

    public function test_obat_summary_stored_as_json(): void
    {
        $siswa = Siswa::factory()->create();
        $obat1 = Obat::factory()->create();
        $obat2 = Obat::factory()->create();

        StokObat::factory()->create(['id_obat' => $obat1->id_obat, 'jumlah' => 50]);
        StokObat::factory()->create(['id_obat' => $obat2->id_obat, 'jumlah' => 50]);

        $this->post(route('uks.rekam-medis.store'), [
            'id_siswa' => $siswa->id_siswa,
            'tanggal' => now()->toDateString(),
            'keluhan' => 'Sakit',
            'obat_diberikan' => [
                'id_obat' => [$obat1->id_obat, $obat2->id_obat],
                'jumlah' => [2, 3],
                'aturan_pakai' => ['3x1', '2x1'],
            ],
        ]);

        $rekam = RekamMedis::latest()->first();
        $obatData = json_decode($rekam->obat_diberikan, true);

        $this->assertIsArray($obatData);
        $this->assertCount(2, $obatData);
        $this->assertEquals($obat1->id_obat, $obatData[0]['id_obat']);
        $this->assertEquals(2, $obatData[0]['jumlah']);
        $this->assertEquals('3x1', $obatData[0]['aturan_pakai']);
    }
}
