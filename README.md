# MAKARYA - Aplikasi Management Data Karyawan

![MAKARYA](https://opengraph.githubassets.com/bd1f6789e04602741733b10c5ed7be2e/idugeni/makarya)

<div align="center">
<img src="https://img.shields.io/github/stars/idugeni/makarya?style=social" alt="GitHub stars">
<img src="https://img.shields.io/github/forks/idugeni/makarya?style=social" alt="GitHub forks">
<img src="https://img.shields.io/github/issues/idugeni/makarya" alt="GitHub issues">
<img src="https://img.shields.io/github/license/idugeni/makarya" alt="GitHub license">
</div>

Aplikasi Management Data Karyawan ini dirancang untuk memberikan solusi lengkap dalam mengelola informasi karyawan di lingkungan perusahaan Anda. Dengan antarmuka yang intuitif dan fitur yang komprehensif, aplikasi ini memudahkan Anda untuk melakukan berbagai tugas administratif dengan cepat dan efisien.

## Kegunaan Aplikasi

* **Menyimpan dan mengelola data karyawan secara terstruktur:** Semua informasi terkait karyawan seperti nama, posisi, dan detail kontak disimpan dalam format yang terorganisir dengan baik di dalam database, memudahkan pencarian dan pengelolaan.
* **Menyediakan fitur pencarian:** Fitur pencarian yang canggih memungkinkan Anda untuk menemukan data karyawan dengan cepat berdasarkan kriteria tertentu seperti nama, ID, atau posisi.
* **Menampilkan informasi karyawan:** Data ditampilkan dalam format tabel yang jelas, dengan kemampuan untuk melihat detail lengkap karyawan dan melakukan tindakan yang diperlukan seperti memperbarui atau menghapus informasi.
* **Mendukung proses administrasi yang lebih cepat:** Antarmuka pengguna dirancang agar mudah digunakan dan memungkinkan Anda untuk melakukan tugas-tugas administratif dengan cepat dan efisien.
* **Memberikan notifikasi interaktif:** SweetAlert2 digunakan untuk memberikan umpan balik interaktif yang jelas kepada pengguna, seperti notifikasi sukses atau kesalahan yang muncul secara langsung di antarmuka pengguna.

## Teknologi yang Digunakan

* [PHP](https://www.php.net/): Bahasa pemrograman server-side yang digunakan untuk menangani logika aplikasi dan interaksi dengan database.
* [MySQL](https://www.mysql.com/): Sistem management basis data yang digunakan untuk menyimpan dan mengelola data karyawan dengan aman dan efisien.
* [Bootstrap](https://getbootstrap.com/): Framework CSS yang menyediakan desain antarmuka yang responsif dan modern, memastikan aplikasi terlihat baik di berbagai perangkat dan ukuran layar.
* [Font Awesome](https://fontawesome.com/): Koleksi ikon yang digunakan untuk meningkatkan antarmuka pengguna dengan ikon yang menarik dan fungsional.
* [SweetAlert2](https://sweetalert2.github.io/): Library JavaScript untuk menampilkan notifikasi interaktif yang memberikan umpan balik yang jelas kepada pengguna tentang status tindakan mereka.

## Fitur Utama

* **Pengelolaan Data Karyawan:** Kemampuan untuk menambah, mengedit, dan menghapus informasi karyawan. Setiap data karyawan dapat diperbarui dengan informasi terbaru atau dihapus jika tidak diperlukan lagi.
* **Pencarian Data:** Fitur pencarian yang memungkinkan Anda untuk dengan cepat menemukan informasi karyawan yang spesifik, menghemat waktu dalam mencari data di tabel besar.
* **Desain Responsif:** Antarmuka yang didesain untuk berfungsi dengan baik di perangkat apa pun, baik itu desktop, tablet, atau smartphone, memberikan pengalaman pengguna yang konsisten dan menyenangkan.
* **Notifikasi Interaktif:** Notifikasi yang diberikan melalui SweetAlert2 memberikan umpan balik yang cepat dan jelas kepada pengguna tentang tindakan yang dilakukan, seperti berhasil menambahkan karyawan baru atau kesalahan dalam formulir.

## Instalasi

1. **Clone Repository:** `git clone https://github.com/idugeni/makarya.git`
2. **Masuk ke Direktori Proyek:** `cd makarya`
3. **Konfigurasi Database:**
    * Buat database MySQL baru dan impor file schema yang tersedia di folder proyek untuk membuat tabel yang diperlukan.
    * Sesuaikan file konfigurasi database di `config.php` dengan kredensial database Anda, seperti nama database, username, dan password.
4. **Jalankan Server:**
    * Pastikan Anda memiliki server lokal seperti XAMPP atau WAMP yang terinstal di komputer Anda untuk menjalankan aplikasi PHP.
    * Letakkan folder proyek di dalam direktori `htdocs` (untuk XAMPP) atau `www` (untuk WAMP).
    * Jalankan server dan akses aplikasi melalui browser dengan membuka `https://localhost/makarya`.

## Kontribusi

Kami menyambut baik kontribusi dari komunitas. Jika Anda ingin berkontribusi pada pengembangan aplikasi ini, Anda dapat melakukan fork repositori ini dan mengirimkan pull request dengan perubahan atau perbaikan yang Anda buat. Pastikan untuk mendokumentasikan perubahan Anda dengan jelas dan mengikuti pedoman kontribusi yang ada di repositori.

## Lisensi

Aplikasi ini dilisensikan di bawah [Lisensi MIT](https://github.com/idugeni/makarya/blob/main/LICENSE). Lisensi ini memungkinkan Anda untuk menggunakan, menyalin, memodifikasi, atau mendistribusikan aplikasi ini dengan kebebasan penuh, selama Anda menyertakan salinan lisensi dan hak cipta yang sama dalam distribusi.
