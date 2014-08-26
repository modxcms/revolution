<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX memerlukan ekstensi mysql untuk PHP dan tidak muncul dimuat.';
$_lang['mysql_err_pdo'] = 'MODX requires the pdo_mysql driver when native PDO is being used and it does not appear to be loaded.';
$_lang['mysql_version_5051'] = 'MODX akan punya masalah pada MySQL versi ([[+ versi]]) Anda, karena banyak bug yang berkaitan dengan driver PDO pada versi ini. Silakan upgrade MySQL untuk menambal masalah ini. Bahkan jika Anda memilih untuk tidak menggunakan MODX, dianjurkan Anda meng-upgrade ke versi ini untuk keamanan dan stabilitas dari situs Anda sendiri.';
$_lang['mysql_version_client_nf'] = 'MODX tidak bisa mendeteksi MySQL versi klien melalui mysql_get_client_info(). Silakan secara manual membuat yakin bahwa klien MySQL versi setidaknya 4.1.20 sebelum melanjutkan.';
$_lang['mysql_version_client_start'] = 'Memeriksa versi klien MySQL:';
$_lang['mysql_version_client_old'] = 'MODX mungkin memiliki masalah karena Anda menggunakan versi klien MySQL sangat lama ([[+ versi]]). MODX akan memungkinkan instalasi menggunakan versi klien MySQL ini, tetapi kami tidak dapat menjamin semua fungsi akan tersedia atau bekerja dengan baik ketika menggunakan versi MySQL klien Perpustakaan.';
$_lang['mysql_version_fail'] = 'Anda menjalankan pada MySQL [[+ versi]], dan MODX revolusi memerlukan MySQL 4.1.20 atau yang lebih baru. Silakan upgrade MySQL ke setidaknya 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODX tidak bisa mendeteksi server MySQL versi melalui mysql_get_server_info(). Silakan secara manual membuat yakin bahwa server MySQL versi setidaknya 4.1.20 sebelum melanjutkan.';
$_lang['mysql_version_server_start'] = 'Memeriksa server MySQL versi:';
$_lang['mysql_version_success'] = 'Oke! Menjalankan: [[+ versi]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';