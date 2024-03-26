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
$_lang['area_mail'] = 'Surat';
$_lang['area_manager'] = 'Back-end Manajer';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Sesi dan Cookie';
$_lang['area_static_elements'] = 'Static Elements';
$_lang['area_static_resources'] = 'Static Resources';
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
$_lang['setting_err'] = 'Silakan periksa data Anda untuk bidang-bidang berikut: ';
$_lang['setting_err_ae'] = 'Pengaturan dengan kata kunci sudah ada. Silakan tentukan nama kunci lain.';
$_lang['setting_err_nf'] = 'Pengaturan tidak ditemukan.';
$_lang['setting_err_ns'] = 'Pengaturan tidak ditentukan';
$_lang['setting_err_not_editable'] = 'This setting can\'t be edited in the grid. Please use the gear/context menu to edit the value!';
$_lang['setting_err_remove'] = 'An error occurred while trying to delete the setting.';
$_lang['setting_err_save'] = 'Terjadi kesalahan saat mencoba untuk menyimpan pengaturan.';
$_lang['setting_err_startint'] = 'Pengaturan mungkin tidak dimulai dengan bilangan bulat.';
$_lang['setting_err_invalid_document'] = 'Ada tidak ada dokumen dengan ID %d. Silakan tentukan dokumen yang ada.';
$_lang['setting_remove_confirm'] = 'Apakah Anda yakin Anda ingin menghapus pengaturan ini? Ini mungkin akan menghentikan instalasi MODX.';
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

$_lang['setting_allow_tv_eval'] = 'Nonaktifkan eval di TV binding';
$_lang['setting_allow_tv_eval_desc'] = 'Pilih opsi ini untuk mengaktifkan atau menonaktifkan eval dalam pengikatan TV. Jika opsi ini disetel ke no, kode / nilai hanya akan ditangani sebagai teks biasa.';

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

$_lang['setting_automatic_template_assignment'] = 'Penugasa Template Otomatis';
$_lang['setting_automatic_template_assignment_desc'] = 'Pilih bagaimana template ditetapkan ke Resources baru saat pembuatan. Pilihannya meliputi: system(template default dari pengaturan sistem), parent (mewarisi template induk), atau sibling(mewarisi template sibling yang paling sering digunakan)';

$_lang['setting_base_help_url'] = 'Bantuan dasar URL';
$_lang['setting_base_help_url_desc'] = 'URL dasar yang digunakan untuk membangun link bantuan di bagian atas kanan dari halaman di manager.';

$_lang['setting_blocked_minutes'] = 'Menit yang diblokir';
$_lang['setting_blocked_minutes_desc'] = 'Di sini Anda dapat memasukkan jumlah menit dimana pengguna akan diblokir jika mereka mencapai jumlah maksimum dari izin percobaan login yang gagal. Masukkan nilai ini sebagai angka itu saja (tidak koma, spasi dll.)';

$_lang['setting_cache_alias_map'] = 'Mengaktifkan cache konteks Alias peta';
$_lang['setting_cache_alias_map_desc'] = 'Ketika diaktifkan, Semua URI sumber daya cache ke dalam konteks. Mengaktifkan situs yang lebih kecil dan menonaktifkan pada situs yang lebih besar untuk kinerja yang lebih baik.';

$_lang['setting_use_context_resource_table'] = 'Gunakan tabel sumber konteks';
$_lang['setting_use_context_resource_table_desc'] = 'Bila diaktifkan, penyegaran konteks menggunakan tabel context_resource. Ini memungkinkan Anda memprogram memiliki satu sumber dalam beberapa konteks. Jika Anda tidak menggunakan beberapa konteks sumber daya melalui API, Anda dapat menyetel ini ke false. Di situs besar Anda akan mendapatkan potensi peningkatan kinerja manajer saat itu.';

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

$_lang['setting_cache_expires'] = 'Waktu kedaluwarsa untuk Cache standar';
$_lang['setting_cache_expires_desc'] = 'Nilai ini (dalam detik) menetapkan jumlah waktu cache file terakhir untuk caching standar.';

$_lang['setting_cache_resource_clear_partial'] = 'Clear Partial Resource Cache for provided contexts';
$_lang['setting_cache_resource_clear_partial_desc'] = 'When enabled, MODX refresh will only clear resource cache for the provided contexts.';

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

$_lang['setting_default_media_source_type'] = 'Default Media Source Type';
$_lang['setting_default_media_source_type_desc'] = 'The default selected Media Source Type when creating a new Media Source.';

$_lang['setting_photo_profile_source'] = 'User Profile Photo Source';
$_lang['setting_photo_profile_source_desc'] = 'Specifies the Media Source to use for storing and retrieving profile photos/avatars. If not specified, the default Media Source will be used.';

$_lang['setting_default_template'] = 'Pola Bawaan';
$_lang['setting_default_template_desc'] = 'Pilih Template standar yang ingin Anda gunakan untuk Sumber Daya baru. Anda masih dapat memilih template yang berbeda dalam editor Sumber Daya, pengaturan ini hanya pemilihan awal pada salah satu dari Template Anda untuk Anda.';

$_lang['setting_default_per_page'] = 'Default Per halaman';
$_lang['setting_default_per_page_desc'] = 'Menjadi nomor standar hasil untuk menunjukkan di grid seluruh manajer.';

$_lang['setting_emailsender'] = 'Pendaftaran Email dari alamat';
$_lang['setting_emailsender_desc'] = 'Di sini Anda dapat menentukan email alamat yang digunakan ketika mengirim pengguna username dan password.';
$_lang['setting_emailsender_err'] = 'Mohon sebutkan alamat email administrasi.';

$_lang['setting_enable_dragdrop'] = 'Mengaktifkan Drag/Drop di pohon-pohon sumber elemen';
$_lang['setting_enable_dragdrop_desc'] = 'Jika off, akan mencegah penyeretan dan penjatuhan di Sumber Daya dan Elemen pohon.';

$_lang['setting_enable_template_picker_in_tree'] = 'Enable the Template Picker in Resource Trees';
$_lang['setting_enable_template_picker_in_tree_desc'] = 'Enable this to use the template picker modal window when creating a new resource in the tree.';

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

$_lang['setting_feed_modx_news'] = 'MODX News Feed URL';
$_lang['setting_feed_modx_news_desc'] = 'Menetapkan URL untuk RSS feed untuk panel MODX berita di manager.';

$_lang['setting_feed_modx_news_enabled'] = 'MODX Berita Feed diaktifkan';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Jika \'Tidak\', MODX akan menyembunyikan Berita feed di bagian Selamat datang dari manajer.';

$_lang['setting_feed_modx_security'] = 'MODX keamanan pemberitahuan URL Feed';
$_lang['setting_feed_modx_security_desc'] = 'Menetapkan URL untuk RSS feed untuk panel MODX keamanan pemberitahuan di manager.';

$_lang['setting_feed_modx_security_enabled'] = 'MODX keamanan Feed diaktifkan';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Jika \'Tidak\', MODX akan menyembunyikan Berita keamanan di bagian Selamat datang dari manajer.';

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
$_lang['setting_link_tag_scheme_desc'] = 'Skema penghasilan URL untuk tag [[~ id]]. Pilihan yang tersedia <a href="http://api.modx.com/revolution/2.2/db_core_model_modx_modx.class.html#\modX::makeUrl()"> di sini</a>.';

$_lang['setting_locale'] = 'Lokal';
$_lang['setting_locale_desc'] = 'Mengatur lokal untuk sistem. Biarkan kosong untuk menggunakan default. Lihat <a href="http://php.net/setlocale" target="_blank"> dokumentasi PHP</a> untuk informasi lebih lanjut.';

$_lang['setting_lock_ttl'] = 'Mengunci waktu-to-Live';
$_lang['setting_lock_ttl_desc'] = 'Jumlah detik kunci pada sumber daya yang akan tetap jika pengguna tidak aktif.';

$_lang['setting_log_level'] = 'Tingkat pendataan';
$_lang['setting_log_level_desc'] = 'Tingkat pendataan default; semakin rendah tingkat, lebih sedikit pesan yang masuk. Pilihan yang tersedia: 0 (FATAL), 1 (ERROR), 2 (PERINGATKAN), 3 (INFO) dan 4 (DEBUG).';

$_lang['setting_log_target'] = 'Target penebangan';
$_lang['setting_log_target_desc'] = 'Target login bawaan yang mana pesan log ditulis. Pilihan yang tersedia: \'FILE\', \'HTML\', atau \'ECHO\'. Default adalah \'FILE\' jika tidak ditentukan.';

$_lang['setting_log_deprecated'] = 'Log Deprecated Functions';
$_lang['setting_log_deprecated_desc'] = 'Enable to receive notices in your error log when deprecated functions are used.';

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

$_lang['setting_mail_smtp_pass'] = 'Kata sandi SMTP';
$_lang['setting_mail_smtp_pass_desc'] = 'Password untuk melakukan otentikasi ke SMTP melawan.';

$_lang['setting_mail_smtp_port'] = 'SMTP Port';
$_lang['setting_mail_smtp_port_desc'] = 'Menetapkan default SMTP server port.';

$_lang['setting_mail_smtp_secure'] = 'SMTP Secure';
$_lang['setting_mail_smtp_secure_desc'] = 'Sets SMTP secure encyption type. Options are "", "ssl" or "tls"';

$_lang['setting_mail_smtp_autotls'] = 'SMTP Auto TLS';
$_lang['setting_mail_smtp_autotls_desc'] = 'Whether to enable TLS encryption automatically if a server supports it, even if "SMTP Secure" is not set to "tls"';

$_lang['setting_mail_smtp_single_to'] = 'SMTP tunggal untuk';
$_lang['setting_mail_smtp_single_to_desc'] = 'Menyediakan kemampuan untuk memiliki untuk bidang proses individu email, bukan pengiriman ke seluruh alamat.';

$_lang['setting_mail_smtp_timeout'] = 'SMTP Timeout';
$_lang['setting_mail_smtp_timeout_desc'] = 'Menetapkan SMTP server timeout dalam detik. Fungsi ini tidak akan bekerja dalam server win32.';

$_lang['setting_mail_smtp_user'] = 'SMTP pengguna';
$_lang['setting_mail_smtp_user_desc'] = 'Pengguna untuk melakukan otentikasi ke SMTP melawan.';

$_lang['setting_main_nav_parent'] = 'Menu utama';
$_lang['setting_main_nav_parent_desc'] = 'Wadah digunakan untuk menarik semua catatan untuk menu utama.';

$_lang['setting_manager_direction'] = 'Arah manajer teks';
$_lang['setting_manager_direction_desc'] = 'Memilih arah yang teks yang akan diterjemahkan dalam manajer, kiri ke kanan atau kanan ke kiri.';

$_lang['setting_manager_date_format'] = 'Format tanggal Manajer';
$_lang['setting_manager_date_format_desc'] = 'Format string, dalam format date() pada PHP PHP, untuk tanggal yang diwakili dalam manajer.';

$_lang['setting_manager_favicon_url'] = 'Manajer Favicon URL';
$_lang['setting_manager_favicon_url_desc'] = 'Jika diatur, akan memuat URL ini sebagai favicon untuk manajer MODX. Harus sebuah URL relatif untuk manajer / direktori, atau URL absolut.';

$_lang['setting_manager_login_url_alternate'] = 'URL alternatif manajer Login';
$_lang['setting_manager_login_url_alternate_desc'] = 'URL alternatif untuk mengirim pengguna tidak terauthentikasi ke ketika mereka perlu login ke pengelola. Login form tidak harus login pengguna untuk konteks "mgr" untuk bekerja.';

$_lang['setting_manager_tooltip_enable'] = 'Enable Manager Tooltips';
$_lang['setting_manager_tooltip_delay'] = 'Delay Time for Manager Tooltips';

$_lang['setting_login_background_image'] = 'Login Background Image';
$_lang['setting_login_background_image_desc'] = 'The background image to use in the manager login. This will automatically stretch to fill the screen.';

$_lang['setting_login_logo'] = 'Login Logo';
$_lang['setting_login_logo_desc'] = 'The logo to show in the top left of the manager login. When left empty, it will show the MODX logo.';

$_lang['setting_login_help_button'] = 'Show Help Button';
$_lang['setting_login_help_button_desc'] = 'When enabled you will find a help button on the login screen. It\'s possible to customize the information shown with the following lexicon entries in core/login: login_help_button_text, login_help_title, and login_help_text.';

$_lang['setting_manager_login_start'] = 'Manajer Login Startup';
$_lang['setting_manager_login_start_desc'] = 'Masukkan ID dokumen Anda ingin mengirim pengguna setelah ia telah login ke pengelola. <strong>Catatan: Pastikan Anda telah memasukkan ID milik dokumen yang ada, dan bahwa ia telah diterbitkan dan dapat diakses oleh pengguna ini!</strong>';

$_lang['setting_manager_theme'] = 'Pengaturan Tema';
$_lang['setting_manager_theme_desc'] = 'Pilih tema untuk manajer konten.';

$_lang['setting_manager_logo'] = 'Manager Logo';
$_lang['setting_manager_logo_desc'] = 'The logo to show in the Content Manager header.';

$_lang['setting_manager_time_format'] = 'Format waktu Manajer';
$_lang['setting_manager_time_format_desc'] = 'Format string, dalam format date() pada PHP PHP, untuk pengaturan waktu yang diwakili dalam manajer.';

$_lang['setting_manager_use_tabs'] = 'Menggunakan tab di Manajer Layout';
$_lang['setting_manager_use_tabs_desc'] = 'Jika benar, manajer akan menggunakan tab untuk render panel konten. Jika tidak, itu akan menggunakan portal.';

$_lang['setting_manager_week_start'] = 'Mulai minggu';
$_lang['setting_manager_week_start_desc'] = 'Menentukan hari mulai minggu. Menggunakan 0 (atau Tinggalkan kosong) untuk hari Minggu, 1 untuk hari Senin dan seterusnya...';

$_lang['setting_mgr_tree_icon_context'] = 'Ikon untuk Context';
$_lang['setting_mgr_tree_icon_context_desc'] = 'Tentukan kelas CSS di sini untuk digunakan untuk menampilkan ikon konteks di pohon. Anda dapat menggunakan pengaturan ini pada setiap konteks untuk menyesuaikan ikon per konteks.';

$_lang['setting_mgr_source_icon'] = 'Ikon untuk Media Source';
$_lang['setting_mgr_source_icon_desc'] = 'Tunjukkan kelas CSS yang akan digunakan untuk menampilkan ikon Sumber Media di pohon file. Default ke "icon-folder-open-o"';

$_lang['setting_modRequest.class'] = 'Permintaan Handler kelas';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_tree_hide_files'] = 'Media Browser Tree Sembunyikan File';
$_lang['setting_modx_browser_tree_hide_files_desc'] = 'If true the files inside folders are not displayed in the Media Browser source tree.';

$_lang['setting_modx_browser_tree_hide_tooltips'] = 'Media Browser Tree Hide Tooltips';
$_lang['setting_modx_browser_tree_hide_tooltips_desc'] = 'Jika benar, tidak ada tooltip pratinjau gambar yang ditampilkan saat melayang di atas file di pohon Browser Media. Standar ke true.';

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
$_lang['setting_parser_recurse_uncacheable_desc'] = 'Jika dinonaktifkan, elemen yang tidak dapat dicache mungkin memiliki keluaran mereka dalam cache dalam konten elemen yang dapat disimpan. Nonaktifkan ini ONLY jika Anda mengalami masalah dengan parsing nested yang kompleks yang berhenti bekerja seperti yang diharapkan.';

$_lang['setting_password_generated_length'] = 'Jumlah Karakter Auto-Generate Password';
$_lang['setting_password_generated_length_desc'] = 'Panjang karakter untuk auto-generated password untuk user.';

$_lang['setting_password_min_length'] = 'Minimum Karakter untuk Password';
$_lang['setting_password_min_length_desc'] = 'Panjang minimum untuk kata sandi untuk Pengguna.';

$_lang['setting_preserve_menuindex'] = 'Pertahankan Indeks Menu Saat Sumber Menggandakan';
$_lang['setting_preserve_menuindex_desc'] = 'Saat menduplikat Resources, urutan indeks menu juga akan dipertahankan.';

$_lang['setting_principal_targets'] = 'Target ACL terlalu membebani';
$_lang['setting_principal_targets_desc'] = 'Sesuaikan target ACL yang akan dimuat untuk Pengguna MODX.';

$_lang['setting_proxy_auth_type'] = 'Jenis Otentikasi Proxy';
$_lang['setting_proxy_auth_type_desc'] = 'Mendukung BASIC atau NTLM.';

$_lang['setting_proxy_host'] = 'Proxy Host';
$_lang['setting_proxy_host_desc'] = 'Jika server Anda menggunakan proxy, tetapkan nama host di sini untuk mengaktifkan fitur MODX yang mungkin perlu menggunakan proxy, seperti Package Management.';

$_lang['setting_proxy_password'] = 'Kata sandi proxy';
$_lang['setting_proxy_password_desc'] = 'Kata sandi diperlukan untuk melakukan otentikasi ke server proxy Anda.';

$_lang['setting_proxy_port'] = 'Proxy Host';
$_lang['setting_proxy_port_desc'] = 'Port untuk server proxy Anda.';

$_lang['setting_proxy_username'] = 'Nama engguna Proxy';
$_lang['setting_proxy_username_desc'] = 'Nama pengguna untuk melakukan otentikasi terhadap server proxy Anda.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb Allow src Di atas Document Root';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Menunjukkan apakah path src diperbolehkan di luar akar dokumen. Ini berguna untuk penerapan multi-konteks dengan beberapa host virtual.';

$_lang['setting_phpthumb_cache_maxage'] = 'phpThumb Max Cache Age';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Hapus thumbnail dalam cache yang belum pernah diakses lebih dari X hari.';

$_lang['setting_phpthumb_cache_maxsize'] = 'ukuran cache phpThumb maksimal';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Hapus gambar mini yang baru saja diakses saat cache tumbuh lebih besar dari ukuran X megabyte.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'phpThumb Max Cache Files';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Hapus gambar mini yang baru saja diakses saat cache memiliki lebih dari X file.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'sumber file cache phpThumb';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Mengamankan atau tidak menyimpan file sumber saat dimuat. Dianjurkan untuk tidak aktif.';

$_lang['setting_phpthumb_document_root'] = 'Root dokumen PHPThumb';
$_lang['setting_phpthumb_document_root_desc'] = 'Terapkan ini jika Anda mengalami masalah dengan variabel server DOCUMENT_ROOT, atau mendapatkan kesalahan dengan OutputThumbnail atau !Is_resource. atur ke path root dokumen absolut yang ingin Anda gunakan. Jika ini kosong, MODX akan menggunakan variabel server DOCUMENT_ROOT.';

$_lang['setting_phpthumb_error_bgcolor'] = 'kesalahan warna latar belakang phpThumb';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'Nilai hex, tanpa #, yang menunjukkan warna latar belakang untuk hasil kesalahan phpThumb.';

$_lang['setting_phpthumb_error_fontsize'] = 'kesalahan ukuran tulisan phpThumb';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'Nilai em yang menunjukkan ukuran font yang akan digunakan untuk teks yang muncul dalam output kesalahan phpThumb.';

$_lang['setting_phpthumb_error_textcolor'] = 'kesalahan ukuran warna phpThumb';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'Nilai hex, tanpa tanda #, menunjukkan ukuran warna untuk teks yang muncul dalam hasil kesalahan phpThumb.';

$_lang['setting_phpthumb_far'] = 'rasio Aspek Gaya phpThumb';
$_lang['setting_phpthumb_far_desc'] = 'Default jauh pengaturan untuk phpThumb ketika digunakan di MODX. Defaultnya C untuk kekuatan rasio aspek menuju pusat.';

$_lang['setting_phpthumb_imagemagick_path'] = 'phpThumb ImageMagick Path';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Pilihan. Tetapkan jalur ImageMagick alternatif di sini untuk menghasilkan gambar mini dengan phpThumb, jika tidak ada dalam default PHP.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'tidak disetujui phpThumb Hotlinking';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Server jarak jauh diizinkan di parameter src kecuali Anda menonaktifkan hotlinking di phpThumb.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'hapus gambar phpThumb Hotlinking';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Menunjukkan apakah gambar yang dihasilkan dari server jarak jauh harus dihapus bila tidak diijinkan.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb Hotlinking tidak menerima pesan';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'Pesan yang diberikan bukan thumbnail saat upaya hotlinking ditolak.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb Hotlinking domain yang valid';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'Daftar comma-delimited hostname yang berlaku dalam src URLs.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb Offsite tida tersambung';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Nonaktifkan kemampuan orang lain untuk menggunakan phpThumb untuk merender gambar di situs mereka sendiri.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb Offsite terhubung ke penghapusan gambar';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Menunjukkan apakah gambar yang ditautkan dari server jauh harus dihapus bila tidak diijinkan.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb Offsite terhubung memerlukan pengarah';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'Jika diaktifkan, upaya penautan di luar kantor akan ditolak tanpa tajuk perujuk yang sah.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'diluar phpThumb lokasi Menghubungkan Pesan Tidak Diizinkan';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'Pesan yang diberikan alih-alih gambar mini saat upaya penghubung di luar kantor ditolak.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb Offsite terhubung domain yang valid';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'Daftar comma-delimited dari hostnames itu adalah perujuk yang valid untuk terhubung offsite.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb Offsite Menghubungkan Sumber yang sudah ditandai';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Pilihan. Jalur sistem file yang valid ke file yang akan digunakan sebagai sumber yang ditandai saat gambar Anda dijadikan di offsite oleh phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb memperjelas-memotong';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'Pengaturan zc default untuk phpThumb saat digunakan di MODX. Default ke 0 untuk mencegah penjelasan pemotongan.';

$_lang['setting_publish_default'] = 'Penerbitan standar';
$_lang['setting_publish_default_desc'] = 'Pilih \'Ya\' untuk membuat semua sumber daya baru yang diterbitkan oleh default.';
$_lang['setting_publish_default_err'] = 'Tolong nyatakan apakah Anda ingin dokumen diterbitkan secara default atau tidak.';

$_lang['setting_quick_search_in_content'] = 'Allow search in content';
$_lang['setting_quick_search_in_content_desc'] = 'If \'Yes\', then the content of the element (resource, template, chunk, etc.) will also be available for quick search.';

$_lang['setting_quick_search_result_max'] = 'Number of items in search result';
$_lang['setting_quick_search_result_max_desc'] = 'Maximum number of elements for each type (resource, template, chunk, etc.) in the quick search result.';

$_lang['setting_request_controller'] = 'Permintaan pengaturan Filename';
$_lang['setting_request_controller_desc'] = 'Nama file dari pengontrol permintaan utama dari mana MODX dimuat. Sebagian besar pengguna bisa meninggalkan ini sebagai index.php.';

$_lang['setting_request_method_strict'] = 'Permintaan metode yang tepat';
$_lang['setting_request_method_strict_desc'] = 'Jika diaktifkan, permintaan melalui Permintaan ID Parameter akan diabaikan dengan FURLs diaktifkan, dan orang-orang melalui Permintaan Alias Parameter akan diabaikan tanpa FURLs diaktifkan.';

$_lang['setting_request_param_alias'] = 'Permintaan Alias Parameter';
$_lang['setting_request_param_alias_desc'] = 'Nama parameter GET untuk mengidentifikasi alias sumber saat mengarahkan ulang dengan FURL.';

$_lang['setting_request_param_id'] = 'Permintaan Parameter ID';
$_lang['setting_request_param_id_desc'] = 'Nama parameter dari GET untuk mengidentifikasi sumber IDs bila tidak menggunakan FURLs.';

$_lang['setting_resource_tree_node_name'] = 'Resource Tree Node Field';
$_lang['setting_resource_tree_node_name_desc'] = 'Menentukan Sumber daya yang digunakan ketika rendering node di Pokok Sumber. Default untuk pagetitle, meskipun setiap bidang Sumberdaya dapat digunakan, seperti menutitle, alias, longtitle, dll.';

$_lang['setting_resource_tree_node_name_fallback'] = 'Resource Tree Node Fallback Field';
$_lang['setting_resource_tree_node_name_fallback_desc'] = 'Menentukan bidang Sumber daya untuk digunakan sebagai alur mundur ketika rendering node di Pokok Sumber. Ini akan digunakan jika sumber daya yang memiliki nilai kosong untuk dikonfigurasi Sumber daya Tree Node Lapangan.';

$_lang['setting_resource_tree_node_tooltip'] = 'Resource Tree Tooltip Field';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Menentukan Sumber daya yang digunakan ketika merender node di Pokok Sumber. Setiap bidang Sumberdaya dapat digunakan, seperti menutitle, alias, longtitle, dll. Jika kosong, akan menjadi longtitle dengan keterangan di bawahnya.';

$_lang['setting_richtext_default'] = 'Richtext Default';
$_lang['setting_richtext_default_desc'] = 'Pilih \'Ya\' untuk membuat semua sumber Daya baru menggunakan Richtext Editor secara default.';

$_lang['setting_search_default'] = 'Searchable Default';
$_lang['setting_search_default_desc'] = 'Pilih \'Ya\' untuk membuat semua sumber daya baru dapat dicari secara default.';
$_lang['setting_search_default_err'] = 'Harap tentukan apakah dokumen Anda ingin dicari secara default atau tidak.';

$_lang['setting_server_offset_time'] = 'Server offset time';
$_lang['setting_server_offset_time_desc'] = 'Pilih jumlah perbedaan waktu jam di antara lokasi Anda dan di mana server berada.';

$_lang['setting_session_cookie_domain'] = 'Session Cookie Domain';
$_lang['setting_session_cookie_domain_desc'] = 'Gunakan setelan ini untuk menyesuaikan domain cookie sesi. Biarkan kosong untuk menggunakan domain saat ini.';

$_lang['setting_session_cookie_samesite'] = 'Session Cookie Samesite';
$_lang['setting_session_cookie_samesite_desc'] = 'Choose Lax or Strict.';

$_lang['setting_session_cookie_lifetime'] = 'Session Cookie Lifetime';
$_lang['setting_session_cookie_lifetime_desc'] = 'Gunakan setelan ini untuk menyesuaikan masa pakai cookie sesi dalam hitungan detik. Ini digunakan untuk mengatur masa pakai cookie sesi klien saat mereka memilih opsi \'remember me\' saat masuk.';

$_lang['setting_session_cookie_path'] = 'Session Cookie Path';
$_lang['setting_session_cookie_path_desc'] = 'Gunakan setelan ini untuk menyesuaikan jalur cookie untuk mengidentifikasi cookie sesi spesifik situs. Biarkan kosong untuk menggunakan MODX_BASE_URL.';

$_lang['setting_session_cookie_secure'] = 'Session Cookie Secure';
$_lang['setting_session_cookie_secure_desc'] = 'Aktifkan setelan ini untuk menggunakan cookie sesi aman. Ini mengharuskan situs Anda diakses melalui https, jika tidak, situs dan/atau pengelola Anda akan menjadi tidak dapat diakses.';

$_lang['setting_session_cookie_httponly'] = 'Session Cookie HttpOnly';
$_lang['setting_session_cookie_httponly_desc'] = 'Use this setting to set the HttpOnly flag on session cookies.';

$_lang['setting_session_gc_maxlifetime'] = 'Session Garbage Collector Max Lifetime';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Allows customization of the session.gc_maxlifetime PHP ini setting when using \'MODX\\Revolution\\modSessionHandler\'.';

$_lang['setting_session_handler_class'] = 'Session Handler Class Name';
$_lang['setting_session_handler_class_desc'] = 'For database managed sessions, use \'MODX\\Revolution\\modSessionHandler\'.  Leave this blank to use standard PHP session management.';

$_lang['setting_session_name'] = 'Session Name';
$_lang['setting_session_name_desc'] = 'Gunakan pengaturan ini untuk menyesuaikan nama sesi yang digunakan untuk sesi di MODX. Biarkan kosong untuk menggunakan nama sesi PHP default.';

$_lang['setting_settings_version'] = 'Settings Version';
$_lang['setting_settings_version_desc'] = 'Saat ini versi yang terinstal MODX.';

$_lang['setting_settings_distro'] = 'Pengaturan Distribusi';
$_lang['setting_settings_distro_desc'] = 'Saat ini diinstal distribusi MODX.';

$_lang['setting_set_header'] = 'Set HTTP Headers';
$_lang['setting_set_header_desc'] = 'When enabled, MODX will attempt to set the HTTP headers for Resources.';

$_lang['setting_send_poweredby_header'] = 'Kirim X-Powered-By Header';
$_lang['setting_send_poweredby_header_desc'] = 'Saat diaktifkan, MODX akan mengirim header "X-Powered-By" untuk mengidentifikasi situs ini sesuai dengan MODX. Ini membantu pelacakan penggunaan MODX global melalui pelacak pihak ketiga yang memeriksa situs Anda. Karena ini mempermudah identifikasi lokasi situs Anda, mungkin akan menimbulkan risiko keamanan yang sedikit meningkat jika kerentanan ditemukan di MODX.';

$_lang['setting_show_tv_categories_header'] = 'Show "Categories" Tabs Header with TVs';
$_lang['setting_show_tv_categories_header_desc'] = 'If "Yes", MODX will show the "Categories" header above the first category tab when editing TVs in a Resource.';

$_lang['setting_signupemail_message'] = 'Sign-up email';
$_lang['setting_signupemail_message_desc'] = 'Here you can set the message sent to your users when you create an account for them and let MODX send them an email containing their username and password. <br /><strong>Note:</strong> The following placeholders are replaced by the Content Manager when the message is sent: <br /><br />[[+sname]] - Name of your web site, <br />[[+saddr]] - Your web site email address, <br />[[+surl]] - Your site URL, <br />[[+uid]] - User\'s login name or id, <br />[[+pwd]] - User\'s password, <br />[[+ufn]] - User\'s full name. <br /><br /><strong>Leave the [[+uid]] and [[+pwd]] in the email, or else the username and password won\'t be sent in the mail and your users won\'t know their username or password!</strong>';
$_lang['setting_signupemail_message_default'] = 'Hello [[+uid]] \n\nHere are your login details for [[+sname]] Content Manager:\n\nUsername: [[+uid]]\nPassword: [[+pwd]]\n\nOnce you log into the Content Manager ([[+surl]]), you can change your password.\n\nRegards,\nSite Administrator';

$_lang['setting_site_name'] = 'Site name';
$_lang['setting_site_name_desc'] = 'Masukkan nama situs anda di sini.';
$_lang['setting_site_name_err']  = 'Silakan masukkan nama situs.';

$_lang['setting_site_start'] = 'Situs start';
$_lang['setting_site_start_desc'] = 'Masukkan ID dari Sumber daya yang ingin anda gunakan sebagai homepage di sini. dengan <strong>CATATAN: pastikan ID ini anda masukkan termasuk Sumber daya yang ada, dan bahwa hal itu telah diterbitkan!</strong>';
$_lang['setting_site_start_err'] = 'Silakan menentukan Sumber daya ID yang merupakan situs start.';

$_lang['setting_site_status'] = 'Site status';
$_lang['setting_site_status_desc'] = 'Pilih \'Ya\' untuk mempublikasikan situs anda di web. Jika anda memilih \'Tidak\' anda, pengunjung anda akan melihat \'Lokasi tidak tersedia pesan\', dan tidak akan dapat untuk menelusuri situs tersebut.';
$_lang['setting_site_status_err'] = 'Please select whether or not the site is online (Yes) or offline (No).';

$_lang['setting_site_unavailable_message'] = 'Site unavailable message';
$_lang['setting_site_unavailable_message_desc'] = 'Pesan untuk menunjukkan ketika situs sedang offline atau jika terjadi kesalahan. dengan <strong>Catatan: pesan Ini hanya akan ditampilkan jika halaman Situs tidak tersedia pilihan ini tidak ditetapkan.</strong>';

$_lang['setting_site_unavailable_page'] = 'Site unavailable page';
$_lang['setting_site_unavailable_page_desc'] = 'Enter the ID of the Resource you want to use as an offline page here. <strong>NOTE: make sure this ID you enter belongs to an existing Resource, and that it has been published!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Please specify the document ID for the site unavailable page.';

$_lang['setting_static_elements_automate_templates'] = 'Automate static elements for templates?';
$_lang['setting_static_elements_automate_templates_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for templates.';

$_lang['setting_static_elements_automate_tvs'] = 'Automate static elements for TVs?';
$_lang['setting_static_elements_automate_tvs_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for TVs.';

$_lang['setting_static_elements_automate_chunks'] = 'Automate static elements for chunks?';
$_lang['setting_static_elements_automate_chunks_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for chunks.';

$_lang['setting_static_elements_automate_snippets'] = 'Automate static elements for snippets?';
$_lang['setting_static_elements_automate_snippets_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for snippets.';

$_lang['setting_static_elements_automate_plugins'] = 'Automate static elements for plugins?';
$_lang['setting_static_elements_automate_plugins_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for plugins.';

$_lang['setting_static_elements_default_mediasource'] = 'Static elements default mediasource';
$_lang['setting_static_elements_default_mediasource_desc'] = 'Specify a default mediasource where you want to store the static elements in.';

$_lang['setting_static_elements_default_category'] = 'Static elements default category';
$_lang['setting_static_elements_default_category_desc'] = 'Specify a default category for creating new static elements.';

$_lang['setting_static_elements_basepath'] = 'Static elements basepath';
$_lang['setting_static_elements_basepath_desc'] = 'Basepath of where to store the static elements files.';

$_lang['setting_resource_static_allow_absolute'] = 'Allow absolute static resource path';
$_lang['setting_resource_static_allow_absolute_desc'] = 'This setting enables users to enter a fully qualified absolute path to any readable file on the server as the content of a static resource. Important: enabling this setting may be considered a significant security risk! It\'s strongly recommended to keep this setting disabled, unless you fully trust every single manager user.';

$_lang['setting_resource_static_path'] = 'Static resource base path';
$_lang['setting_resource_static_path_desc'] = 'When resource_static_allow_absolute is disabled, static resources are restricted to be within the absolute path provided here.  Important: setting this too wide may allow users to read files they shouldn\'t! It is strongly recommended to limit users to a specific directory such as {core_path}static/ or {assets_path} with this setting.';

$_lang['setting_symlink_merge_fields'] = 'Merge Resource Fields in Symlinks';
$_lang['setting_symlink_merge_fields_desc'] = 'If set to Yes, will automatically merge non-empty fields with target resource when forwarding using Symlinks.';

$_lang['setting_syncsite_default'] = 'Empty Cache default';
$_lang['setting_syncsite_default_desc'] = 'Pilih \'Ya\' untuk mengosongkan cache setelah Anda menyimpan sumber daya secara standar.';
$_lang['setting_syncsite_default_err'] = 'Tolong nyatakan apakah Anda ingin mengosongkan cache setelah menyimpan sumber daya secara standar.';

$_lang['setting_topmenu_show_descriptions'] = 'Show Descriptions in Main Menu';
$_lang['setting_topmenu_show_descriptions_desc'] = 'If set to \'No\', MODX will hide the descriptions from main menu items in the manager.';

$_lang['setting_tree_default_sort'] = 'Resource Tree Default Sort Field';
$_lang['setting_tree_default_sort_desc'] = 'The default sort field for the Resource tree when loading the manager.';

$_lang['setting_tree_root_id'] = 'Tree Root ID';
$_lang['setting_tree_root_id_desc'] = 'Set this to a valid ID of a Resource to start the left Resource tree at below that node as the root. The user will only be able to see Resources that are children of the specified Resource.';

$_lang['setting_tvs_below_content'] = 'Move TVs Below Content';
$_lang['setting_tvs_below_content_desc'] = 'Set this to Yes to move TVs below the Content when editing Resources.';

$_lang['setting_ui_debug_mode'] = 'UI Debug Mode';
$_lang['setting_ui_debug_mode_desc'] = 'Set this to Yes to output debug messages when using the UI for the default manager theme. You must use a browser that supports console.log.';

$_lang['setting_unauthorized_page'] = 'Unauthorized page';
$_lang['setting_unauthorized_page_desc'] = 'Masukkan Sumber ID yang ingin Anda kirimi pengguna jika mereka meminta Sumber yang aman atau tidak sah. <strong>CATATAN: Pastikan ID yang Anda masukkan milik sumber yang ada, dan itu telah dipublikasikan dan dapat diakses publik!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Silakan tentukan ID Sumber daya untuk halaman yang tidak sah.';

$_lang['setting_upload_files'] = 'Jenis berkas yang dapat diunggah';
$_lang['setting_upload_files_desc'] = 'Di sini Anda bisa memasukkan daftar berkas yang bisa diunggah ke \'assets/files/\' menggunakan Resource Manager. Harap masukkan ekstensi untuk tipe berkas, dipisahkan dengan koma.';

$_lang['setting_upload_file_exists'] = 'Check if uploaded file exists';
$_lang['setting_upload_file_exists_desc'] = 'When enabled an error will be shown when uploading a file that already exists with the same name. When disabled, the existing file will be quietly replaced with the new file.';

$_lang['setting_upload_images'] = 'Jenis gambar yang dapat diunggah';
$_lang['setting_upload_images_desc'] = 'Di sini Anda bisa memasukkan daftar berkas yang bisa diunggah menjadi \'asset/images/\' menggunakan Resource Manager. Harap masukkan ekstensi untuk jenis gambar, dipisahkan dengan tanda koma.';

$_lang['setting_upload_maxsize'] = 'Batas ukuran unggahan';
$_lang['setting_upload_maxsize_desc'] = 'Masukkan ukuran berkas maksimal yang bisa diunggah melalui file manager. Unggahan ukuran berkas harus dimasukkan dalam format bytes. <strong>CATATAN: File berukuran besar bisa memakan waktu lama untuk diunggah! </strong>';

$_lang['setting_upload_media'] = 'Jenis meida yang dapat diunggah';
$_lang['setting_upload_media_desc'] = 'Di sini Anda bisa memasukkan daftar berkas yang bisa diunggah menjadi \'asset/media/\' menggunakan Resource Manager. Harap masukkan ekstensi untuk jenis media, dipisahkan dengan koma.';

$_lang['setting_upload_translit'] = 'Transliterate names of uploaded files?';
$_lang['setting_upload_translit_desc'] = 'If this option is enabled, the name of an uploaded file will be transliterated according to the global transliteration rules.';

$_lang['setting_use_alias_path'] = 'Gunakan sahabat alias diri sendiri';
$_lang['setting_use_alias_path_desc'] = 'Atur pilihan ini menjadi \'ya\' akan menampilkan path lengkap ke sumber jika sumber memiliki alias. Misalnya, jika sumber dengan alias bernama \'child\' berada di dalam lingkupan sumber dengan alias disebut \'parent\', maka seluruh alias path ke sumber akan ditampilkan sebagai \'/parent/child.html\'.<br/><strong>CATATAN: Saat menyetel opsi ini ke \'Ya\' (mengaktifkan jalur alias), item referensi (seperti gambar, CSS, JavaScripts, dll.) gunakan jalur absolut, misalnya \'/ aset / gambar\' sebagai bertentangan dengan \'aset / gambar\'. Dengan demikian Anda akan mencegah pencarian (atau jaringan pencarian) menambahkan jalur relatif ke jalur alias.</strong>';

$_lang['setting_use_editor'] = 'Mengaktifkan Editor Teks Kaya';
$_lang['setting_use_editor_desc'] = 'Apakah anda ingin mengaktifkan editor teks kaya? Jika anda lebih nyaman menulis HTML, maka anda dapat mengubah editor menggunakan pengaturan ini. Perhatikan bahwa pengaturan ini berlaku untuk semua dokumen dan semua pengguna!';
$_lang['setting_use_editor_err'] = 'Sebutkan apakah atau tidak anda ingin menggunakan RTE editor.';

$_lang['setting_use_frozen_parent_uris'] = 'Gunakan Frozen URI dari Resource Induk';
$_lang['setting_use_frozen_parent_uris_desc'] = 'Bila diaktifkan, sumber daya URI untuk anak-anak akan relatif terhadap URI beku dari salah satu orang tuanya, dengan mengabaikan alias sumber daya yang tinggi di pohon.';

$_lang['setting_use_multibyte'] = 'Gunakan Multibyte Extension';
$_lang['setting_use_multibyte_desc'] = 'Set ke benar jika anda ingin menggunakan ekstensi mbstring untuk multibyte karakter di instalasi MODX. Hanya set ke benar jika anda memiliki mbstring PHP ekstensi diinstal.';

$_lang['setting_use_weblink_target'] = 'Gunakan tujuan link pencarian';
$_lang['setting_use_weblink_target_desc'] = 'Atur ke benar jika Anda ingin memiliki tagar tautan MODX dan makeUrl() menghasilkan tautan sebagai URL tujuan untuk WebLinks. Jika tidak, URL MODX internal akan dihasilkan oleh tagar tautan dan metode makeUrl().';

$_lang['setting_user_nav_parent'] = 'Menu user induk';
$_lang['setting_user_nav_parent_desc'] = 'Wadah yang digunakan untuk menarik semua catatan untuk menu pengguna.';

$_lang['setting_welcome_screen'] = 'Tampilkan Layar Selamat Datang';
$_lang['setting_welcome_screen_desc'] = 'Apabila aturan ke benar, layar selamat datang akan tampil pada pemuatan halaman selamat datang berikutnya, dan kemudian tidak muncul setelah itu.';

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
$_lang['setting_xhtml_urls_desc'] = 'Jika diset ke benar, semua Url yang dihasilkan oleh MODX akan XHTML-compliant, termasuk pengkodean karakter ampersand.';

$_lang['setting_default_context'] = 'Default Context';
$_lang['setting_default_context_desc'] = 'Pilih default Konteks yang ingin anda gunakan untuk sumber Daya baru.';

$_lang['setting_auto_isfolder'] = 'Set container automatically';
$_lang['setting_auto_isfolder_desc'] = 'Jika diatur ke ya, wadah properti akan berubah secara otomatis.';

$_lang['setting_default_username'] = 'Username publik';
$_lang['setting_default_username_desc'] = 'Username yang akan digunakan untuk user publik (belum melakukan login).';

$_lang['setting_manager_use_fullname'] = 'Tampilkan nama lengkap di header manajer ';
$_lang['setting_manager_use_fullname_desc'] = 'Jika disetel ke ya, isi field "nama lengkap" akan ditampilkan di pengelola alih-alih "loginname"';

$_lang['setting_log_snippet_not_found'] = 'Cuplikan tidak ditemukan';
$_lang['setting_log_snippet_not_found_desc'] = 'Jika diatur ke ya, cuplikan yang dipanggil namun tidak ditemukan akan masuk ke log kesalahan.';

$_lang['setting_error_log_filename'] = 'Error log filename';
$_lang['setting_error_log_filename_desc'] = 'Customize the filename of the MODX error log file (includes file extension).';

$_lang['setting_error_log_filepath'] = 'Error log path';
$_lang['setting_error_log_filepath_desc'] = 'Optionally set a absolute path the a custom error log location. You might use placehodlers like {cache_path}.';

$_lang['setting_passwordless_activated'] = 'Activate passwordless login';
$_lang['setting_passwordless_activated_desc'] = 'When enabled, users will enter their email address to receive a one-time login link, rather than entering a username and password.';

$_lang['setting_passwordless_expiration'] = 'Passwordless login expiration';
$_lang['setting_passwordless_expiration_desc'] = 'How long a one-time login link is valid in seconds.';

$_lang['setting_static_elements_html_extension'] = 'Static elements html extension';
$_lang['setting_static_elements_html_extension_desc'] = 'The extension for files used by static elements with HTML content.';
