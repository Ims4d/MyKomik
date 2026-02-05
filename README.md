# MyKomik

MyKomik adalah platform manajemen dan pembaca komik berbasis web yang dibangun menggunakan framework Laravel. Aplikasi ini memungkinkan pengguna untuk membaca komik, memberikan rating, komentar, serta melacak progres membaca. Terdapat juga panel dashboard untuk manajemen data komik, chapter, genre, dan pengguna.

## Prasyarat

Sebelum memulai, pastikan sistem Anda memenuhi persyaratan berikut:

- PHP >= 8.2
- Composer
- Node.js & NPM
- Database (MySQL/PostgreSQL/SQLite)

## Cara Setup

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek di lingkungan lokal:

1. Clone repositori ini ke komputer Anda.

2. Masuk ke direktori proyek:
   ```bash
   cd MyKomik
   ```

3. Instal dependensi PHP menggunakan Composer:
   ```bash
   composer install
   ```

4. Instal dependensi JavaScript menggunakan NPM:
   ```bash
   npm install
   ```

5. Salin file konfigurasi environment:
   ```bash
   cp .env.example .env
   ```

6. Generate kunci aplikasi:
   ```bash
   php artisan key:generate
   ```

7. Konfigurasikan koneksi database di dalam file `.env`.

8. Jalankan migrasi database beserta seeder untuk data awal:
   ```bash
   php artisan migrate --seed
   ```

9. Buat simbolik link untuk penyimpanan file:
   ```bash
   php artisan storage:link
   ```

10. Jalankan server pengembangan:
    ```bash
    npm run dev
    ```

11. Buka terminal baru dan jalankan server Laravel:
    ```bash
    php artisan serve
    ```

Aplikasi sekarang dapat diakses melalui browser di alamat http://localhost:8000.