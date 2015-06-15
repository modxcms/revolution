<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Checking if <span class="mono">[[+file]]</span> exists and is writable: ';
$_lang['test_config_file_nw'] = 'Untuk menginstal Linux/Unix baru, silakan membuat sebuah file kosong yang bernama <span class="mono"> [[+ kunci]].inc.php</span> dalam inti MODX Anda <span class="mono"> config /</span> direktori dengan izin diatur menjadi ditulisi oleh PHP.';
$_lang['test_db_check'] = 'Creating connection to the database: ';
$_lang['test_db_check_conn'] = 'Check the connection details and try again.';
$_lang['test_db_failed'] = 'Database connection failed!';
$_lang['test_db_setup_create'] = 'Setup will attempt to create the database.';
$_lang['test_dependencies'] = 'Checking PHP for zlib dependency: ';
$_lang['test_dependencies_fail_zlib'] = 'Your PHP installation does not have the "zlib" extension installed. This extension is necessary for MODX to run. Please enable it to continue.';
$_lang['test_directory_exists'] = 'Checking if <span class="mono">[[+dir]]</span> directory exists: ';
$_lang['test_directory_writable'] = 'Checking if <span class="mono">[[+dir]]</span> directory is writable: ';
$_lang['test_memory_limit'] = 'Checking if memory limit is set to at least 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX ditemukan Anda memory_limit pengaturan pada [[+ memori]], di bawah pengaturan yang disarankan 24 m. MODX berusaha untuk mengatur memory_limit ke 24M, tetapi tidak berhasil. Silakan set pengaturan memory_limit di file php.ini Anda untuk setidaknya 24M atau lebih tinggi sebelum melanjutkan. Jika Anda masih mengalami masalah (seperti mendapatkan layar putih kosong menginstal), diatur untuk 32 M, 64 M atau lebih tinggi.';
$_lang['test_memory_limit_success'] = 'Oke! Diatur ke [[+ memori]]';
$_lang['test_mysql_version_5051'] = 'MODX akan punya masalah pada MySQL versi ([[+ versi]]) Anda, karena banyak bug yang berkaitan dengan driver PDO pada versi ini. Silakan upgrade MySQL untuk menambal masalah ini. Bahkan jika Anda memilih untuk tidak menggunakan MODX, dianjurkan Anda meng-upgrade ke versi ini untuk keamanan dan stabilitas dari situs Anda sendiri.';
$_lang['test_mysql_version_client_nf'] = 'Tidak bisa mendeteksi MySQL versi klien!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX tidak bisa mendeteksi MySQL versi klien melalui mysql_get_client_info(). Silakan secara manual membuat yakin bahwa klien MySQL versi setidaknya 4.1.20 sebelum melanjutkan.';
$_lang['test_mysql_version_client_old'] = 'MODX mungkin memiliki masalah karena Anda menggunakan versi klien MySQL sangat lama ([[+ versi]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX akan memungkinkan instalasi menggunakan versi klien MySQL ini, tetapi kami tidak dapat menjamin semua fungsi akan tersedia atau bekerja dengan baik ketika menggunakan versi MySQL klien Perpustakaan.';
$_lang['test_mysql_version_client_start'] = 'Memeriksa versi klien MySQL:';
$_lang['test_mysql_version_fail'] = 'Anda menjalankan pada MySQL [[+ versi]], dan MODX revolusi memerlukan MySQL 4.1.20 atau yang lebih baru. Silakan upgrade MySQL ke setidaknya 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Tidak bisa mendeteksi server MySQL versi!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX tidak bisa mendeteksi server MySQL versi melalui mysql_get_server_info(). Silakan secara manual membuat yakin bahwa server MySQL versi setidaknya 4.1.20 sebelum melanjutkan.';
$_lang['test_mysql_version_server_start'] = 'Memeriksa server MySQL versi:';
$_lang['test_mysql_version_success'] = 'Oke! Menjalankan: [[+ versi]]';
$_lang['test_nocompress'] = 'Memeriksa jika kami harus menonaktifkan CSS/JS kompresi: ';
$_lang['test_nocompress_disabled'] = 'Oke! Menonaktifkan.';
$_lang['test_nocompress_skip'] = 'Tidak dipilih, melewatkan tes.';
$_lang['test_php_version_fail'] = 'Anda menjalankan pada PHP [[+ versi]], dan MODX revolusi memerlukan PHP 5.1.1 atau kemudian. Silakan upgrade PHP ke setidaknya 5.1.1. MODX merekomendasikan upgrade ke setidaknya 5.3.2+.';
$_lang['test_php_version_516'] = 'MODX akan punya masalah pada versi PHP ([[+ versi]]) Anda karena banyak bug yang berkaitan dengan driver PDO pada versi ini. Silakan upgrade PHP ke versi 5.3.0 atau lebih tinggi, yang patch masalah ini. MODX merekomendasikan upgrade ke setidaknya 5.3.2+. Bahkan jika Anda memilih untuk tidak menggunakan MODX, dianjurkan Anda meng-upgrade ke versi ini untuk keamanan dan stabilitas dari situs Anda sendiri.';
$_lang['test_php_version_520'] = 'MODX akan punya masalah pada versi PHP ([[+ versi]]) Anda karena banyak bug yang berkaitan dengan driver PDO pada versi ini. Silakan upgrade PHP ke versi 5.3.0 atau lebih tinggi, yang patch masalah ini. MODX merekomendasikan upgrade ke setidaknya 5.3.2+. Bahkan jika Anda memilih untuk tidak menggunakan MODX, dianjurkan Anda meng-upgrade ke versi ini untuk keamanan dan stabilitas dari situs Anda sendiri.';
$_lang['test_php_version_start'] = 'Checking PHP version:';
$_lang['test_php_version_success'] = 'Oke! Menjalankan: [[+ versi]]';
$_lang['test_safe_mode_start'] = 'Memeriksa untuk memastikan safe_mode dinonaktifkan:';
$_lang['test_safe_mode_fail'] = 'MODX telah menemukan keberadaan safe_mode. Anda harus menonaktifkan safe_mode dalam konfigurasi PHP Anda untuk melanjutkan.';
$_lang['test_sessions_start'] = 'Checking if sessions are properly configured:';
$_lang['test_simplexml'] = 'Memeriksa SimpleXML:';
$_lang['test_simplexml_nf'] = 'Tidak dapat menemukan SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX tidak bisa menemukan SimpleXML pada lingkungan PHP Anda. Manajemen paket dan fungsi lainnya tidak akan bekerja tanpa ini diinstal. Anda dapat melanjutkan instalasi, tetapi MODX merekomendasikan memungkinkan SimpleXML untuk fitur-fitur canggih dan fungsi.';
$_lang['test_suhosin'] = 'Memeriksa isu-isu suhosin:';
$_lang['test_suhosin_max_length'] = 'Suhosin mendapatkan nilai maks terlalu rendah!';
$_lang['test_suhosin_max_length_err'] = 'Saat ini, Anda menggunakan ekstensi PHP suhosin, dan suhosin.get.max_value_length Anda diatur terlalu rendah untuk MODX untuk benar kompres file JS di manager. MODX merekomendasikan upping nilai tersebut 4096; sampai saat itu, MODX akan secara otomatis ditetapkan JS kompresi (compress_js pengaturan) ke 0 untuk mencegah kesalahan.';
$_lang['test_table_prefix'] = 'Checking table prefix `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Table prefix is already in use in this database!';
$_lang['test_table_prefix_inuse_desc'] = 'Setup couldn\'t install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.';
$_lang['test_table_prefix_nf'] = 'Table prefix does not exist in this database!';
$_lang['test_table_prefix_nf_desc'] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table_prefix, and run Setup again.';
$_lang['test_zip_memory_limit'] = 'Checking if memory limit is set to at least 24M for zip extensions: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX found your memory_limit setting to be below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 24M or higher before proceeding, so that the zip extensions can work properly.';