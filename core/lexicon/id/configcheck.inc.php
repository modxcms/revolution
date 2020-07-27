<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Silakan hubungi administrator sistem dan memperingatkan mereka tentang pesan ini!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'allow_tags_in_post konteks pengaturan diaktifkan di luar \'mgr\'';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'Allow_tags_in_post menetapkan konteks diaktifkan dalam instalasi di luar mgr konteks. MODX merekomendasikan pengaturan ini dinonaktifkan kecuali Anda perlu secara eksplisit memungkinkan pengguna untuk mengirimkan Tag MODX, numerik entitas atau tag skrip HTML melalui metode POST form di situs Anda. Ini harus umumnya dinonaktifkan kecuali di mgr konteks.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post sistem pengaturan diaktifkan';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'Allow_tags_in_post pengaturan sistem diaktifkan dalam instalasi. MODX merekomendasikan pengaturan ini dinonaktifkan kecuali Anda perlu secara eksplisit memungkinkan pengguna untuk mengirimkan Tag MODX, numerik entitas atau tag skrip HTML melalui metode POST form di situs Anda. Ada baiknya untuk mengaktifkan ini melalui pengaturan konteks konteks khusus.';
$_lang['configcheck_cache'] = 'Direktori cache tidak dapat ditulisi';
$_lang['configcheck_cache_msg'] = 'MODX tidak dapat menulis ke direktori cache. MODX akan masih berfungsi seperti yang diharapkan, tetapi tidak cache akan berlangsung. Untuk mengatasinya, membuat /_cache/ direktori ditulisi.';
$_lang['configcheck_configinc'] = 'File konfigurasi masih dapat ditulisi!';
$_lang['configcheck_configinc_msg'] = 'Situs Anda rentan terhadap hacker yang bisa melakukan banyak kerusakan ke situs. Silakan membuat file config Anda baca saja! Jika Anda tidak admin situs, silakan hubungi administrator sistem dan memperingatkan mereka tentang pesan ini! Terletak di [[+path]]';
$_lang['configcheck_default_msg'] = 'Peringatan aneh yang tidak ditentukan telah ditemukan.';
$_lang['configcheck_errorpage_unavailable'] = 'Kesalahan halaman pada situs Anda ini tidak tersedia.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Ini berarti bahwa halaman kesalahan ini tidak dapat diakses kepada peselancar normal web atau tidak ada. Hal ini dapat mengakibatkan rekursif perulangan kondisi dan banyak kesalahan dalam log situs Anda. Pastikan ada tidak ada kelompok webuser yang ditugaskan ke halaman.';
$_lang['configcheck_errorpage_unpublished'] = 'Kesalahan halaman pada situs Anda tidak dipublikasikan atau tidak ada.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Ini berarti bahwa kesalahan halaman Anda tidak dapat diakses umum. Mempublikasikan halaman atau pastikan diberikan dokumen yang ada di pohon situs Anda dalam sistem &gt; sistem pengaturan menu.';
$_lang['configcheck_htaccess'] = 'Folder core dapat diakses publik';
$_lang['configcheck_htaccess_msg'] = 'MODX detected that your core folder is (partially) accessible to the public.
<strong>This is not recommended and a security risk.</strong>
If your MODX installation is running on a Apache webserver
you should at least set up the .htaccess file inside the core folder <em>[[+fileLocation]]</em>.
This can be easily done by renaming the existing ht.access example file there to .htaccess.
<p>There are other methods and webservers you may use, please read the <a href="https://docs.modx.com/current/en/getting-started/maintenance/securing-modx">Hardening MODX Guide</a>
for further information about securing your site.</p>
If you setup everything correctly, browsing e.g. to the <a href="[[+checkUrl]]" target="_blank">Changelog</a>
should give you a 403 (permission denied) or better a 404 (not found). If you can see the changelog
there in the browser, something is still wrong and you need to reconfigure or call an expert to solve this.';
$_lang['configcheck_images'] = 'Direktori gambar tidak bisa ditulisi';
$_lang['configcheck_images_msg'] = 'Direktori gambar tidak bisa ditulisi, atau tidak ada. Ini berarti fungsi manajer gambar dalam editor tidak akan bekerja!';
$_lang['configcheck_installer'] = 'Installer masih ada';
$_lang['configcheck_installer_msg'] = 'Setup / direktori berisi installer untuk MODX. Bayangkan apa yang mungkin terjadi jika orang jahat menemukan folder ini dan menjalankan installer! Mereka mungkin tidak akan mendapatkan terlalu jauh, karena mereka akan perlu untuk memasukkan beberapa pengguna informasi untuk database, tapi masih terbaik untuk menghapus folder ini dari server Anda. Terletak di: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Jumlah entri dalam file bahasa';
$_lang['configcheck_lang_difference_msg'] = 'Bahasa yang dipilih saat ini memiliki nomor yang berbeda dari entri dari bahasa default. Sementara tidak masalah, ini mungkin berarti file bahasa yang perlu dimutakhirkan.';
$_lang['configcheck_notok'] = 'Satu atau lebih rincian konfigurasi tidak memeriksa OK: ';
$_lang['configcheck_ok'] = 'Check sudah OK - tidak ada peringatan untuk laporan.';
$_lang['configcheck_phpversion'] = 'Versi PHP terlalu rendah';
$_lang['configcheck_phpversion_msg'] = 'Versi PHP [[+phpversion]] di server Anda tidak lagi dilanjutkan oleh para pengembang PHP, yang berarti tidak tersedia pembaruan. Hal ini juga dapat berarti bahwa MODX atau berbagai addon yang ada sekarang tidak lagi mendukung versi PHP tersebut. Perbarui server Anda, setidaknya gunakan PHP [[+phprequired]] untuk mengamankan situs Anda.';
$_lang['configcheck_register_globals'] = 'register_globals diatur ke ON di file konfigurasi php.ini Anda';
$_lang['configcheck_register_globals_msg'] = 'Konfigurasi ini membuat situs Anda lebih rentan terhadap serangan Cross Site Scripting (XSS). Anda harus bicara dengan host Anda tentang apa yang dapat Anda lakukan untuk menonaktifkan setelan ini.';
$_lang['configcheck_title'] = 'Konfigurasi check';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Halaman yang tidak sah pada situs Anda tidak dipublikasikan atau tidak ada.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Ini berarti bahwa halaman Anda yang tidak sah tidak dapat diakses oleh peselancar normal web atau tidak ada. Hal ini dapat mengakibatkan rekursif perulangan kondisi dan banyak kesalahan dalam log situs Anda. Pastikan ada tidak ada kelompok webuser yang ditugaskan ke halaman.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Halaman tidak sah didefinisikan dalam pengaturan konfigurasi situs tidak dipublikasikan.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Ini berarti bahwa halaman Anda yang tidak sah tidak dapat diakses umum. Mempublikasikan halaman atau pastikan diberikan dokumen yang ada di pohon situs Anda dalam sistem &gt; sistem pengaturan menu.';
$_lang['configcheck_warning'] = 'Peringatan konfigurasi:';
$_lang['configcheck_what'] = 'Apa artinya ini?';
