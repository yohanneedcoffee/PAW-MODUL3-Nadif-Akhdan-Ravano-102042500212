<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SistemPeminjamanBukuTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@perpustakaan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }

    /**
     * Test 1: Authentication & Authorization
     */
    public function test_admin_can_login_with_correct_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@perpustakaan.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_login_fails_with_invalid_password(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@perpustakaan.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_unauthenticated_user_cannot_access_protected_pages(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/buku')->assertRedirect('/login');
        $this->get('/anggota')->assertRedirect('/login');
        $this->get('/peminjaman')->assertRedirect('/login');
    }

    public function test_admin_can_logout(): void
    {
        $response = $this->actingAs($this->admin)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * Test 2: CRUD Data Buku
     */
    public function test_admin_can_create_book(): void
    {
        $response = $this->actingAs($this->admin)->post('/buku', [
            'judul' => 'Clean Code',
            'penulis' => 'Robert C. Martin',
            'penerbit' => 'Prentice Hall',
            'tahun_terbit' => 2008,
            'stok' => 5,
        ]);

        $response->assertRedirect('/buku');
        $this->assertDatabaseHas('buku', [
            'judul' => 'Clean Code',
            'stok' => 5,
        ]);
    }

    public function test_create_book_validation_fails_on_empty_fields(): void
    {
        $response = $this->actingAs($this->admin)->post('/buku', []);

        $response->assertSessionHasErrors(['judul', 'penulis', 'penerbit', 'tahun_terbit', 'stok']);
    }

    public function test_admin_can_view_books_list_and_detail(): void
    {
        $buku = Buku::create([
            'judul' => 'Refactoring',
            'penulis' => 'Martin Fowler',
            'penerbit' => 'Addison-Wesley',
            'tahun_terbit' => 2018,
            'stok' => 3,
        ]);

        $this->actingAs($this->admin)->get('/buku')->assertStatus(200)->assertSee('Refactoring');
        $this->actingAs($this->admin)->get("/buku/{$buku->id}")->assertStatus(200)->assertSee('Martin Fowler');
    }

    public function test_admin_can_update_book(): void
    {
        $buku = Buku::create([
            'judul' => 'Buku Lama',
            'penulis' => 'Penulis Lama',
            'penerbit' => 'Penerbit Lama',
            'tahun_terbit' => 2020,
            'stok' => 4,
        ]);

        $response = $this->actingAs($this->admin)->put("/buku/{$buku->id}", [
            'judul' => 'Buku Baru Diupdate',
            'penulis' => 'Penulis Baru',
            'penerbit' => 'Penerbit Baru',
            'tahun_terbit' => 2024,
            'stok' => 10,
        ]);

        $response->assertRedirect('/buku');
        $this->assertDatabaseHas('buku', [
            'id' => $buku->id,
            'judul' => 'Buku Baru Diupdate',
            'stok' => 10,
        ]);
    }

    public function test_admin_can_delete_book(): void
    {
        $buku = Buku::create([
            'judul' => 'Buku Akan Dihapus',
            'penulis' => 'Penulis',
            'penerbit' => 'Penerbit',
            'tahun_terbit' => 2020,
            'stok' => 2,
        ]);

        $response = $this->actingAs($this->admin)->delete("/buku/{$buku->id}");

        $response->assertRedirect('/buku');
        $this->assertDatabaseMissing('buku', ['id' => $buku->id]);
    }

    /**
     * Test 3: CRUD Data Anggota
     */
    public function test_admin_can_create_anggota(): void
    {
        $response = $this->actingAs($this->admin)->post('/anggota', [
            'nama' => 'Joko Susanto',
            'email' => 'joko@example.com',
            'no_hp' => '081234567890',
        ]);

        $response->assertRedirect('/anggota');
        $this->assertDatabaseHas('anggota', [
            'nama' => 'Joko Susanto',
            'email' => 'joko@example.com',
        ]);
    }

    public function test_create_anggota_fails_with_invalid_email(): void
    {
        $response = $this->actingAs($this->admin)->post('/anggota', [
            'nama' => 'User Salah',
            'email' => 'bukan-format-email',
            'no_hp' => '081234567890',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_admin_can_update_and_delete_anggota(): void
    {
        $anggota = Anggota::create([
            'nama' => 'Anggota Awal',
            'email' => 'awal@example.com',
            'no_hp' => '08111111111',
        ]);

        // Update
        $this->actingAs($this->admin)->put("/anggota/{$anggota->id}", [
            'nama' => 'Anggota Edit',
            'email' => 'edit@example.com',
            'no_hp' => '08222222222',
        ])->assertRedirect('/anggota');

        $this->assertDatabaseHas('anggota', ['email' => 'edit@example.com']);

        // Delete
        $this->actingAs($this->admin)->delete("/anggota/{$anggota->id}")->assertRedirect('/anggota');
        $this->assertDatabaseMissing('anggota', ['id' => $anggota->id]);
    }

    /**
     * Test 4: Sistem Peminjaman, Pengurangan Stok, & Pengembalian Buku
     */
    public function test_borrowing_book_decrements_stock_and_sets_status_dipinjam(): void
    {
        $anggota = Anggota::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'no_hp' => '081234567802',
        ]);

        $buku = Buku::create([
            'judul' => 'Basis Data',
            'penulis' => 'Fathansyah',
            'penerbit' => 'Informatika',
            'tahun_terbit' => 2020,
            'stok' => 5,
        ]);

        $response = $this->actingAs($this->admin)->post('/peminjaman', [
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        $response->assertRedirect('/peminjaman');

        // Pastikan stok berkurang 1 (5 -> 4)
        $this->assertEquals(4, $buku->fresh()->stok);

        // Pastikan record peminjaman berstatus Dipinjam dan tanggal_kembali NULL
        $this->assertDatabaseHas('peminjaman', [
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'status' => 'Dipinjam',
            'tanggal_kembali' => null,
        ]);
    }

    public function test_cannot_borrow_book_with_zero_stock(): void
    {
        $anggota = Anggota::create([
            'nama' => 'Citra Dewi',
            'email' => 'citra@example.com',
            'no_hp' => '081234567803',
        ]);

        $buku = Buku::create([
            'judul' => 'Buku Habis',
            'penulis' => 'Penulis',
            'penerbit' => 'Penerbit',
            'tahun_terbit' => 2021,
            'stok' => 0,
        ]);

        $response = $this->actingAs($this->admin)->post('/peminjaman', [
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, $buku->fresh()->stok);
        $this->assertDatabaseCount('peminjaman', 0);
    }

    public function test_returning_book_increments_stock_and_cannot_be_returned_twice(): void
    {
        $anggota = Anggota::create([
            'nama' => 'Dina Lestari',
            'email' => 'dina@example.com',
            'no_hp' => '081234567804',
        ]);

        $buku = Buku::create([
            'judul' => 'Algoritma',
            'penulis' => 'Rinaldi',
            'penerbit' => 'Informatika',
            'tahun_terbit' => 2021,
            'stok' => 2,
        ]);

        $peminjaman = Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->subDays(3)->toDateString(),
            'tanggal_kembali' => null,
            'status' => 'Dipinjam',
        ]);

        // Proses pengembalian pertama
        $response = $this->actingAs($this->admin)->put("/peminjaman/{$peminjaman->id}/kembali");

        $response->assertRedirect('/peminjaman');
        $response->assertSessionHas('success', 'Buku berhasil dikembalikan!');

        // Verifikasi stok bertambah 1 (2 -> 3)
        $this->assertEquals(3, $buku->fresh()->stok);

        // Verifikasi status dan tanggal_kembali
        $peminjamanFresh = $peminjaman->fresh();
        $this->assertEquals('Dikembalikan', $peminjamanFresh->status);
        $this->assertEquals(now()->toDateString(), $peminjamanFresh->tanggal_kembali);

        // Percobaan pengembalian kedua untuk transaksi yang sama
        $responseAgain = $this->actingAs($this->admin)->put("/peminjaman/{$peminjaman->id}/kembali");

        $responseAgain->assertRedirect('/peminjaman');
        $responseAgain->assertSessionHas('error');

        // Pastikan stok TIDAK bertambah dua kali (tetap 3)
        $this->assertEquals(3, $buku->fresh()->stok);
    }
}
