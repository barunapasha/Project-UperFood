
# Project UperFood

Project UperFood merupakan project pemrograman web & appl yang dikembangkan oleh kelompok 6 untuk memenuhi syarat kelulusan mata kuliah ini.


## Installation

1. Pull project lalu lakukan composer download

```bash
  git pull [url repo ini]
  composer install
```
2. lalu pastikan kalian memiliki laragon di device tersebut.
3. diperlukan juga API dari midtrans karna project ini sudah di konfigurasi dengan API midtrans yang nantinya pembayaran benar benar bisa menggunakan Gopay, Ovo, Credit Card, VA Bank, dll.
4. setelah memiliki API midtrans, lakukan composer install midtrans pada project ini karna nantinya harus mengambil beberapa sumber dari midtrans.
```bash
composer midtrans install (cek kembali di web midtrans)
```
5. terakhir pastikan juga untuk melakukan npm run dev karna project ini menggunakan tailwindcss
6. terakhir tinggal jalankan project ini dari laragon atau dari terminal vscode
    
## Authors

- [@barunapasha](https://www.github.com/barunapasha)
- [@nurhumam](https://www.github.com/nurhumam)



## FAQ

#### Apakah project ini sudah ada akun admin?

Ya, gunakan admin@uperfood.com | admin123 sebagai email & password untuk mengakses halaman admin

#### Apakah project ini sudah terintegrasi dengan database?

Ya, anda bisa mengganti database project ini dengan database milik anda sendiri di env

