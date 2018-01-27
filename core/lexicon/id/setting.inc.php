<?php
/**
 * Setting English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Daerah';
$_lang['area_authentication'] = 'Otentikasi dan keamanan';
$_lang['area_caching'] = 'Caching';
$_lang['area_core'] = 'Kode inti';
$_lang['area_editor'] = 'Pengedit teks dengan kaya fitur';
$_lang['area_file'] = 'Sistem berkas';
$_lang['area_filter'] = 'Pilih berdasarkan area...';
$_lang['area_furls'] = 'URL yang berkaitan';
$_lang['area_gateway'] = 'Gateway';
$_lang['area_language'] = 'Kosa kata dan bahasa';
$_lang['area_mail'] = 'Mail';
$_lang['area_manager'] = 'Back-end Manajer';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Sesi dan Cookie';
$_lang['area_lexicon_string'] = 'Masuk daerah leksikon';
$_lang['area_lexicon_string_msg'] = 'Masukkan kunci masuk leksikon untuk area di sini. Jika tidak ada leksikon yang masuk, itu hanya akan menampilkan area kunci. <br /> bidang: otentikasi, caching, file, furls, gateway, bahasa, manajer, sesi, situs, sistem';
$_lang['area_site'] = 'Situs';
$_lang['area_system'] = 'Sistem dan Server';
$_lang['areas'] = 'Daerah';
$_lang['charset'] = 'Charset';
$_lang['country'] = 'Negara';
$_lang['description_desc'] = 'Deskripsi singkat dari pengaturan. Ini bisa menjadi sebuah catatan leksikon berdasarkan kunci, mengikuti format "setting_" + kunci "_desc".';
$_lang['key_desc'] = 'Kunci untuk pengaturan. Akan tersedia dalam konten Anda melalui [[++ kunci]] pengganti.';
$_lang['name_desc'] = 'Sebuah nama untuk menetapkan. Ini bisa menjadi sebuah catatan leksikon berdasarkan kunci, mengikuti format "setting_" + kunci.';
$_lang['namespace'] = 'Namespace';
$_lang['namespace_desc'] = 'Namespace ini berkaitan dengan pengaturan. Leksikon topik akan dimuat untuk Namespace ini ketika meraih pengaturan standar.';
$_lang['namespace_filter'] = 'pilih berdasarkan namespace...';
$_lang['search_by_key'] = 'Cari dengan kata kunci...';
$_lang['setting_create'] = 'Buat pengaturan baru';
$_lang['setting_err'] = 'Silakan periksa data Anda untuk bidang-bidang berikut: ';
$_lang['setting_err_ae'] = 'Pengaturan dengan kata kunci sudah ada. Silakan tentukan nama kunci lain.';
$_lang['setting_err_nf'] = 'Pengaturan tidak ditemukan.';
$_lang['setting_err_ns'] = 'Pengaturan tidak ditentukan';
$_lang['setting_err_remove'] = 'Terjadi kesalahan saat mencoba untuk menghapus pengaturan.';
$_lang['setting_err_save'] = 'Terjadi kesalahan saat mencoba untuk menyimpan pengaturan.';
$_lang['setting_err_startint'] = 'Pengaturan mungkin tidak dimulai dengan bilangan bulat.';
$_lang['setting_err_invalid_document'] = 'Ada tidak ada dokumen dengan ID %d. Silakan tentukan dokumen yang ada.';
$_lang['setting_remove'] = 'Hapus pengaturan';
$_lang['setting_remove_confirm'] = 'Apakah Anda yakin Anda ingin menghapus pengaturan ini? Ini mungkin akan menghentikan instalasi MODX.';
$_lang['setting_update'] = 'Perbaharui pengaturan';
$_lang['settings_after_install'] = 'Sebagai instalasi baru, Anda diminta untuk mengendalikan pengaturan ini, dan merubah apapun yang mungkin Anda ingin. Setelah Anda telah menguasai pengaturan, tekan \'Simpan\' untuk memperbarui pengaturan database. <br /><br />';
$_lang['settings_desc'] = 'Di sini Anda dapat mengatur preferensi Umum dan pengaturan konfigurasi untuk pengaturan antarmuka MODX, serta bagaimana situs MODX Anda berjalan. Klik dua kali pada kolom nilai pada setelan yang ingin Anda edit secara dinamis mengedit melalui grid, atau klik kanan pada setelan untuk opsi lebih lanjut. Anda juga dapat mengklik tanda "+" untuk keterangan pengaturan.';
$_lang['settings_furls'] = 'URL yang berkaitan';
$_lang['settings_misc'] = 'Bermacam-macam';
$_lang['settings_site'] = 'Situs';
$_lang['settings_ui'] = 'Antarmuka &amp; fitur';
$_lang['settings_users'] = 'Pengguna';
$_lang['system_settings'] = 'Pengaturan sistem';
$_lang['usergroup'] = 'Kelompok pengguna';

// user settings
$_lang['setting_access_category_enabled'] = 'Periksa akses kategori';
$_lang['setting_access_category_enabled_desc'] = 'Gunakan ini untuk mengaktifkan atau menonaktifkan pemeriksaan ACL kategori (per konteks). <strong>Catatan: jika opsi ini disetel tidak ada, maka semua kategori izin akses akan diabaikan!</strong>';

$_lang['setting_access_context_enabled'] = 'Periksa akses konteks';
$_lang['setting_access_context_enabled_desc'] = 'Gunakan ini untuk mengaktifkan atau menonaktifkan pemeriksaan konteks ACL. <strong>Catatan: jika opsi ini disetel tidak ada, maka semua izin akses konteks akan diabaikan. JANGAN menonaktifkan seluruh sistem ini atau pengaturan konteks atau Anda akan menonaktifkan akses ke pengaturan antarmuka.</strong>';

$_lang['setting_access_resource_group_enabled'] = 'Periksa akses sumber grup';
$_lang['setting_access_resource_group_enabled_desc'] = 'Gunakan ini untuk mengaktifkan atau menonaktifkan pemeriksaan grup sumber ACL (per konteks). <strong>Catatan: jika opsi ini disetel tidak ada, maka semua izin akses grup sumber daya akan diabaikan!</strong>';

$_lang['setting_allow_mgr_access'] = 'Akses pengaturan antarmuka';
$_lang['setting_allow_mgr_access_desc'] = 'Gunakan pilihan ini untuk mengaktifkan atau menonaktifkan akses ke pengaturan antarmuka. <strong>Catatan: jika opsi ini disetel tidak ada, maka pengguna akan diarahkan untuk memulai pengaturan logn atau halaman web situs dimulai.</strong>';

$_lang['setting_failed_login'] = 'Upaya login gagal';
$_lang['setting_failed_login_desc'] = 'Di sini Anda dapat memasukkan nomor login yang gagal yang diperbolehkan sebelum pengguna diblokir.';

$_lang['setting_login_allowed_days'] = 'Hari yang diperbolehkan';
$_lang['setting_login_allowed_days_desc'] = 'Pilih hari-hari yang diperbolehkan untuk login bagi si pengguna ini.';

$_lang['setting_login_allowed_ip'] = 'Alamat IP diperbolehkan';
$_lang['setting_login_allowed_ip_desc'] = 'Masukkan dari alamat IP yang pengguna ini diperbolehkan untuk login. <strong>Catatan: Pisahkan beberapa alamat IP dengan koma (,)</strong>';

$_lang['setting_login_homepage'] = 'Halaman Login';
$_lang['setting_login_homepage_desc'] = 'Masukkan ID dokumen yang ingin Anda kirimkan pengguna setelah ia telah log in. <strong>Catatan: Pastikan Anda memasukkan ID milik dokumen yang ada, dan bahwa ia telah diterbitkan dan dapat diakses oleh pengguna ini!</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Versi skema akses kebijakan ';
$_lang['setting_access_policies_version_desc'] = 'Versi sistem kebijakan akses. JANGAN DIUBAH.';

$_lang['setting_allow_forward_across_contexts'] = 'Memungkinkan penerusan di seluruh konteks';
$_lang['setting_allow_forward_across_contexts_desc'] = 'Ketika benar, symlink dan modX::sendForward() API panggilan dapat meneruskan permintaan sumber daya di konteks lainnya.';

$_lang['setting_allow_manager_login_forgot_password'] = 'Memungkinkan lupa Password di layar Login Manager';
$_lang['setting_allow_manager_login_forgot_password_desc'] = 'Pengaturan ini ke "Tidak" akan menonaktifkan kemampuan lupa sandi pada layar login manajer.';

$_lang['setting_allow_tags_in_post'] = 'Memungkinkan Tag dalam posting';
$_lang['setting_allow_tags_in_post_desc'] = 'Jika salah, Semua POST yang bervariasi akan dicopot oleh penanda tulisan HTML, numerik entitas dan penanda MODX. MODX merekomendasikan untuk meninggalkan pengaturan ini ke salah untuk konteks lain manager, dimana diatur ke benar dengan standarnya.';

$_lang['setting_allow_tv_eval'] = 'Disable eval in TV binding';
$_lang['setting_allow_tv_eval_desc'] = 'Select this option to enable or disable eval in TV binding. If this option is set to no, the code/value will just be handled as regular text.';

$_lang['setting_anonymous_sessions'] = 'Session Anonymous';
$_lang['setting_anonymous_sessions_desc'] = 'Jika dinonaktifkan, hanya user yang sudah login akan mendapatkan PHP session. Hal ini dapat mengurangi overhead untuk user anonymus serta mengurangi beban pada situs MODX, jika memang mereka tidak perlu memiliki session PHP yang unik. Jika nilai session_enabled "false", maka hal ini tidak akan berpengaruh karena session PHP tidak pernah dihidupkan.';

$_lang['setting_archive_with'] = 'Kekuatan arsip PCLZip';
$_lang['setting_archive_with_desc'] = 'Jika benar, akan menggunakan PCLZip bukan ZipArchive sebagai ekstensi zip. Hidupkan ini jika Anda mendapatkan kesalahan extractTo atau mengalami masalah dengan unzipping dalam manajemen paket.';

$_lang['setting_auto_menuindex'] = 'Menu pengindeksan standar';
$_lang['setting_auto_menuindex_desc'] = 'Pilih \'Ya\' untuk menghidupkan menu otomatis menaikan indeks secara standar.';

$_lang['setting_auto_check_pkg_updates'] = 'Memeriksa otomatis pembaruan paket';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Jika \'Ya\', MODX akan secara otomatis memeriksa pembaruan untuk paket di manajemen paket. Hal ini mungkin akan melambatkan pemuatan jaringan.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Waktu kedaluwarsa cache untuk paket otomatis pemeriksaan pembaruan';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'Jumlah menit yang manajemen paket akan mencache hasilnya untuk memeriksa pembaruan paket.';

$_lang['setting_allow_multiple_emails'] = 'Memungkinkan duplikasi email untuk pengguna';
$_lang['setting_allow_multiple_emails_desc'] = 'Jika diaktifkan, pengguna dapat berbagi alamat email yang sama.';

$_lang['setting_automatic_alias'] = 'Secara otomatis menghasilkan alias';
$_lang['setting_automatic_alias_desc'] = 'Pilih \'Ya\' untuk memiliki sistem yang secara otomatis menghasilkan alias berdasarkan judul halaman sumber daya saat menyimpan.';

$_lang['setting_base_help_url'] = 'Bantuan dasar URL';
$_lang['setting_base_help_url_desc'] = 'URL dasar yang digunakan untuk membangun link bantuan di bagian atas kanan dari halaman di manager.';

$_lang['setting_blocked_minutes'] = 'Menit yang diblokir';
$_lang['setting_blocked_minutes_desc'] = 'Di sini Anda dapat memasukkan jumlah menit dimana pengguna akan diblokir jika mereka mencapai jumlah maksimum dari izin percobaan login yang gagal. Masukkan nilai ini sebagai angka itu saja (tidak koma, spasi dll.)';

$_lang['setting_cache_action_map'] = 'Mengaktifkan cache tindakan peta';
$_lang['setting_cache_action_map_desc'] = 'Bila diaktifkan, tindakan (atau peta controller) di-cache untuk mengurangi beban halaman pengelola.';

$_lang['setting_cache_alias_map'] = 'Mengaktifkan cache konteks Alias peta';
$_lang['setting_cache_alias_map_desc'] = 'Ketika diaktifkan, Semua URI sumber daya cache ke dalam konteks. Mengaktifkan situs yang lebih kecil dan menonaktifkan pada situs yang lebih besar untuk kinerja yang lebih baik.';

$_lang['setting_use_context_resource_table'] = 'Use the context resource table';
$_lang['setting_use_context_resource_table_desc'] = 'When enabled, context refreshes use the context_resource table. This enables you to programmatically have one resource in multiple contexts. If you do not use those multiple resource contexts via the API, you can set this to false. On large sites you will get a potential performance boost in the manager then.';

$_lang['setting_cache_context_settings'] = 'Mengaktifkan konteks pengaturan Cache';
$_lang['setting_cache_context_settings_desc'] = 'Bila diaktifkan, pengaturan konteks akan di-cache untuk mengurangi beban.';

$_lang['setting_cache_db'] = 'Mengaktifkan Database Cache';
$_lang['setting_cache_db_desc'] = 'Bila diaktifkan, benda-benda dan hasil mentah set dari SQL query cache secara signifikan mengurangi beban database.';

$_lang['setting_cache_db_expires'] = 'Waktu kedaluwarsa untuk DB Cache';
$_lang['setting_cache_db_expires_desc'] = 'Nilai ini (dalam detik) menetapkan jumlah waktu cache file terakhir untuk DB Cache hasil-yang ditetapkan.';

$_lang['setting_cache_db_session'] = 'Mengaktifkan Database sesi Cache';
$_lang['setting_cache_db_session_desc'] = 'Ketika diaktifkan, dan cache_db diaktifkan, sesi database akan di-cache dalam DB cache hasil-yang ditetapkan.';

$_lang['setting_cache_db_session_lifetime'] = 'Waktu kedaluwarsa untuk sesi DB Cache';
$_lang['setting_cache_db_session_lifetime_desc'] = 'Nilai ini (dalam detik) menetapkan jumlah waktu cache file terakhir untuk sesi entri dalam DB cache hasil-yang ditetapkan.';

$_lang['setting_cache_default'] = 'Standar cache';
$_lang['setting_cache_default_desc'] = 'Pilih \'Ya\' untuk membuat semua sumber daya baru yang dapat disimpan di cache secara default.';
$_lang['setting_cache_default_err'] = 'Silakan Anda nyatakan atau tidak dokumen yang di-cache secara default.';

$_lang['setting_cache_disabled'] = 'Menonaktifkan opsi Global Cache';
$_lang['setting_cache_disabled_desc'] = 'Pilih \'Ya\' untuk menonaktifkan semua MODX caching fitur. MODX tidak merekomendasikan menonaktifkan caching.';
$_lang['setting_cache_disabled_err'] = 'Silakan Anda nyatakan, cache diaktifkan atau tidak.';

$_lang['setting_cache_expires'] = 'Waktu kedaluwarsa untuk Cache standar';
$_lang['setting_cache_expires_desc'] = 'Nilai ini (dalam detik) menetapkan jumlah waktu cache file terakhir untuk caching standar.';

$_lang['setting_cache_format'] = 'Format cache untuk penggunaan';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = cerita bersambung. Salah satu format';

$_lang['setting_cache_handler'] = 'Kelas caching Handler';
$_lang['setting_cache_handler_desc'] = 'Nama kelas handler jenis untuk menggunakan untuk caching.';

$_lang['setting_cache_lang_js'] = 'Cache leksikon JS string';
$_lang['setting_cache_lang_js_desc'] = 'Jika diatur ke benar, ini akan menggunakan header server untuk men-cache senar leksikon yang dimuat ke JavaScript untuk antar muka pengelolaan.';

$_lang['setting_cache_lexicon_topics'] = 'Topik cache leksikon';
$_lang['setting_cache_lexicon_topics_desc'] = 'Ketika diaktifkan, semua topik leksikon akan di-cache sehingga sangat mengurangi beban yang diperlukan untuk fungsi internasionalisasi. MODX sangat menyarankan meninggalkan pengaturan ini ke \'Ya\'.';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Topik cache leksikon non-inti';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Ketika dinonaktifkan, topik lexicon non-inti akan tidak di-cache. Hal ini berguna untuk menonaktifkan ketika mengembangkan Ekstra Anda sendiri.';

$_lang['setting_cache_resource'] = 'Mengaktifkan sumber daya parsial Cache';
$_lang['setting_cache_resource_desc'] = 'Sumber daya parsial caching dikonfigurasi oleh sumber daya ketika fitur ini diaktifkan.  Menonaktifkan fitur ini akan menonaktifkan itu secara global.';

$_lang['setting_cache_resource_expires'] = 'Waktu kedaluwarsa untuk sumber daya parsial Cache';
$_lang['setting_cache_resource_expires_desc'] = 'Nilai ini (dalam detik) menetapkan jumlah waktu cache file terakhir untuk sebagian sumber daya caching.';

$_lang['setting_cache_scripts'] = 'Mengaktifkan Script Cache';
$_lang['setting_cache_scripts_desc'] = 'Bila diaktifkan, MODX akan men-cache semua skrip (potongan dan plugin) ke file untuk mengurangi waktu pemuatan. MODX merekomendasikan meninggalkan ini diatur ke \'Ya\'.';

$_lang['setting_cache_system_settings'] = 'Mengaktifkan sistem pengaturan Cache';
$_lang['setting_cache_system_settings_desc'] = 'Bila diaktifkan, pengaturan sistem akan di-cache untuk mengurangi waktu loading. MODX merekomendasikan untuk meninggalkan .';

$_lang['setting_clear_cache_refresh_trees'] = 'Menyegarkan tree si situs cache dihapus';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'Ketika diaktifkan, akan menyegarkan trees setelah membersihkan cache situs.';

$_lang['setting_compress_css'] = 'Menggunakan CSS terkompresi';
$_lang['setting_compress_css_desc'] = 'Ketika ini diaktifkan, MODX akan menggunakan versi kompresi CSS stylesheet di antarmuka manajer. Hal ini sangat mengurangi beban dan waktu pelaksanaan dalam pengelola. Menonaktifkan hanya jika Anda memodifikasi elemen inti.';

$_lang['setting_compress_js'] = 'Menggunakan perpustakaan JavaScript kompresi';
$_lang['setting_compress_js_desc'] = 'Ketika ini diaktifkan, MODX akan menggunakan versi kompresi java script pilihan di antarmuka manajer. Hal ini sangat mengurangi beban dan waktu pelaksanaan dalam pengelola. Menonaktifkan hanya jika Anda memodifikasi elemen inti.';

$_lang['setting_compress_js_groups'] = 'Menggunakan pengelompokan ketika mengompresi JavaScript';
$_lang['setting_compress_js_groups_desc'] = 'Kelompok inti MODX manajer JavaScript menggunakan pengecilan di groupsConfig. Diatur ke "ya" jika menggunakan suhosin atau faktor lain yang membatasi.';

$_lang['setting_compress_js_max_files'] = 'Berkas permulaan kompresi maksimum JavaScript';
$_lang['setting_compress_js_max_files_desc'] = 'Jumlah maksimum file JavaScript MODX akan berusaha untuk kompres sekaligus ketika compress_js aktif. Diatur ke jumlah yang lebih sedikit jika Anda mengalami masalah dengan Google Minify di manager.';

$_lang['setting_concat_js'] = 'Gunakan Perpustakaan Javascript yang disatukan';
$_lang['setting_concat_js_desc'] = 'Ketika ini diaktifkan, MODX akan menggunakan versi penyatuan pada perpustakaan java script di antarmuka manajer. Hal ini sangat mengurangi beban dan waktu pelaksanaan dalam pengelola. Menonaktifkan hanya jika Anda memodifikasi elemen inti.';

$_lang['setting_confirm_navigation'] = 'Mengkonfirmasi navigasi dengan perubahan yang belum disimpan';
$_lang['setting_confirm_navigation_desc'] = 'Ketika ini diaktifkan, pengguna akan diminta untuk mengkonfirmasi niat mereka jika ada perubahan yang belum disimpan.';

$_lang['setting_container_suffix'] = 'Akhiran kontainer';
$_lang['setting_container_suffix_desc'] = 'Akhiran untuk menambahkan ke sumber daya ditetapkan sebagai wadah dengan menggunakan FURLs.';

$_lang['setting_context_tree_sort'] = 'Mengaktifkan penyortiran konteks di pohon sumber daya';
$_lang['setting_context_tree_sort_desc'] = 'Jika diatur ke Ya, konteks akan diurutkan berdasarkan alfanumerik dalam pohon sumber daya kiri.';
$_lang['setting_context_tree_sortby'] = 'Urutkan bidang pada konteks di pohon sumber';
$_lang['setting_context_tree_sortby_desc'] = 'Bidang untuk menyortir konteks oleh di pohon sumber daya, jika pengurutan diaktifkan.';
$_lang['setting_context_tree_sortdir'] = 'Arah jenis konteks di pohon sumber daya';
$_lang['setting_context_tree_sortdir_desc'] = 'Arah untuk mengurutkan konteks di pohon sumber daya, jika penyortiran diaktifkan.';

$_lang['setting_cultureKey'] = 'Bahasa';
$_lang['setting_cultureKey_desc'] = 'Pilih bahasa untuk semua konteks non-manajer, termasuk web.';

$_lang['setting_date_timezone'] = 'Zona waktu standar';
$_lang['setting_date_timezone_desc'] = 'Kontrol pengaturan zona waktu yang standar untuk fungsi tanggal PHP, jika tidak kosong. Jika kosong dan tanggal PHP. Zona waktu pada pengaturan tidak diatur di lingkungan Anda, UTC akan dianggap.';

$_lang['setting_debug'] = 'Debug';
$_lang['setting_debug_desc'] = 'Kendali mengubah debugging on/off di MODX dan/atau menetapkan tingkat error_melaporkan PHP. \'\' = gunakan error saat ini_reporting, \'0\' = salah (error_melaporkan = 0), \'1\' = benar (error_melaporkan =-1), atau nilai error_melaporkan yang berlaku (sebagai bilangan bulat).';

$_lang['setting_default_content_type'] = 'Jenis konten standar';
$_lang['setting_default_content_type_desc'] = 'Pilih jenis konten standar yang Anda ingin gunakan untuk sumber daya baru. Anda masih dapat memilih jenis konten yang berbeda dengan sumber daya editor; pengaturan ini hanya pra-pemilihan salah satu jenis konten Anda untuk Anda.';

$_lang['setting_default_duplicate_publish_option'] = 'Opsi duplikasi sumber penerbitan standar';
$_lang['setting_default_duplicate_publish_option_desc'] = 'Standar dipilih opsi saat menduplikasi sumber daya. BIsa jadi salah satu "menghentikan penayangan" untuk menghentikan penayangan semua duplikat, "menerbitkan" menayangkan semua duplikasi, atau "melestarikan" untuk memelihara kondisi yang ditayangkan berdasarkan sumber yang diduplikasi.';

$_lang['setting_default_media_source'] = 'Sumber media standar';
$_lang['setting_default_media_source_desc'] = 'Memuat sumber media standar.';

$_lang['setting_default_template'] = 'Pola Bawaan';
$_lang['setting_default_template_desc'] = 'Pilih Template standar yang ingin Anda gunakan untuk Sumber Daya baru. Anda masih dapat memilih template yang berbeda dalam editor Sumber Daya, pengaturan ini hanya pemilihan awal pada salah satu dari Template Anda untuk Anda.';

$_lang['setting_default_per_page'] = 'Default Per halaman';
$_lang['setting_default_per_page_desc'] = 'Menjadi nomor standar hasil untuk menunjukkan di grid seluruh manajer.';

$_lang['setting_editor_css_path'] = 'Path ke CSS file';
$_lang['setting_editor_css_path_desc'] = 'Masukkan path ke file CSS Anda yang ingin Anda gunakan dalam sebuah editor rich text editor. Cara terbaik untuk memasuki jalan adalah untuk memasuki jalan dari akar dari server Anda, misalnya: /assets/site/style.css. Jika Anda tidak ingin memuat sebuah style sheet ke editor editor richtext, biarkan bidang ini kosong.';

$_lang['setting_editor_css_selectors'] = 'CSS penyeleksi untuk Editor';
$_lang['setting_editor_css_selectors_desc'] = 'Daftar comma separated CSS penyeleksi untuk editor richtext editor.';

$_lang['setting_emailsender'] = 'Pendaftaran Email dari alamat';
$_lang['setting_emailsender_desc'] = 'Di sini Anda dapat menentukan email alamat yang digunakan ketika mengirim pengguna username dan password.';
$_lang['setting_emailsender_err'] = 'Mohon sebutkan alamat email administrasi.';

$_lang['setting_emailsubject'] = 'Subjek Email pendaftaran';
$_lang['setting_emailsubject_desc'] = 'Baris subjek email untuk email standar pendaftaran ketika pengguna sudah terdaftar.';
$_lang['setting_emailsubject_err'] = 'Mohon sebutkan baris subjek untuk pendaftaran email.';

$_lang['setting_enable_dragdrop'] = 'Mengaktifkan Drag/Drop di pohon-pohon sumber elemen';
$_lang['setting_enable_dragdrop_desc'] = 'Jika off, akan mencegah penyeretan dan penjatuhan di Sumber Daya dan Elemen pohon.';

$_lang['setting_error_page'] = 'Halaman kesalahan';
$_lang['setting_error_page_desc'] = 'Masukkan ID dokumen yang Anda ingin kirimkan ke pengguna jika mereka meminta dokumen yang tidak benar-benar ada (404 Halaman tidak ditemukan). <strong>Catatan: Pastikan ID yang Anda masukan dimiliki oleh dokumen yang masih ada, dan bahwa ia telah diterbitkan!</strong>';
$_lang['setting_error_page_err'] = 'Silakan tentukan ID dokumen yang merupakan halaman kesalahan.';

$_lang['setting_ext_debug'] = 'ExtJS debug';
$_lang['setting_ext_debug_desc'] = 'Apakah atau tidak untuk memuat ext-semua-debug.js untuk membantu debug kode ExtJS Anda.';

$_lang['setting_extension_packages'] = 'Paket ekstensi';
$_lang['setting_extension_packages_desc'] = 'JSON array paket untuk memuat pada MODX Instansiasi. Dalam format [{"packagename": {"jalan": "path/ke/paket"}}, {"anotherpackagename": {"jalan": "path/ke/otherpackage"}}]';

$_lang['setting_enable_gravatar'] = 'Mengaktifkan Gravatar';
$_lang['setting_enable_gravatar_desc'] = 'Jika diaktifkan, Gravatar akan digunakan sebagai gambar profil (jika pengguna tidak memiliki mengunggah foto profil).';

$_lang['setting_failed_login_attempts'] = 'Upaya login gagal';
$_lang['setting_failed_login_attempts_desc'] = 'Jumlah usaha gagal login pengguna diperbolehkan sebelum \'diblokir\'.';

$_lang['setting_fe_editor_lang'] = 'Front-end Editor bahasa';
$_lang['setting_fe_editor_lang_desc'] = 'Pilih bahasa untuk editor untuk digunakan ketika digunakan sebagai front-end editor.';

$_lang['setting_feed_modx_news'] = 'MODX News Feed URL';
$_lang['setting_feed_modx_news_desc'] = 'Menetapkan URL untuk RSS feed untuk panel MODX berita di manager.';

$_lang['setting_feed_modx_news_enabled'] = 'MODX Berita Feed diaktifkan';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Jika \'Tidak\', MODX akan menyembunyikan Berita feed di bagian Selamat datang dari manajer.';

$_lang['setting_feed_modx_security'] = 'MODX keamanan pemberitahuan URL Feed';
$_lang['setting_feed_modx_security_desc'] = 'Menetapkan URL untuk RSS feed untuk panel MODX keamanan pemberitahuan di manager.';

$_lang['setting_feed_modx_security_enabled'] = 'MODX keamanan Feed diaktifkan';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Jika \'Tidak\', MODX akan menyembunyikan Berita keamanan di bagian Selamat datang dari manajer.';

$_lang['setting_filemanager_path'] = 'Path file Manager (sudah ditinggalkan)';
$_lang['setting_filemanager_path_desc'] = 'Usang - menggunakan sumber-sumber Media sebagai gantinya. IIS sering tidak mengisi document_root pengaturan dengan benar, yang digunakan oleh file manager untuk menentukan apa yang Anda bisa melihat pada. Jika Anda mengalami masalah menggunakan file manager, pastikan ini poin jalan ke akar instalasi MODX.';

$_lang['setting_filemanager_path_relative'] = 'Apakah manajer File Path relatif? (Sudah ditinggalkan)';
$_lang['setting_filemanager_path_relative_desc'] = 'Usang - menggunakan sumber-sumber Media sebagai gantinya. Jika pengaturan filemanager_path Anda relatif terhadap MODX base_path, kemudian silakan set pengaturan ini ke ya. Jika Anda filemanager_path di luar docroot, pengaturan No.';

$_lang['setting_filemanager_url'] = 'Manajer file Url (sudah ditinggalkan)';
$_lang['setting_filemanager_url_desc'] = 'Usang - menggunakan sumber-sumber Media sebagai gantinya. Opsional. Menetapkan ini jika Anda ingin mengatur sebuah URL yang eksplisit untuk mengakses file di file manager MODX dari (berguna jika Anda telah mengubah filemanager_path ke path di luar MODX webroot). Pastikan ini adalah nilai pengaturan filemanager_path URL diakses web. Jika Anda membiarkannya kosong, MODX akan mencoba untuk secara otomatis menghitung itu.';

$_lang['setting_filemanager_url_relative'] = 'Apakah manajer File URL relatif? (Sudah ditinggalkan)';
$_lang['setting_filemanager_url_relative_desc'] = 'Usang - menggunakan sumber-sumber Media sebagai gantinya. Jika pengaturan filemanager_url Anda relatif terhadap MODX base_url, kemudian silakan set pengaturan ini ke ya. Jika Anda filemanager_url di luar webroot utama, pengaturan No.';

$_lang['setting_forgot_login_email'] = 'Lupa Login Email';
$_lang['setting_forgot_login_email_desc'] = 'Template untuk email yang dikirim saat pengguna lupa MODX username dan/atau password.';

$_lang['setting_form_customization_use_all_groups'] = 'Gunakan semua grup sumber daya untuk bentuk kotumisasi';
$_lang['setting_form_customization_use_all_groups_desc'] = 'Jika diatur ke benar, FC akan menggunakan * all * set untuk * all * kelompok pengguna anggota ketika menerapkan pengaturan bentuk kustomisasi. Jika tidak, itu hanya akan menggunakan pengaturan milik pengguna primer kelompok. Catatan: pengaturan ini ke Ya mungkin menyebabkan bug dengan bertentangan FC set.';

$_lang['setting_forward_merge_excludes'] = 'Kirim teruskan tidak termasuk field pada penggabungan';
$_lang['setting_forward_merge_excludes_desc'] = 'Symlink menggabungkan nilai-nilai bidang non-kosong atas nilai-nilai dalam target sumber daya; menggunakan koma-dibatasi daftar ini tidak termasuk mencegah bidang yang telah ditetapkan menjadi ditimpa oleh Symlink.';

$_lang['setting_friendly_alias_lowercase_only'] = 'FURL alias huruf kecil';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Menentukan apakah akan memperbolehkan karakter hanya huruf kecil di sumber daya alias.';

$_lang['setting_friendly_alias_max_length'] = 'FURL panjang maksimum Alias';
$_lang['setting_friendly_alias_max_length_desc'] = 'Jika lebih besar dari nol, jumlah maksimum karakter untuk memungkinkan dalam alias sumber daya. Nol sama dengan tak terbatas.';

$_lang['setting_friendly_alias_realtime'] = 'FURL Alias Real-Time';
$_lang['setting_friendly_alias_realtime_desc'] = 'Tentukan apakah alias dibuat saat resource disimpan atau ketika mengetik pagetitle secara otomatis (automatic_alias harus diaktifkan untuk menjalankan fungsi ini).';

$_lang['setting_friendly_alias_restrict_chars'] = 'FURL Alias karakter pembatasan metode';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'Metode yang digunakan untuk membatasi karakter yang digunakan dalam alias sumber daya. "pola" memungkinkan pola RegEx yang akan diberikan, "hukum" memungkinkan karakter URL hukum, "alpha" memungkinkan hanya huruf-huruf alfabet, dan "alfanumerik" memungkinkan hanya huruf dan angka.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'FURL Alias karakter pembatasan pola';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Pola RegEx yang berlaku untuk membatasi karakter yang digunakan dalam alias sumber daya.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'FURL Alias Strip elemen tag';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Menentukan jika elemen tag harus dilucuti dari alias sumber daya.';

$_lang['setting_friendly_alias_translit'] = 'FURL Alias transliterasi';
$_lang['setting_friendly_alias_translit_desc'] = 'Metode transliterasi untuk digunakan pada alias ditetapkan untuk sumber daya. Kosong atau "none" adalah default yang melompat transliterasi. Nilai lain yang mungkin adalah "iconv" (jika tersedia) atau sebuah tabel bernama transliterasi yang disediakan oleh kelas layanan transliterasi kustom.';

$_lang['setting_friendly_alias_translit_class'] = 'FURL Aliaskelas layanan transliterasi';
$_lang['setting_friendly_alias_translit_class_desc'] = 'Kelas Layanan opsional untuk menyediakan layanan yang bernama transliterasi untuk FURL Alias generasi/penyaringan.';

$_lang['setting_friendly_alias_translit_class_path'] = 'FURL Alias jalan kelas layanan transliterasi';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'Lokasi paket model dimana FURL Alias transliterasi Service Class akan dimuat.';

$_lang['setting_friendly_alias_trim_chars'] = 'FURL Alias Trim karakter';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Karakter untuk memangkas dari ujung alias sumber daya yang disediakan.';

$_lang['setting_friendly_alias_word_delimiter'] = 'FURL Alias kata pembatas';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'Pilihan kata pembatas untuk URL alias slugs.';

$_lang['setting_friendly_alias_word_delimiters'] = 'FURL Alias kata pembatas';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Karakter yang mewakili kata pembatas saat memproses dengan ramah URL alias slugs. Karakter ini akan dikonversi dan konsolidasi untuk disukai FURL alias kata pembatas.';

$_lang['setting_friendly_urls'] = 'Gunakan URL ramah';
$_lang['setting_friendly_urls_desc'] = 'Hal ini memungkinkan Anda untuk menggunakan search engine friendly URL dengan MODX. Harap dicatat, ini hanya bekerja untuk instalasi MODX yang berjalan pada Apache, dan Anda akan perlu untuk menulis file .htaccess untuk bekerja. Lihat file .htaccess yang disertakan dalam distribusi untuk info lebih lanjut.';
$_lang['setting_friendly_urls_err'] = 'Silakan sebutkan apakah Anda ingin menggunakan URL yang ramah.';

$_lang['setting_friendly_urls_strict'] = 'Gunakan URL Ramah Ketat';
$_lang['setting_friendly_urls_strict_desc'] = 'Ketika URL ramah diaktifkan, pilihan ini memaksa bukan kanun permintaan yang sesuai dengan sumber daya untuk 301 redirect untuk URI kanonik untuk sumber daya. PERINGATAN: Tidak memberi jika Anda menggunakan aturan penulisan ulang kustom yang tidak cocok setidaknya awal kanonik URI. Sebagai contoh, URI kanonik foo / dengan penulisan ulang kustom untuk foo/bar.html akan bekerja, tetapi upaya untuk menulis ulang bar/foo.html sebagai foo / akan memaksa redirect ke foo / dengan opsi ini diaktifkan.';

$_lang['setting_global_duplicate_uri_check'] = 'Periksa URI duplikat di seluruh semua konteks';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Pilih \'Ya\' untuk membuat duplikat cek URI yang mencakup semua konteks dalam pencarian. Jika tidak, hanya konteks sumber daya yang disimpan dalam dicentang.';

$_lang['setting_hidemenu_default'] = 'Bersembunyi dari Default menu';
$_lang['setting_hidemenu_default_desc'] = 'Pilih \'Ya\' untuk membuat semua sumber daya baru yang tersembunyi dari menu secara default.';

$_lang['setting_inline_help'] = 'Tampilkan teks Inline bantuan untuk bidang';
$_lang['setting_inline_help_desc'] = 'Jika \'Ya\', maka bidang akan menampilkan teks bantuan langsung di bawah bidang. Jika \'Tidak\', semua bidang akan memiliki bantuan berbasis tooltip.';

$_lang['setting_link_tag_scheme'] = 'Skema penghasilan URL';
$_lang['setting_link_tag_scheme_desc'] = 'Skema penghasilan URL untuk tag [[~ id]]. Pilihan yang tersedia <a href="http://api.modx.com/revolution/2.2/db_core_model_modx_modx.class.html#\\modX::makeUrl()"> di sini</a>.';

$_lang['setting_locale'] = 'Lokal';
$_lang['setting_locale_desc'] = 'Mengatur lokal untuk sistem. Biarkan kosong untuk menggunakan default. Lihat <a href="http://php.net/setlocale" target="_blank"> dokumentasi PHP</a> untuk informasi lebih lanjut.';

$_lang['setting_lock_ttl'] = 'Mengunci waktu-to-Live';
$_lang['setting_lock_ttl_desc'] = 'Jumlah detik kunci pada sumber daya yang akan tetap jika pengguna tidak aktif.';

$_lang['setting_log_level'] = 'Tingkat pendataan';
$_lang['setting_log_level_desc'] = 'Tingkat pendataan default; semakin rendah tingkat, lebih sedikit pesan yang masuk. Pilihan yang tersedia: 0 (FATAL), 1 (ERROR), 2 (PERINGATKAN), 3 (INFO) dan 4 (DEBUG).';

$_lang['setting_log_target'] = 'Target penebangan';
$_lang['setting_log_target_desc'] = 'Target login bawaan yang mana pesan log ditulis. Pilihan yang tersedia: \'FILE\', \'HTML\', atau \'ECHO\'. Default adalah \'FILE\' jika tidak ditentukan.';

$_lang['setting_mail_charset'] = 'Rangkaian karakter surat';
$_lang['setting_mail_charset_desc'] = 'Charset default untuk email, misalnya, \'iso-8859-1\' atau \'utf-8\'';

$_lang['setting_mail_encoding'] = 'Pengkodean pesan';
$_lang['setting_mail_encoding_desc'] = 'Menetapkan Encoding pesan. Pilihan ini adalah "8 bit", "7 bit", "biner", "base64", dan "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Menggunakan SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Jika benar, MODX akan mencoba untuk menggunakan SMTP difungsi e-mail.';

$_lang['setting_mail_smtp_auth'] = 'Otentikasi SMTP';
$_lang['setting_mail_smtp_auth_desc'] = 'Otentikasi set SMTP. Memanfaatkan pengaturan mail_smtp_user dan mail_smtp_pass.';

$_lang['setting_mail_smtp_helo'] = 'Pesan SMTP Helo';
$_lang['setting_mail_smtp_helo_desc'] = 'Menetapkan SMTP HELO pesan (defaultnya nama host).';

$_lang['setting_mail_smtp_hosts'] = 'SMTP host';
$_lang['setting_mail_smtp_hosts_desc'] = 'Menetapkan SMTP host.  Semua host harus dipisahkan dengan titik koma.  Anda juga dapat menentukan sebuah port yang berbeda untuk masing-masing host dengan menggunakan format ini: [hostname:port] (misalnya, "smtp1.example.com:25;smtp2.example.com"). Host akan diadili dalam urutan.';

$_lang['setting_mail_smtp_keepalive'] = 'SMTP tetap-hidup';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Mencegah koneksi SMTP ditutup setelah setiap pengiriman e-mail. Tidak dianjurkan.';

$_lang['setting_mail_smtp_pass'] = 'SMTP Password';
$_lang['setting_mail_smtp_pass_desc'] = 'Password untuk melakukan otentikasi ke SMTP melawan.';

$_lang['setting_mail_smtp_port'] = 'SMTP Port';
$_lang['setting_mail_smtp_port_desc'] = 'Menetapkan default SMTP server port.';

$_lang['setting_mail_smtp_prefix'] = 'Awalan koneksi SMTP';
$_lang['setting_mail_smtp_prefix_desc'] = 'Set sambungan awalan. Pilihan "", "ssl" atau "tls"';

$_lang['setting_mail_smtp_single_to'] = 'SMTP tunggal untuk';
$_lang['setting_mail_smtp_single_to_desc'] = 'Menyediakan kemampuan untuk memiliki untuk bidang proses individu email, bukan pengiriman ke seluruh alamat.';

$_lang['setting_mail_smtp_timeout'] = 'SMTP Timeout';
$_lang['setting_mail_smtp_timeout_desc'] = 'Menetapkan SMTP server timeout dalam detik. Fungsi ini tidak akan bekerja dalam server win32.';

$_lang['setting_mail_smtp_user'] = 'SMTP pengguna';
$_lang['setting_mail_smtp_user_desc'] = 'Pengguna untuk melakukan otentikasi ke SMTP melawan.';

$_lang['setting_main_nav_parent'] = 'Menu utama';
$_lang['setting_main_nav_parent_desc'] = 'The container used to pull all records for the main menu.';

$_lang['setting_manager_direction'] = 'Arah manajer teks';
$_lang['setting_manager_direction_desc'] = 'Memilih arah yang teks yang akan diterjemahkan dalam manajer, kiri ke kanan atau kanan ke kiri.';

$_lang['setting_manager_date_format'] = 'Format tanggal Manajer';
$_lang['setting_manager_date_format_desc'] = 'Format string, dalam format date() pada PHP PHP, untuk tanggal yang diwakili dalam manajer.';

$_lang['setting_manager_favicon_url'] = 'Manajer Favicon URL';
$_lang['setting_manager_favicon_url_desc'] = 'Jika diatur, akan memuat URL ini sebagai favicon untuk manajer MODX. Harus sebuah URL relatif untuk manajer / direktori, atau URL absolut.';

$_lang['setting_manager_js_cache_file_locking'] = 'Mengaktifkan mengunci file untuk manajer JS/CSS Cache';
$_lang['setting_manager_js_cache_file_locking_desc'] = 'Cache file penguncian. Diatur ke tidak jika filesystem NFS.';
$_lang['setting_manager_js_cache_max_age'] = 'Manajer JS/CSS kompresi Cache umur';
$_lang['setting_manager_js_cache_max_age_desc'] = 'Maksimal usia cache browser manajer CSS/JS kompresi dalam hitungan detik. Setelah masa ini, peramban akan mengirim GET bersyarat yang lain. Gunakan lebih lama untuk lalu lintas rendah.';
$_lang['setting_manager_js_document_root'] = 'Manajer JS/CSS kompresi Document Root';
$_lang['setting_manager_js_document_root_desc'] = 'Jika server Anda tidak menangani variabel server DOCUMENT_ROOT, mengatur secara eksplisit di sini agar manajer CSS/JS kompresi. Jangan mengubah ini kecuali Anda tahu apa yang Anda lakukan.';
$_lang['setting_manager_js_zlib_output_compression'] = 'Mengaktifkan zlib kompresi Output untuk manajer JS/CSS';
$_lang['setting_manager_js_zlib_output_compression_desc'] = 'Apakah atau tidak untuk mengaktifkan zlib output kompresi untuk CSS/JS kompresi dalam manajer. Tidak menghidupkan ini kecuali Anda yakin zlib.output_compression variabel konfigurasi PHP dapat diatur ke 1. MODX merekomendasikan meninggalkannya.';

$_lang['setting_manager_lang_attribute'] = 'Manajer HTML dan XML bahasa atribut';
$_lang['setting_manager_lang_attribute_desc'] = 'Masukkan kode bahasa yang paling sesuai dengan bahasa Manajer pilihan Anda, ini akan memastikan bahwa browser dapat menyajikan konten dalam format terbaik untuk Anda.';

$_lang['setting_manager_language'] = 'Bahasa pengaturan';
$_lang['setting_manager_language_desc'] = 'Pilih bahasa untuk MODX Content Manager.';

$_lang['setting_manager_login_url_alternate'] = 'URL alternatif manajer Login';
$_lang['setting_manager_login_url_alternate_desc'] = 'URL alternatif untuk mengirim pengguna tidak terauthentikasi ke ketika mereka perlu login ke pengelola. Login form tidak harus login pengguna untuk konteks "mgr" untuk bekerja.';

$_lang['setting_manager_login_start'] = 'Manajer Login Startup';
$_lang['setting_manager_login_start_desc'] = 'Masukkan ID dokumen Anda ingin mengirim pengguna setelah ia telah login ke pengelola. <strong>Catatan: Pastikan Anda telah memasukkan ID milik dokumen yang ada, dan bahwa ia telah diterbitkan dan dapat diakses oleh pengguna ini!</strong>';

$_lang['setting_manager_theme'] = 'Pengaturan Tema';
$_lang['setting_manager_theme_desc'] = 'Pilih tema untuk manajer konten.';

$_lang['setting_manager_time_format'] = 'Format waktu Manajer';
$_lang['setting_manager_time_format_desc'] = 'Format string, dalam format date() pada PHP PHP, untuk pengaturan waktu yang diwakili dalam manajer.';

$_lang['setting_manager_use_tabs'] = 'Menggunakan tab di Manajer Layout';
$_lang['setting_manager_use_tabs_desc'] = 'Jika benar, manajer akan menggunakan tab untuk render panel konten. Jika tidak, itu akan menggunakan portal.';

$_lang['setting_manager_week_start'] = 'Mulai minggu';
$_lang['setting_manager_week_start_desc'] = 'Menentukan hari mulai minggu. Menggunakan 0 (atau Tinggalkan kosong) untuk hari Minggu, 1 untuk hari Senin dan seterusnya...';

$_lang['setting_mgr_tree_icon_context'] = 'Ikon untuk Context';
$_lang['setting_mgr_tree_icon_context_desc'] = 'Define a CSS class here to be used to display the context icon in the tree. You can use this setting on each context to customize the icon per context.';

$_lang['setting_mgr_source_icon'] = 'Ikon untuk Media Source';
$_lang['setting_mgr_source_icon_desc'] = 'Indicate a CSS class to be used to display the Media Sources icons in the files tree. Defaults to "icon-folder-open-o"';

$_lang['setting_modRequest.class'] = 'Permintaan Handler kelas';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_tree_hide_files'] = 'Media Browser Tree Hide Files';
$_lang['setting_modx_browser_tree_hide_files_desc'] = 'If true the files inside folders are not displayed in the Media Browser source tree. Defaults to false.';

$_lang['setting_modx_browser_tree_hide_tooltips'] = 'Media Browser Tree Hide Tooltips';
$_lang['setting_modx_browser_tree_hide_tooltips_desc'] = 'If true, no image preview tooltips are shown when hovering over a file in the Media Browser tree. Defaults to true.';

$_lang['setting_modx_browser_default_sort'] = 'File Browser Default Sort';
$_lang['setting_modx_browser_default_sort_desc'] = 'Default sort metode ketika menggunakan popup File Browser di manager. Nilai yang tersedia: nama, ukuran, lastmod (terakhir diubah).';

$_lang['setting_modx_browser_default_viewmode'] = 'File Browser Default Lihat Mode';
$_lang['setting_modx_browser_default_viewmode_desc'] = 'Lihat modus default ketika menggunakan popup File Browser di manager. Nilai yang tersedia: grid, daftar.';

$_lang['setting_modx_charset'] = 'Pengkodean karakter';
$_lang['setting_modx_charset_desc'] = 'Silakan pilih pengkodean karakter yang ingin Anda gunakan. Harap dicatat bahwa MODX telah diuji dengan sejumlah penyandiaksaraan ini, tapi tidak semua dari mereka. Untuk kebanyakan bahasa, pengaturan default UTF-8 lebih baik.';

$_lang['setting_new_file_permissions'] = 'Ijin file baru';
$_lang['setting_new_file_permissions_desc'] = 'Ketika meng-upload file baru di File Manager, File Manager akan berusaha untuk mengubah file permission untuk orang-orang yang masuk dalam pengaturan ini. Ini mungkin tidak bekerja pada beberapa setup, seperti IIS, dalam hal Anda perlu secara manual mengubah hak akses.';

$_lang['setting_new_folder_permissions'] = 'Baru Folder Permissions';
$_lang['setting_new_folder_permissions_desc'] = 'Ketika mengupload file baru, File Manager akan mencoba mengubah setting hak akses pada folder yang dituju. Hal ini mungkin tidak berlaku pada beberapa server, seperti IIS, karenanya Anda perlu secara manual mengubah hak akses pada folder tersebut.';

$_lang['setting_parser_recurse_uncacheable'] = 'Delay Uncacheable Parsing';
$_lang['setting_parser_recurse_uncacheable_desc'] = 'If disabled, uncacheable elements may have their output cached inside cacheable element content. Disable this ONLY if you are having problems with complex nested parsing which stopped working as expected.';

$_lang['setting_password_generated_length'] = 'Jumlah Karakter Auto-Generate Password';
$_lang['setting_password_generated_length_desc'] = 'Panjang karakter untuk auto-generated password untuk user.';

$_lang['setting_password_min_length'] = 'Minimum Karakter untuk Password';
$_lang['setting_password_min_length_desc'] = 'The minimum length for a password for a User.';

$_lang['setting_preserve_menuindex'] = 'Preserve Menu Index When Duplicating Resources';
$_lang['setting_preserve_menuindex_desc'] = 'When duplicating Resources, the menu index order will also be preserved.';

$_lang['setting_principal_targets'] = 'ACL Targets to Load';
$_lang['setting_principal_targets_desc'] = 'Customize the ACL targets to load for MODX Users.';

$_lang['setting_proxy_auth_type'] = 'Proxy Authentication Type';
$_lang['setting_proxy_auth_type_desc'] = 'Supports either BASIC or NTLM.';

$_lang['setting_proxy_host'] = 'Proxy Host';
$_lang['setting_proxy_host_desc'] = 'If your server is using a proxy, set the hostname here to enable MODX features that might need to use the proxy, such as Package Management.';

$_lang['setting_proxy_password'] = 'Proxy Password';
$_lang['setting_proxy_password_desc'] = 'The password required to authenticate to your proxy server.';

$_lang['setting_proxy_port'] = 'Proxy Port';
$_lang['setting_proxy_port_desc'] = 'The port for your proxy server.';

$_lang['setting_proxy_username'] = 'Proxy Username';
$_lang['setting_proxy_username_desc'] = 'The username to authenticate against with your proxy server.';

$_lang['setting_photo_profile_source'] = 'Media Source untuk Foto User';
$_lang['setting_photo_profile_source_desc'] = 'The Media Source used to store users profiles photos. Defaults to default Media Source.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb Allow src Above Document Root';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Indicates if the src path is allowed outside the document root. This is useful for multi-context deployments with multiple virtual hosts.';

$_lang['setting_phpthumb_cache_maxage'] = 'phpThumb Max Cache Age';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Delete cached thumbnails that have not been accessed in more than X days.';

$_lang['setting_phpthumb_cache_maxsize'] = 'phpThumb Max Cache Size';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Delete least-recently-accessed thumbnails when cache grows bigger than X megabytes in size.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'phpThumb Max Cache Files';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Delete least-recently-accessed thumbnails when cache has more than X files.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'phpThumb Cache Source Files';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Whether or not to cache source files as they are loaded. Recommended to off.';

$_lang['setting_phpthumb_document_root'] = 'PHPThumb Document Root';
$_lang['setting_phpthumb_document_root_desc'] = 'Set this if you are experiencing issues with the server variable DOCUMENT_ROOT, or getting errors with OutputThumbnail or !is_resource. Set it to the absolute document root path you would like to use. If this is empty, MODX will use the DOCUMENT_ROOT server variable.';

$_lang['setting_phpthumb_error_bgcolor'] = 'phpThumb Error Background Color';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'A hex value, without the #, indicating a background color for phpThumb error output.';

$_lang['setting_phpthumb_error_fontsize'] = 'phpThumb Error Font Size';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'An em value indicating a font size to use for text appearing in phpThumb error output.';

$_lang['setting_phpthumb_error_textcolor'] = 'phpThumb Error Font Color';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'A hex value, without the #, indicating a font color for text appearing in phpThumb error output.';

$_lang['setting_phpthumb_far'] = 'phpThumb Force Aspect Ratio';
$_lang['setting_phpthumb_far_desc'] = 'The default far setting for phpThumb when used in MODX. Defaults to C to force aspect ratio toward the center.';

$_lang['setting_phpthumb_imagemagick_path'] = 'phpThumb ImageMagick Path';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Optional. Set an alternative ImageMagick path here for generating thumbnails with phpThumb, if it is not in the PHP default.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb Hotlinking Disabled';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Remote servers are allowed in the src parameter unless you disable hotlinking in phpThumb.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb Hotlinking Erase Image';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Indicates if an image generated from a remote server should be erased when not allowed.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb Hotlinking Not Allowed Message';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'A message that is rendered instead of the thumbnail when a hotlinking attempt is rejected.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb Hotlinking Valid Domains';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'A comma-delimited list of hostnames that are valid in src URLs.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb Offsite Linking Disabled';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Disables the ability for others to use phpThumb to render images on their own sites.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb Offsite Linking Erase Image';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Indicates if an image linked from a remote server should be erased when not allowed.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb Offsite Linking Require Referrer';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'If enabled, any offsite linking attempts will be rejected without a valid referrer header.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb Offsite Linking Not Allowed Message';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'A message that is rendered instead of the thumbnail when an offsite linking attempt is rejected.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb Offsite Linking Valid Domains';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'A comma-delimited list of hostnames that are valid referrers for offsite linking.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb Offsite Linking Watermark Source';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Optional. A valid file system path to a file to use as a watermark source when your images are rendered offsite by phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb Zoom-Crop';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'The default zc setting for phpThumb when used in MODX. Defaults to 0 to prevent zoom cropping.';

$_lang['setting_publish_default'] = 'Published default';
$_lang['setting_publish_default_desc'] = 'Select \'Yes\' to make all new resources published by default.';
$_lang['setting_publish_default_err'] = 'Please state whether or not you want documents to be published by default.';

$_lang['setting_rb_base_dir'] = 'Resource path';
$_lang['setting_rb_base_dir_desc'] = 'Enter the physical path to the resource directory. This setting is usually automatically generated. If you\'re using IIS, however, MODX may not be able to work the path out on its own, causing the Resource Browser to show an error. In that case, you can enter the path to the images directory here (the path as you\'d see it in Windows Explorer). <strong>NOTE:</strong> The resource directory must contain the subfolders images, files, flash and media in order for the resource browser to function correctly.';
$_lang['setting_rb_base_dir_err'] = 'Please state the resource browser base directory.';
$_lang['setting_rb_base_dir_err_invalid'] = 'This resource directory either does not exist or cannot be accessed. Please state a valid directory or adjust the permissions of this directory.';

$_lang['setting_rb_base_url'] = 'Resource URL';
$_lang['setting_rb_base_url_desc'] = 'Enter the virtual path to resource directory. This setting is usually automatically generated. If you\'re using IIS, however, MODX may not be able to work the URL out on its own, causing the Resource Browser to show an error. In that case, you can enter the URL to the images directory here (the URL as you\'d enter it on Internet Explorer).';
$_lang['setting_rb_base_url_err'] = 'Please state the resource browser base URL.';

$_lang['setting_request_controller'] = 'Request Controller Filename';
$_lang['setting_request_controller_desc'] = 'The filename of the main request controller from which MODX is loaded. Most users can leave this as index.php.';

$_lang['setting_request_method_strict'] = 'Strict Request Method';
$_lang['setting_request_method_strict_desc'] = 'If enabled, requests via the Request ID Parameter will be ignored with FURLs enabled, and those via Request Alias Parameter will be ignored without FURLs enabled.';

$_lang['setting_request_param_alias'] = 'Request Alias Parameter';
$_lang['setting_request_param_alias_desc'] = 'The name of the GET parameter to identify Resource aliases when redirecting with FURLs.';

$_lang['setting_request_param_id'] = 'Request ID Parameter';
$_lang['setting_request_param_id_desc'] = 'The name of the GET parameter to identify Resource IDs when not using FURLs.';

$_lang['setting_resolve_hostnames'] = 'Resolve hostnames';
$_lang['setting_resolve_hostnames_desc'] = 'Do you want MODX to try to resolve your visitors\' hostnames when they visit your site? Resolving hostnames may create some extra server load, although your visitors won\'t notice this in any way.';

$_lang['setting_resource_tree_node_name'] = 'Resource Tree Node Field';
$_lang['setting_resource_tree_node_name_desc'] = 'Specify the Resource field to use when rendering the nodes in the Resource Tree. Defaults to pagetitle, although any Resource field can be used, such as menutitle, alias, longtitle, etc.';

$_lang['setting_resource_tree_node_name_fallback'] = 'Resource Tree Node Fallback Field';
$_lang['setting_resource_tree_node_name_fallback_desc'] = 'Specify the Resource field to use as fallback when rendering the nodes in the Resource Tree. This will be used if the resource has an empty value for the configured Resource Tree Node Field.';

$_lang['setting_resource_tree_node_tooltip'] = 'Resource Tree Tooltip Field';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Specify the Resource field to use when rendering the nodes in the Resource Tree. Any Resource field can be used, such as menutitle, alias, longtitle, etc. If blank, will be the longtitle with a description underneath.';

$_lang['setting_richtext_default'] = 'Richtext Default';
$_lang['setting_richtext_default_desc'] = 'Select \'Yes\' to make all new Resources use the Richtext Editor by default.';

$_lang['setting_search_default'] = 'Searchable Default';
$_lang['setting_search_default_desc'] = 'Select \'Yes\' to make all new resources searchable by default.';
$_lang['setting_search_default_err'] = 'Please specify whether or not you want documents to be searchable by default.';

$_lang['setting_server_offset_time'] = 'Server offset time';
$_lang['setting_server_offset_time_desc'] = 'Select the number of hours time difference between where you are and where the server is.';

$_lang['setting_server_protocol'] = 'Server type';
$_lang['setting_server_protocol_desc'] = 'If your site is on a https connection, please specify so here.';
$_lang['setting_server_protocol_err'] = 'Please specify whether or not your site is a secure site.';
$_lang['setting_server_protocol_http'] = 'http';
$_lang['setting_server_protocol_https'] = 'https';

$_lang['setting_session_cookie_domain'] = 'Session Cookie Domain';
$_lang['setting_session_cookie_domain_desc'] = 'Use this setting to customize the session cookie domain. Leave blank to use the current domain.';

$_lang['setting_session_cookie_lifetime'] = 'Session Cookie Lifetime';
$_lang['setting_session_cookie_lifetime_desc'] = 'Use this setting to customize the session cookie lifetime in seconds.  This is used to set the lifetime of a client session cookie when they choose the \'remember me\' option on login.';

$_lang['setting_session_cookie_path'] = 'Session Cookie Path';
$_lang['setting_session_cookie_path_desc'] = 'Use this setting to customize the cookie path for identifying site specific session cookies. Leave blank to use MODX_BASE_URL.';

$_lang['setting_session_cookie_secure'] = 'Session Cookie Secure';
$_lang['setting_session_cookie_secure_desc'] = 'Enable this setting to use secure session cookies. This requires your site to be accessible over https, otherwise your site and/or manager will become inaccessible.';

$_lang['setting_session_cookie_httponly'] = 'Session Cookie HttpOnly';
$_lang['setting_session_cookie_httponly_desc'] = 'Use this setting to set the HttpOnly flag on session cookies.';

$_lang['setting_session_gc_maxlifetime'] = 'Session Garbage Collector Max Lifetime';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Allows customization of the session.gc_maxlifetime PHP ini setting when using \'modSessionHandler\'.';

$_lang['setting_session_handler_class'] = 'Session Handler Class Name';
$_lang['setting_session_handler_class_desc'] = 'For database managed sessions, use \'modSessionHandler\'.  Leave this blank to use standard PHP session management.';

$_lang['setting_session_name'] = 'Session Name';
$_lang['setting_session_name_desc'] = 'Use this setting to customize the session name used for the sessions in MODX. Leave blank to use the default PHP session name.';

$_lang['setting_settings_version'] = 'Settings Version';
$_lang['setting_settings_version_desc'] = 'The current installed version of MODX.';

$_lang['setting_settings_distro'] = 'Settings Distribution';
$_lang['setting_settings_distro_desc'] = 'The current installed distribution of MODX.';

$_lang['setting_set_header'] = 'Set HTTP Headers';
$_lang['setting_set_header_desc'] = 'When enabled, MODX will attempt to set the HTTP headers for Resources.';

$_lang['setting_send_poweredby_header'] = 'Send X-Powered-By Header';
$_lang['setting_send_poweredby_header_desc'] = 'When enabled, MODX will send the "X-Powered-By" header to identify this site as built on MODX. This helps tracking global MODX usage through third party trackers inspecting your site. Because this makes it easier to identify what your site is built with, it might pose a slightly increased security risk if a vulnerability is found in MODX.';

$_lang['setting_show_tv_categories_header'] = 'Show "Categories" Tabs Header with TVs';
$_lang['setting_show_tv_categories_header_desc'] = 'If "Yes", MODX will show the "Categories" header above the first category tab when editing TVs in a Resource.';

$_lang['setting_signupemail_message'] = 'Sign-up email';
$_lang['setting_signupemail_message_desc'] = 'Here you can set the message sent to your users when you create an account for them and let MODX send them an email containing their username and password. <br /><strong>Note:</strong> The following placeholders are replaced by the Content Manager when the message is sent: <br /><br />[[+sname]] - Name of your web site, <br />[[+saddr]] - Your web site email address, <br />[[+surl]] - Your site URL, <br />[[+uid]] - User\'s login name or id, <br />[[+pwd]] - User\'s password, <br />[[+ufn]] - User\'s full name. <br /><br /><strong>Leave the [[+uid]] and [[+pwd]] in the email, or else the username and password won\'t be sent in the mail and your users won\'t know their username or password!</strong>';
$_lang['setting_signupemail_message_default'] = 'Hello [[+uid]] \n\nHere are your login details for [[+sname]] Content Manager:\n\nUsername: [[+uid]]\nPassword: [[+pwd]]\n\nOnce you log into the Content Manager ([[+surl]]), you can change your password.\n\nRegards,\nSite Administrator';

$_lang['setting_site_name'] = 'Site name';
$_lang['setting_site_name_desc'] = 'Enter the name of your site here.';
$_lang['setting_site_name_err']  = 'Please enter a site name.';

$_lang['setting_site_start'] = 'Site start';
$_lang['setting_site_start_desc'] = 'Enter the ID of the Resource you want to use as homepage here. <strong>NOTE: make sure this ID you enter belongs to an existing Resource, and that it has been published!</strong>';
$_lang['setting_site_start_err'] = 'Please specify a Resource ID that is the site start.';

$_lang['setting_site_status'] = 'Site status';
$_lang['setting_site_status_desc'] = 'Select \'Yes\' to publish your site on the web. If you select \'No\', your visitors will see the \'Site unavailable message\', and won\'t be able to browse the site.';
$_lang['setting_site_status_err'] = 'Please select whether or not the site is online (Yes) or offline (No).';

$_lang['setting_site_unavailable_message'] = 'Site unavailable message';
$_lang['setting_site_unavailable_message_desc'] = 'Message to show when the site is offline or if an error occurs. <strong>Note: This message will only be displayed if the Site unavailable page option is not set.</strong>';

$_lang['setting_site_unavailable_page'] = 'Site unavailable page';
$_lang['setting_site_unavailable_page_desc'] = 'Enter the ID of the Resource you want to use as an offline page here. <strong>NOTE: make sure this ID you enter belongs to an existing Resource, and that it has been published!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Please specify the document ID for the site unavailable page.';

$_lang['setting_strip_image_paths'] = 'Rewrite browser paths?';
$_lang['setting_strip_image_paths_desc'] = 'If this is set to \'No\', MODX will write file browser resource src\'s (images, files, flash, etc.) as absolute URLs. Relative URLs are helpful should you wish to move your MODX install, e.g., from a staging site to a production site. If you have no idea what this means, it\'s best just to leave it set to \'Yes\'.';

$_lang['setting_symlink_merge_fields'] = 'Merge Resource Fields in Symlinks';
$_lang['setting_symlink_merge_fields_desc'] = 'If set to Yes, will automatically merge non-empty fields with target resource when forwarding using Symlinks.';

$_lang['setting_syncsite_default'] = 'Empty Cache default';
$_lang['setting_syncsite_default_desc'] = 'Select \'Yes\' to empty the cache after you save a resource by default.';
$_lang['setting_syncsite_default_err'] = 'Please state whether or not you want to empty the cache after saving a resource by default.';

$_lang['setting_topmenu_show_descriptions'] = 'Show Descriptions in Top Menu';
$_lang['setting_topmenu_show_descriptions_desc'] = 'If set to \'No\', MODX will hide the descriptions from top menu items in the manager.';

$_lang['setting_tree_default_sort'] = 'Resource Tree Default Sort Field';
$_lang['setting_tree_default_sort_desc'] = 'The default sort field for the Resource tree when loading the manager.';

$_lang['setting_tree_root_id'] = 'Tree Root ID';
$_lang['setting_tree_root_id_desc'] = 'Set this to a valid ID of a Resource to start the left Resource tree at below that node as the root. The user will only be able to see Resources that are children of the specified Resource.';

$_lang['setting_tvs_below_content'] = 'Move TVs Below Content';
$_lang['setting_tvs_below_content_desc'] = 'Set this to Yes to move Template Variables below the Content when editing Resources.';

$_lang['setting_ui_debug_mode'] = 'UI Debug Mode';
$_lang['setting_ui_debug_mode_desc'] = 'Set this to Yes to output debug messages when using the UI for the default manager theme. You must use a browser that supports console.log.';

$_lang['setting_udperms_allowroot'] = 'Allow root';
$_lang['setting_udperms_allowroot_desc'] = 'Do you want to allow your users to create new Resources in the root of the site?';

$_lang['setting_unauthorized_page'] = 'Unauthorized page';
$_lang['setting_unauthorized_page_desc'] = 'Enter the ID of the Resource you want to send users to if they have requested a secured or unauthorized Resource. <strong>NOTE: Make sure the ID you enter belongs to an existing Resource, and that it has been published and is publicly accessible!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Please specify a Resource ID for the unauthorized page.';

$_lang['setting_upload_files'] = 'Uploadable File Types';
$_lang['setting_upload_files_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/files/\' using the Resource Manager. Please enter the extensions for the filetypes, seperated by commas.';

$_lang['setting_upload_flash'] = 'Uploadable Flash Types';
$_lang['setting_upload_flash_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/flash/\' using the Resource Manager. Please enter the extensions for the flash types, separated by commas.';

$_lang['setting_upload_images'] = 'Uploadable Image Types';
$_lang['setting_upload_images_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/images/\' using the Resource Manager. Please enter the extensions for the image types, separated by commas.';

$_lang['setting_upload_maxsize'] = 'Maximum upload size';
$_lang['setting_upload_maxsize_desc'] = 'Enter the maximum file size that can be uploaded via the file manager. Upload file size must be entered in bytes. <strong>NOTE: Large files can take a very long time to upload!</strong>';

$_lang['setting_upload_media'] = 'Uploadable Media Types';
$_lang['setting_upload_media_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/media/\' using the Resource Manager. Please enter the extensions for the media types, separated by commas.';

$_lang['setting_use_alias_path'] = 'Use Friendly Alias Path';
$_lang['setting_use_alias_path_desc'] = 'Setting this option to \'yes\' will display the full path to the Resource if the Resource has an alias. For example, if a Resource with an alias called \'child\' is located inside a container Resource with an alias called \'parent\', then the full alias path to the Resource will be displayed as \'/parent/child.html\'.<br /><strong>NOTE: When setting this option to \'Yes\' (turning on alias paths), reference items (such as images, CSS, JavaScripts, etc.) use the absolute path, e.g., \'/assets/images\' as opposed to \'assets/images\'. By doing so you will prevent the browser (or web server) from appending the relative path to the alias path.</strong>';

$_lang['setting_use_browser'] = 'Enable Resource Browser';
$_lang['setting_use_browser_desc'] = 'Select yes to enable the resource browser. This will allow your users to browse and upload resources such as images, flash and media files on the server.';
$_lang['setting_use_browser_err'] = 'Please state whether or not you want to use the resource browser.';

$_lang['setting_use_editor'] = 'Enable Rich Text Editor';
$_lang['setting_use_editor_desc'] = 'Do you want to enable the rich text editor? If you\'re more comfortable writing HTML, then you can turn the editor off using this setting. Note that this setting applies to all documents and all users!';
$_lang['setting_use_editor_err'] = 'Please state whether or not you want to use an RTE editor.';

$_lang['setting_use_frozen_parent_uris'] = 'Gunakan Frozen URI dari Resource Induk';
$_lang['setting_use_frozen_parent_uris_desc'] = 'When enabled, the URI for children resources will be relative to the frozen URI of one of its parents, ignoring the aliases of resources high in the tree.';

$_lang['setting_use_multibyte'] = 'Use Multibyte Extension';
$_lang['setting_use_multibyte_desc'] = 'Set to true if you want to use the mbstring extension for multibyte characters in your MODX installation. Only set to true if you have the mbstring PHP extension installed.';

$_lang['setting_use_weblink_target'] = 'Use WebLink Target';
$_lang['setting_use_weblink_target_desc'] = 'Set to true if you want to have MODX link tags and makeUrl() generate links as the target URL for WebLinks. Otherwise, the internal MODX URL will be generated by link tags and the makeUrl() method.';

$_lang['setting_user_nav_parent'] = 'Menu user induk';
$_lang['setting_user_nav_parent_desc'] = 'The container used to pull all records for the user menu.';

$_lang['setting_webpwdreminder_message'] = 'Web Reminder Email';
$_lang['setting_webpwdreminder_message_desc'] = 'Enter a message to be sent to your web users whenever they request a new password via email. The Content Manager will send an email containing their new password and activation information. <br /><strong>Note:</strong> The following placeholders are replaced by the Content Manager when the message is sent: <br /><br />[[+sname]] - Name of your web site, <br />[[+saddr]] - Your web site email address, <br />[[+surl]] - Your site URL, <br />[[+uid]] - User\'s login name or id, <br />[[+pwd]] - User\'s password, <br />[[+ufn]] - User\'s full name. <br /><br /><strong>Leave the [[+uid]] and [[+pwd]] in the email, or else the username and password won\'t be sent in the mail and your users won\'t know their username or password!</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'Hello [[+uid]]\n\nTo activate your new password, click the following link:\n\n[[+surl]]\n\nIf successful, you can use the following password to log in:\n\nPassword:[[+pwd]]\n\nIf you did not request this email, then please ignore it.\n\nRegrads,\nSite Administrator';

$_lang['setting_websignupemail_message'] = 'Web Signup email';
$_lang['setting_websignupemail_message_desc'] = 'Here you can set the message sent to your web users when you create a web account for them and let the Content Manager send them an email containing their username and password. <br /><strong>Note:</strong> The following placeholders are replaced by the Content Manager when the message is sent: <br /><br />[[+sname]] - Name of your web site, <br />[[+saddr]] - Your web site email address, <br />[[+surl]] - Your site URL, <br />[[+uid]] - User\'s login name or id, <br />[[+pwd]] - User\'s password, <br />[[+ufn]] - User\'s full name. <br /><br /><strong>Leave the [[+uid]] and [[+pwd]] in the email, or else the username and password won\'t be sent in the mail and your users won\'t know their username or password!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Hello [[+uid]] \n\nHere are your login details for [[+sname]]:\n\nUsername: [[+uid]]\nPassword: [[+pwd]]\n\nOnce you log into [[+sname]] ([[+surl]]), you can change your password.\n\nRegards,\nSite Administrator';

$_lang['setting_welcome_screen'] = 'Show Welcome Screen';
$_lang['setting_welcome_screen_desc'] = 'If set to true, the welcome screen will show on the next successful loading of the welcome page, and then not show after that.';

$_lang['setting_welcome_screen_url'] = 'Welcome Screen URL';
$_lang['setting_welcome_screen_url_desc'] = 'The URL for the welcome screen that loads on first load of MODX Revolution.';

$_lang['setting_welcome_action'] = 'Welcome Action';
$_lang['setting_welcome_action_desc'] = 'The default controller to load when accessing the manager when no controller is specified in the URL.';

$_lang['setting_welcome_namespace'] = 'Welcome Namespace';
$_lang['setting_welcome_namespace_desc'] = 'The namespace the Welcome Action belongs to.';

$_lang['setting_which_editor'] = 'Editor to use';
$_lang['setting_which_editor_desc'] = 'Here you can select which Rich Text Editor you wish to use. You can download and install additional Rich Text Editors from Package Management.';

$_lang['setting_which_element_editor'] = 'Editor to use for Elements';
$_lang['setting_which_element_editor_desc'] = 'Here you can select which Rich Text Editor you wish to use when editing Elements. You can download and install additional Rich Text Editors from Package Management.';

$_lang['setting_xhtml_urls'] = 'XHTML URLs';
$_lang['setting_xhtml_urls_desc'] = 'If set to true, all URLs generated by MODX will be XHTML-compliant, including encoding of the ampersand character.';

$_lang['setting_default_context'] = 'Default Context';
$_lang['setting_default_context_desc'] = 'Select the default Context you wish to use for new Resources.';

$_lang['setting_auto_isfolder'] = 'Set container automatically';
$_lang['setting_auto_isfolder_desc'] = 'If set to yes, container property will be changed automatically.';

$_lang['setting_default_username'] = 'Username publik';
$_lang['setting_default_username_desc'] = 'Username yang akan digunakan untuk user publik (belum melakukan login).';

$_lang['setting_manager_use_fullname'] = 'Show fullname in manager header ';
$_lang['setting_manager_use_fullname_desc'] = 'If set to yes, the content of the "fullname" field will be shown in manager instead of "loginname"';

$_lang['log_snippet_not_found'] = 'Log snippets not found';
$_lang['log_snippet_not_found_desc'] = 'If set to yes, snippets that are called but not found will be logged to the error log.';
