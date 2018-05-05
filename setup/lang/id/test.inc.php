<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Memeriksa apakah <span class="mono">[[+file]]</span> ada dan dapat ditulis: ';
$_lang['test_config_file_nw'] = 'Untuk menginstal Linux/Unix baru, silakan membuat sebuah file kosong yang bernama <span class="mono"> [[+key]].inc.php</span> dalam inti MODX Anda <span class="mono"> config /</span> direktori dengan izin diatur menjadi ditulisi oleh PHP.';
$_lang['test_db_check'] = 'Membuat koneksi ke database: ';
$_lang['test_db_check_conn'] = 'Periksa rincian koneksi dan coba lagi.';
$_lang['test_db_failed'] = 'Koneksi database gagal!';
$_lang['test_db_setup_create'] = 'Setup akan mencoba untuk membuat database.';
$_lang['test_dependencies'] = 'Memeriksa PHP untuk ketergantungan zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'Instalasi PHP Anda tidak memiliki ekstensi "zlib" yang terpasang. Ekstensi ini diperlukan agar MODX dapat berjalan. Mohon aktifkan dilanjutkan.';
$_lang['test_directory_exists'] = 'Memeriksa apakah <span class="mono">[[+dir]]</span> direktori ada: ';
$_lang['test_directory_writable'] = 'Memeriksa apakah <span class="mono">[[+dir]]</span> direktori dapat ditulis: ';
$_lang['test_memory_limit'] = 'Memeriksa apakah batas memori diatur untuk setidaknya 24m: ';
$_lang['test_memory_limit_fail'] = 'MODX ditemukan Anda memory_limit pengaturan pada [[+memory]], di bawah pengaturan yang disarankan 24 m. MODX berusaha untuk mengatur memory_limit ke 24M, tetapi tidak berhasil. Silakan set pengaturan memory_limit di file php.ini Anda untuk setidaknya 24M atau lebih tinggi sebelum melanjutkan. Jika Anda masih mengalami masalah (seperti mendapatkan layar putih kosong menginstal), diatur untuk 32 M, 64 M atau lebih tinggi.';
$_lang['test_memory_limit_success'] = 'Oke! Diatur ke [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX akan punya masalah pada MySQL versi ([[+version]]) Anda, karena banyak bug yang berkaitan dengan driver PDO pada versi ini. Silakan upgrade MySQL untuk menambal masalah ini. Bahkan jika Anda memilih untuk tidak menggunakan MODX, dianjurkan Anda meng-upgrade ke versi ini untuk keamanan dan stabilitas dari situs Anda sendiri.';
$_lang['test_mysql_version_client_nf'] = 'Tidak bisa mendeteksi MySQL versi klien!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX tidak bisa mendeteksi MySQL versi klien melalui mysql_get_client_info(). Silakan secara manual membuat yakin bahwa klien MySQL versi setidaknya 4.1.20 sebelum melanjutkan.';
$_lang['test_mysql_version_client_old'] = 'MODX mungkin memiliki masalah karena Anda menggunakan versi klien MySQL sangat lama ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX akan memungkinkan instalasi menggunakan versi klien MySQL ini, tetapi kami tidak dapat menjamin semua fungsi akan tersedia atau bekerja dengan baik ketika menggunakan versi MySQL klien Perpustakaan.';
$_lang['test_mysql_version_client_start'] = 'Memeriksa versi klien MySQL:';
$_lang['test_mysql_version_fail'] = 'Anda menjalankan pada MySQL [[+version]], dan MODX revolusi memerlukan MySQL 4.1.20 atau yang lebih baru. Silakan upgrade MySQL ke setidaknya 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Tidak bisa mendeteksi server MySQL versi!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX tidak bisa mendeteksi server MySQL versi melalui mysql_get_server_info(). Silakan secara manual membuat yakin bahwa server MySQL versi setidaknya 4.1.20 sebelum melanjutkan.';
$_lang['test_mysql_version_server_start'] = 'Memeriksa server MySQL versi:';
$_lang['test_mysql_version_success'] = 'Oke! Menjalankan: [[+version]]';
$_lang['test_nocompress'] = 'Memeriksa jika kami harus menonaktifkan CSS/JS kompresi: ';
$_lang['test_nocompress_disabled'] = 'Oke! Menonaktifkan.';
$_lang['test_nocompress_skip'] = 'Tidak dipilih, melewatkan tes.';
$_lang['test_php_version_fail'] = 'Anda menjalankan pada PHP [[+version]], dan MODX revolusi memerlukan PHP [[+required]] atau kemudian. Silakan upgrade PHP ke setidaknya [[+required]]. MODX merekomendasikan upgrade ke setidaknya [[+recommended]]+.';
$_lang['test_php_version_start'] = 'Memeriksa versi PHP:';
$_lang['test_php_version_success'] = 'Oke! Menjalankan: [[+version]]';
$_lang['test_safe_mode_start'] = 'Memeriksa untuk memastikan safe_mode dinonaktifkan:';
$_lang['test_safe_mode_fail'] = 'MODX telah menemukan keberadaan safe_mode. Anda harus menonaktifkan safe_mode dalam konfigurasi PHP Anda untuk melanjutkan.';
$_lang['test_sessions_start'] = 'Memeriksa apakah sesi dikonfigurasi dengan benar:';
$_lang['test_simplexml'] = 'Memeriksa SimpleXML:';
$_lang['test_simplexml_nf'] = 'Tidak dapat menemukan SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX tidak bisa menemukan SimpleXML pada lingkungan PHP Anda. Manajemen paket dan fungsi lainnya tidak akan bekerja tanpa ini diinstal. Anda dapat melanjutkan instalasi, tetapi MODX merekomendasikan memungkinkan SimpleXML untuk fitur-fitur canggih dan fungsi.';
$_lang['test_suhosin'] = 'Memeriksa isu-isu suhosin:';
$_lang['test_suhosin_max_length'] = 'Suhosin mendapatkan nilai maks terlalu rendah!';
$_lang['test_suhosin_max_length_err'] = 'Saat ini, Anda menggunakan ekstensi PHP suhosin, dan suhosin.get.max_value_length Anda diatur terlalu rendah untuk MODX untuk benar kompres file JS di manager. MODX merekomendasikan upping nilai tersebut 4096; sampai saat itu, MODX akan secara otomatis ditetapkan JS kompresi (compress_js pengaturan) ke 0 untuk mencegah kesalahan.';
$_lang['test_table_prefix'] = 'Memeriksa tabel prefix `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Tabel prefix sudah digunakan dalam database ini!';
$_lang['test_table_prefix_inuse_desc'] = 'Penyiapan tidak dapat diinstal ke dalam database yang dipilih, karena sudah berisi tabel dengan awalan yang Anda tentukan. Silakan pilih table_prefix baru, dan jalankan Setup lagi.';
$_lang['test_table_prefix_nf'] = 'Tabel prefix tidak ada dalam database ini!';
$_lang['test_table_prefix_nf_desc'] = 'Penyiapan tidak dapat diinstal ke dalam database yang dipilih, karena tidak berisi tabel yang ada dengan awalan yang Anda tetapkan untuk ditingkatkan versinya. Silakan pilih table_prefix yang ada, dan jalankan Setup lagi.';
$_lang['test_zip_memory_limit'] = 'Memeriksa apakah batas memori diatur ke setidaknya 24M untuk ekstensi zip: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX menemukan pengaturan memory_limit Anda berada di bawah pengaturan 24M yang disarankan. MODX mencoba menyetel memory_limit ke 24M, namun tidak berhasil. Harap set setting memory_limit di file php.ini Anda ke 24M atau lebih tinggi sebelum melanjutkan, sehingga ekstensi zip dapat bekerja dengan baik.';