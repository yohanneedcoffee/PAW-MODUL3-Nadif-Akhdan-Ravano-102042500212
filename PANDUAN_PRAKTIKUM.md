# Panduan Praktikum Sistem Peminjaman Buku (CRUD Laravel)

Kerjakan TODO secara berurutan dari 1 sampai 10. TODO yang belakangan bergantung pada TODO sebelumnya, jadi pastikan tiap TODO sudah berhasil sebelum lanjut.

Setiap TODO di project sudah diberi komentar `TODO` di dalam file. Baca komentar itu baik-baik sebelum mulai menulis.

## TODO 1 - Mass Assignment Model Buku

File: `app/Models/Buku.php`

### Tujuan
Memahami cara Eloquent membatasi kolom mana saja yang boleh diisi sekaligus (mass assignment) saat menyimpan data.

### Petunjuk Pengerjaan
1. Buka file migration tabel `buku` di folder `database/migrations`. Catat kolom apa saja yang dibuat.
2. Tentukan kolom mana yang nantinya diisi oleh admin lewat form, dan kolom mana yang diisi otomatis oleh Laravel atau database.
3. Pelajari property `$fillable` di dokumentasi Eloquent bagian *Mass Assignment*.
4. Isi property tersebut di model sesuai kolom yang sudah kamu tentukan pada langkah 2.

### Yang Perlu Diperhatikan
- Nama kolom harus sama persis dengan yang ada di migration, termasuk huruf dan garis bawahnya.
- Kolom `id`, `created_at`, dan `updated_at` sudah diurus Laravel. Apakah perlu didaftarkan?
- Jika muncul `MassAssignmentException`, berarti ada kolom yang dikirim tetapi belum diizinkan.

### Cara Mengecek
Jalankan `php artisan tinker`, lalu coba buat satu data buku memakai method `create()` milik model `Buku`. Jika data tersimpan dan semua kolom terisi (tidak ada yang `null` padahal sudah diisi), TODO ini berhasil.

## TODO 2 - Mass Assignment Model Anggota

File: `app/Models/Anggota.php`

### Tujuan
Menerapkan konsep yang sama seperti TODO 1 pada model anggota.

### Petunjuk Pengerjaan
1. Buka migration tabel `anggota` dan catat kolom-kolomnya.
2. Bandingkan dengan form tambah anggota di `resources/views/anggota/create.blade.php`. Field apa saja yang dikirim dari form?
3. Gunakan property yang sama seperti TODO 1 untuk mengizinkan kolom tersebut.

### Yang Perlu Diperhatikan
- Perhatikan nama tabel. Model `Anggota` sudah mengatur nama tabelnya sendiri. Coba cari tahu kenapa hal itu diperlukan.
- Typo pada nama kolom tidak selalu memunculkan error, tetapi kolomnya akan tersimpan kosong.

### Cara Mengecek
Gunakan `php artisan tinker` dan coba buat satu data anggota memakai `create()`. Periksa hasilnya di phpMyAdmin: apakah semua kolom terisi?

## TODO 3 - Relasi belongsTo pada Peminjaman

File: `app/Models/Peminjaman.php`

### Tujuan
Memahami relasi antartabel. Satu transaksi peminjaman dimiliki oleh satu anggota dan berisi satu buku.

### Petunjuk Pengerjaan
1. Buka migration tabel `peminjaman`. Temukan kolom yang menjadi *foreign key* ke tabel lain.
2. Buka method `index()` dan `show()` di `PeminjamanController`. Relasi dengan nama apa saja yang dipanggil di sana? Nama method relasimu harus cocok dengan nama tersebut.
3. Pelajari method `belongsTo()` di dokumentasi Eloquent bagian *Relationships > One To Many (Inverse)*.
4. Pikirkan: apakah nama foreign key di tabelmu sudah sesuai konvensi Laravel, atau perlu disebutkan secara eksplisit?

### Yang Perlu Diperhatikan
- Method relasi harus mengembalikan (*return*) hasil pemanggilan relasi. Lupa `return` adalah kesalahan yang paling sering terjadi.
- Satu transaksi hanya punya satu anggota dan satu buku. Apakah nama method sebaiknya tunggal atau jamak?
- Pastikan class model tujuan dapat dikenali, yaitu berada di namespace yang sama atau sudah di-import.

### Cara Mengecek
Di `php artisan tinker`, ambil satu data peminjaman lalu akses relasinya sebagai property. Jika yang muncul objek anggota atau buku (bukan `null` atau error), relasi sudah benar. Data peminjaman bisa kamu buat dulu lewat phpMyAdmin atau seeder.


## TODO 4 - Resource Route

File: `routes/web.php`

### Tujuan
Memahami cara mendaftarkan seluruh route CRUD sekaligus dengan satu baris, serta menambahkan route khusus di luar CRUD standar.

### Petunjuk Pengerjaan
1. Pelajari `Route::resource()` di dokumentasi Laravel bagian *Controllers > Resource Controllers*. Perhatikan tabel route apa saja yang dibuat otomatis.
2. Daftarkan resource untuk ketiga controller di dalam group middleware yang sudah tersedia (lihat komentar TODO).
3. Untuk route pengembalian buku, baca komentar TODO: method HTTP apa yang diminta, URI-nya seperti apa, dan method controller mana yang dituju.
4. Buka `resources/views/peminjaman/show.blade.php` yang sudah memanggil route pengembalian. Nama route apa yang dipakai di sana? Beri nama route kamu sesuai nama tersebut.
5. Jalankan `php artisan route:list` dan perhatikan nama parameter URL untuk resource `anggota`. Bandingkan dengan nama variabel parameter di method `edit`, `update`, dan `destroy` milik `AnggotaController`. Apakah keduanya sama? Jika tidak, cari cara mengatur nama parameter resource di dokumentasi.

### Yang Perlu Diperhatikan
- Route ditulis di dalam group yang tepat. Route di luar group tidak dilindungi login.
- Laravel menebak bentuk tunggal dari kata bahasa Inggris. Kata bahasa Indonesia bisa ditebak dengan salah, dan hal ini memengaruhi *route model binding*.
- Error `Route [xxx] not defined` berarti nama route yang dipanggil di view belum terdaftar.

### Cara Mengecek
Jalankan `php artisan route:list`. Pastikan route `buku.*`, `anggota.*`, `peminjaman.*`, dan route pengembalian muncul. Lalu buka menu Buku, Anggota, dan Peminjaman di browser. Halaman tidak boleh menampilkan error route.


## TODO 5 - Store dan Validasi Buku

File: `app/Http/Controllers/BukuController.php`

### Tujuan
Memahami alur menyimpan data dari form: terima request, validasi, simpan, lalu redirect.

### Petunjuk Pengerjaan
1. Pelajari method `validate()` pada object `Request` serta daftar aturan di dokumentasi *Validation > Available Validation Rules*.
2. Untuk setiap field, tanyakan: wajib diisi atau tidak? Berupa teks atau angka? Ada batas panjang atau nilai minimum? Sesuaikan dengan tipe dan panjang kolom di migration.
3. Ingat bahwa `validate()` mengembalikan data yang lolos validasi. Gunakan hasil itu untuk menyimpan data lewat model (manfaatkan hasil TODO 1).
4. Lihat method lain di controller yang sama, misalnya `update()` atau `destroy()`. Tiru cara mereka melakukan redirect dan mengirim pesan sukses.

### Yang Perlu Diperhatikan
- Stok buku tidak masuk akal jika bernilai negatif. Aturan apa yang bisa mencegahnya?
- Kunci flash message harus sama dengan yang dibaca di layout. Cek file layout di `resources/views/layouts`.
- Jangan menyimpan seluruh isi request tanpa validasi.

### Cara Mengecek
1. Submit form tambah buku dalam keadaan kosong. Pesan error harus muncul.
2. Isi stok dengan angka negatif. Data harus ditolak.
3. Isi data dengan benar. Kamu harus diarahkan ke daftar buku, muncul pesan sukses, dan buku baru tampil di tabel.

## TODO 6 - Store dan Validasi Anggota

File: `app/Http/Controllers/AnggotaController.php`

### Tujuan
Menerapkan validasi yang memeriksa format data dan keunikan data di database.

### Petunjuk Pengerjaan
1. Baca komentar TODO: aturan apa saja yang diminta untuk setiap field?
2. Cari aturan validasi untuk format email dan aturan untuk memastikan nilai belum pernah ada di tabel. Baca cara menuliskan parameternya di dokumentasi.
3. Lihat method `update()` di controller yang sama. Bagaimana aturan keunikan email ditulis di sana? Pikirkan apa bedanya dengan kondisi saat menambah data baru.
4. Simpan data dan lakukan redirect dengan pola yang sama seperti TODO 5.

### Yang Perlu Diperhatikan
- Aturan keunikan harus mengarah ke nama tabel yang benar. Ingat nama tabel anggota yang kamu temukan di TODO 2.
- Batas panjang nomor HP harus sesuai dengan panjang kolom di migration.

### Cara Mengecek
Tambahkan satu anggota baru, lalu coba tambahkan lagi anggota lain dengan email yang sama. Penyimpanan kedua harus ditolak dengan pesan bahwa email sudah dipakai. Coba juga isi email tanpa tanda `@`.


## TODO 7 - Store Transaksi Peminjaman

File: `app/Http/Controllers/PeminjamanController.php`

### Tujuan
Memahami penyimpanan data yang berhubungan dengan tabel lain, termasuk memeriksa bahwa data yang dipilih benar-benar ada.

### Petunjuk Pengerjaan
1. Untuk `anggota_id` dan `buku_id`, cari aturan validasi yang memastikan ID tersebut benar-benar ada di tabel asalnya.
2. Baca komentar TODO tentang nilai status awal transaksi. Lihat juga method `kembali()` untuk mengetahui penulisan status yang dipakai di project ini.
3. Pikirkan kolom mana yang diisi dari request dan kolom mana yang ditentukan oleh controller sendiri. Saat buku baru dipinjam, apakah tanggal kembali sudah diketahui?
4. Pelajari method `kembali()`: apa yang terjadi pada stok buku saat buku dikembalikan? Saat buku dipinjam, logikanya seharusnya kebalikan dari itu. Pertimbangkan memakai pola `DB::transaction()` yang sama.
5. Lakukan redirect ke daftar peminjaman dengan pesan sukses.

### Yang Perlu Diperhatikan
- Pastikan `$fillable` model `Peminjaman` sudah mencakup semua kolom yang kamu isi.
- Penulisan status harus sama persis (huruf besar/kecil) dengan yang dicek di method lain dan di view.
- Jika stok tidak ikut berubah, laporan stok di dashboard akan salah.

### Cara Mengecek
Pengecekan dilakukan bersama TODO 9. Setelah transaksi tersimpan, periksa di phpMyAdmin: status sesuai, tanggal kembali masih kosong, dan stok buku yang dipinjam berkurang.


## TODO 8 - Form Tambah Buku

File: `resources/views/buku/create.blade.php`

### Tujuan
Membuat form Blade yang mengirim data ke controller dengan aman.

### Petunjuk Pengerjaan
1. Buka `resources/views/buku/edit.blade.php` atau `anggota/create.blade.php` sebagai contoh struktur dan class CSS yang dipakai di project ini.
2. Tentukan tujuan form (`action`) memakai helper `route()` dan nama route penyimpanan buku dari `php artisan route:list`. Tentukan juga method form yang sesuai.
3. Pelajari directive `@csrf`. Kenapa setiap form POST di Laravel membutuhkannya?
4. Buat input untuk setiap field yang disebutkan di komentar TODO. Atribut `name` harus sama dengan nama field yang divalidasi di TODO 5.
5. Pelajari helper `old()` agar isian tidak hilang ketika validasi gagal, serta directive `@error` untuk menampilkan pesan error per field.

### Yang Perlu Diperhatikan
- Error `419 Page Expired` berarti token CSRF tidak ada.
- Pilih tipe input yang tepat untuk field angka.
- Jangan lupa tombol submit dan tombol kembali ke daftar buku.

### Cara Mengecek
Buka halaman tambah buku dan pastikan semua input tampil. Submit dengan satu field kosong: field lain harus tetap terisi dan pesan error muncul. Submit dengan data lengkap: data harus masuk ke daftar buku.


## TODO 9 - Form Peminjaman

File: `resources/views/peminjaman/create.blade.php`

### Tujuan
Membuat form yang pilihannya diambil dari database menggunakan dropdown.

### Petunjuk Pengerjaan
1. Lihat method `create()` di `PeminjamanController`. Variabel apa saja yang dikirim ke view, dan isinya apa?
2. Siapkan form dengan `action`, method, dan `@csrf` seperti TODO 8, tetapi arahkan ke route penyimpanan peminjaman.
3. Buat elemen `<select>` untuk anggota dan buku. Gunakan `@foreach` untuk membuat setiap `<option>` dari variabel yang dikirim controller.
4. Pikirkan: nilai apa yang harus dikirim ke server (atribut `value`), dan teks apa yang ditampilkan agar admin mudah memilih?
5. Tambahkan input tanggal pinjam. Nama setiap field harus sesuai dengan yang divalidasi di TODO 7.

### Yang Perlu Diperhatikan
- Server membutuhkan ID, bukan nama atau judul.
- Tambahkan opsi awal kosong sebagai petunjuk agar admin tidak tanpa sengaja memilih item pertama.
- Buku dengan stok 0 sudah disaring oleh controller. Kamu tidak perlu menyaringnya lagi di view.

### Cara Mengecek
Buka halaman tambah peminjaman. Dropdown anggota dan buku harus berisi data dari database. Pilih satu anggota dan satu buku lalu simpan. Transaksi harus muncul di daftar peminjaman (setelah TODO 10 selesai).


## TODO 10 - Looping Daftar Peminjaman

File: `resources/views/peminjaman/index.blade.php`

### Tujuan
Menampilkan banyak data dalam tabel memakai looping Blade dan mengambil data dari tabel lain lewat relasi.

### Petunjuk Pengerjaan
1. Pelajari directive `@forelse` dan `@empty`. Apa bedanya dengan `@foreach`?
2. Lihat bagian `<thead>`: kolom apa saja yang harus diisi di setiap baris? Jumlah `<td>` harus sama dengan jumlah `<th>`.
3. Untuk nama anggota dan judul buku, manfaatkan relasi dari TODO 3. Jangan menampilkan angka ID.
4. Data dikirim dengan `paginate()`. Cari method pada objek paginator agar nomor urut tetap berlanjut di halaman berikutnya.
5. Kolom status dan aksi berbeda tergantung status transaksi. Gunakan `@if`. Untuk tombol pengembalian, ingat bahwa form HTML hanya mendukung GET dan POST. Pelajari directive `@method` dan lihat contoh form hapus di view lain.

### Yang Perlu Diperhatikan
- Jika data anggota atau buku sudah dihapus, relasi bisa bernilai `null` dan halaman menjadi error. Cari cara menampilkan nilai cadangan.
- Tanggal kembali bisa masih kosong. Tampilkan keterangan yang jelas, jangan dibiarkan kosong.
- Atur `colspan` pada baris kosong (`@empty`) agar sesuai jumlah kolom.

### Cara Mengecek
1. Saat belum ada transaksi, tabel menampilkan pesan bahwa data kosong.
2. Setelah ada transaksi, tabel menampilkan nama anggota dan judul buku.
3. Klik tombol pengembalian. Status harus berubah, tanggal kembali terisi, tombol berganti, dan stok buku bertambah kembali.
