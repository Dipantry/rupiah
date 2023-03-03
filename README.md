# All-in-one Indonesian Rupiah library

[![Latest Version](https://img.shields.io/github/v/release/dipantry/rupiah?label=Release&sort=semver&style=flat-square)](https://github.com/dipantry/rupiah/releases)
[![Packagist Version](https://img.shields.io/packagist/v/dipantry/rupiah?label=Packagist)](https://packagist.org/packages/dipantry/rupiah)
![PHP Version](https://img.shields.io/packagist/php-v/dipantry/rupiah?label=PHP%20Version)
[![MIT Licensed](https://img.shields.io/github/license/dipantry/rajaongkir?label=License&style=flat-square)](LICENSE)<br>
![run-tests](https://github.com/dipantry/rupiah/workflows/run-tests/badge.svg)
[![StyleCI](https://github.styleci.io/repos/483157402/shield?branch=master)](https://github.styleci.io/repos/483157402?branch=master)

Package Laravel atau Lumen untuk mengkonversi kurs rupiah dengan nilai tukar negara asing berdasarkan data dari [Bank Indonesia](https://www.bi.go.id/id/moneter/informasi-kurs/transaksi-bi/Default.aspx). Package ini akan membantu Anda untuk mengambil informasi dari API Bank Indonesia. Selain itu, package ini mampu mengambil data bank terbaru berdasarkan website [Flip.id](https://flip.id/blog/kode-bank).

## Fitur
### Konversi
- [x] Mengkonversi nilai tukar rupiah terhadap mata uang asing
- [x] Mengkonversi nilai tukar mata uang asing terhadap rupiah
- [x] Mendapatkan data nilai tukar rupiah saat ini

### Bank
- [x] Mengambil data terbaru menggunakan command dan disimpan ke dalam database

### Lainnya
- [x] Mengubah angka rupiah menjadi tulisan Bahasa Indonesia

---
# Instalasi
```sh
composer require dipantry/rupiah
```

## Petunjuk Penggunaan
### Lumen
Dalam file `bootstrap/app.php`, uncomment baris berikut
```php
$app->withFacades();
$app->withEloquent();
```
dan daftarkan service provider dan alias/facade dengan menambahkan kode berikut
```php
$app->register(Dipantry\Rupiah\ServiceProvider::class);

// class_aliases
class_alias(Dipantry\Rupiah\Facade::class, 'Rupiah');
```

### Laravel
Dalam file `config/app.php`, masukkan baris berikut pada bagian `providers`
```php
'providers' => [
    ...
    Dipantry\Rupiah\ServiceProvider::class,
],
```
dan tambahkan baris berikut pada bagian `aliases`
```php
'aliases' => [
    'Rajaongkir' => Dipantry\Rupiah\Facade::class,
],
```

## Konfigurasi
```sh
php artisan vendor:publish --provider="Dipantry\Rupiah\ServiceProvider"
```

File konfigurasi terletak pada `config/rupiah.php`
```php
return [
    'table_prefix' => 'Untuk migrasi dan seeding data',
    'timeout' => 'Waktu timeout untuk setiap pemanggilan API',
    'max_retry' => 'Jumlah perulangan yang dilakukan jika terjadi error',
]
```

### Jalankan migrasi
```sh
php artisan migrate
```

### Jalankan seeder untuk mengisi data bank
```sh
php artisan rupiah:bank
```

---
## Data Bank
Pengambilan data bank dapat menggunakan command `rupiah:bank` dan mengakses database dapat menggunakan model `Bank` yang telah disediakan.
```php
use Dipantry\Rupiah\Models\Bank;

Bank::all();
```

## Data Kurs
### Inisiasi Rupiah
```php
\Rupiah::of(10000);

\Rupiah::of(10000)->getValue();
// 10000
```
Class Rupiah menerima parameter float sebagai value untuk diproses selanjutnya

### Nilai Tukar Rupiah
Terdapat 3 macam function untuk mendapatkan nilai tukar
- `exchangeRate()` untuk mendapatkan nilai tukar rupiah terhadap mata uang asing pada hari ini atau waktu yang ditentukan sendiri (Tidak bisa digunakan saat bank tutup). Menerima parameter code currency dan tanggal.
```php
use Dipantry\Rupiah\Enums\CurrencyCode;

\Rupiah::exchangeRate('USD');
// Mendapatkan nilai tukar rupiah terhadap USD pada hari ini
// ['buy' => 14000, 'sell' => 14200]

\Rupiah::exchangeRate('USD', '2021-01-01');
// Mendapatkan nilai tukar rupiah terhadap USD pada tanggal 1 Januari 2021
// ['buy' => 14000, 'sell' => 14200]

// atau

\Rupiah::exchangeRate(CurrencyCode::USD);
```

- `buy()` untuk mengubah nilai rupiah menjadi mata uang asing. Menerima parameter code currency. Method ini langsung mengembalikan nilai perkalian dengan value yang telah diinisiasi.
```php
use Dipantry\Rupiah\Enums\CurrencyCode;

$rupiah = \Rupiah::of(10000);
$rupiah->buy(CurrencyCode::USD);
// 0.71
```

- `sell()` untuk mengubah mata uang asing menjadi nilai rupiah. Menerima parameter code currency. Method ini langsung mengembalikan nilai perkalian dengan value yang telah diinisiasi.
```php
use Dipantry\Rupiah\Enums\CurrencyCode;

$rupiah = \Rupiah::of(10000);
$rupiah->sell(CurrencyCode::USD);
// 14200
```

## Terbilang
Method ini mengubah angka menjadi tulisan Bahasa Indonesia. Method ini langsung mengembalikan nilai string.
```php
\Rupiah::of(10000)->toWords();
// Sepuluh Ribu Rupiah
```

---
### Currency Code
Package ini menyediakan model `CurrencyCode` yang berisi kode mata uang asing yang dapat digunakan untuk mempermudah penggunaan.<br>
*Disarankan untuk menggunakan model ini untuk menghindari kesalahan penulisan kode mata uang asing.*

### Exception
- `HttpRequestException` <br> 
Exception ini didapat apabila terjadi error saat melakukan request ke API Bank Indonesia.
- `InvalidCurrencyCodeException` <br>
Exception ini didapat apabila kode mata uang asing yang dimasukkan tidak valid.

---
# Testing
Jalankan testing dengan menjalankan perintah berikut ini
```sh
vendor/bin/phpunit
```