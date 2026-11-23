<?php

use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can save a pengaduan status and response', function () {
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@example.test',
        'password' => 'password',
        'role' => 'admin',
    ]);
    $pengaduan = Pengaduan::create([
        'kode_pengaduan' => 'ADU-202611-001',
        'nama_pelapor' => 'Pelapor',
        'no_hp' => '081234567890',
        'kategori' => 'Pelayanan',
        'judul_pengaduan' => 'Pengaduan layanan',
        'isi_pengaduan' => 'Layanan belum tersedia.',
    ]);

    $response = $this->actingAs($admin)->post(
        route('admin.pengaduan.tanggapi.store', $pengaduan),
        [
            'status' => 'diproses',
            'tanggapan' => 'Pengaduan sedang ditindaklanjuti.',
        ]
    );

    $response
        ->assertRedirect(route('admin.pengaduan.tanggapi', $pengaduan))
        ->assertSessionHas('sukses');

    $pengaduan->refresh();

    expect($pengaduan->status)->toBe('diproses')
        ->and($pengaduan->tanggapan)->toBe('Pengaduan sedang ditindaklanjuti.')
        ->and($pengaduan->ditanggapi_oleh)->toBe($admin->id)
        ->and($pengaduan->tanggal_tanggapan)->not->toBeNull();
});

test('admin can filter pengaduans by ditolak status', function () {
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@example.test',
        'password' => 'password',
        'role' => 'admin',
    ]);

    $ditolak = Pengaduan::create([
        'kode_pengaduan' => 'ADU-202611-002',
        'nama_pelapor' => 'Pelapor 1',
        'no_hp' => '081234567890',
        'kategori' => 'Pelayanan',
        'judul_pengaduan' => 'Pengaduan ditolak',
        'isi_pengaduan' => 'Isi pengaduan.',
        'status' => 'ditolak',
    ]);

    Pengaduan::create([
        'kode_pengaduan' => 'ADU-202611-003',
        'nama_pelapor' => 'Pelapor 2',
        'no_hp' => '081234567891',
        'kategori' => 'Infrastruktur',
        'judul_pengaduan' => 'Pengaduan diproses',
        'isi_pengaduan' => 'Isi pengaduan.',
        'status' => 'diproses',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.pengaduan.index', [
        'status' => 'ditolak',
    ]));

    $response
        ->assertOk()
        ->assertSee($ditolak->kode_pengaduan)
        ->assertDontSee('ADU-202611-003');
});
