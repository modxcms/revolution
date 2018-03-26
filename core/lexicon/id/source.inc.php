<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Izin akses';
$_lang['base_path'] = 'Path dasar';
$_lang['base_path_relative'] = 'Relatif Path basis?';
$_lang['base_url'] = 'Base URL';
$_lang['base_url_relative'] = 'Base URL relatif?';
$_lang['minimum_role'] = 'Peran minimal';
$_lang['path_options'] = 'Pilihan path';
$_lang['policy'] = 'Kebijakan';
$_lang['source'] = 'Sumber media';
$_lang['source_access_add'] = 'Menambahkan grup pengguna';
$_lang['source_access_remove'] = 'Menghapus akses';
$_lang['source_access_remove_confirm'] = 'Apakah Anda yakin ingin menghapus akses ke sumber untuk kelompok pengguna ini?';
$_lang['source_access_update'] = 'Memperbarui akses';
$_lang['source_create'] = 'Membuat sumber Media baru';
$_lang['source_description_desc'] = 'Deskripsi singkat dari sumber Media.';
$_lang['source_duplicate'] = 'Sumber Media duplikat';
$_lang['source_err_ae_name'] = 'Sumber Media dengan nama itu sudah ada! Silakan tentukan nama baru.';
$_lang['source_err_nf'] = 'Media sumber tidak ditemukan!';
$_lang['source_err_nfs'] = 'Ada sumber Media dapat ditemukan dengan id: [[+id]].';
$_lang['source_err_ns'] = 'Silakan tentukan sumber Media.';
$_lang['source_err_ns_name'] = 'Silakan tentukan nama untuk sumber Media.';
$_lang['source_name_desc'] = 'Nama sumber Media.';
$_lang['source_properties.intro_msg'] = 'Mengelola properti sumber ini di bawah ini.';
$_lang['source_remove'] = 'Menghapus sumber Media';
$_lang['source_remove_confirm'] = 'Apakah Anda yakin Anda ingin menghapus sumber Media ini? Ini mungkin mematahkan setiap TV telah ditetapkan untuk sumber ini.';
$_lang['source_remove_multiple'] = 'Menghapus beberapa sumber Media';
$_lang['source_remove_multiple_confirm'] = 'Apakah Anda yakin Anda ingin menghapus sumber Media ini? Ini mungkin menghentikan setiap TV yang telah ditetapkan untuk sumber ini.';
$_lang['source_update'] = 'Perbarui sumber Media';
$_lang['source_type'] = 'Jenis Sumber';
$_lang['source_type_desc'] = 'Jenisnya atau driver dari sumber Media. Sumber akan menggunakan driver ini untuk menyambungkan ketika mengumpulkan data. Sebagai contoh: sistem berkas akan mengambil file dari sistem file. S3 akan mendapatkan file dari S3 ember.';
$_lang['source_type.file'] = 'Sistem berkas';
$_lang['source_type.file_desc'] = 'Sumber berbasis filesystem yang menavigasi file server Anda.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Menavigasi Amazon S3.';
$_lang['source_types'] = 'Jenis Sumber';
$_lang['source_types.intro_msg'] = 'Ini adalah daftar semua jenis diinstal Media sumber Anda memiliki contoh MODX ini.';
$_lang['source.access.intro_msg'] = 'Di sini Anda dapat membatasi sumber Media grup pengguna tertentu dan menerapkan kebijakan untuk kelompok pengguna tersebut. Sumber Media dengan kelompok pengguna tidak melekat padanya tersedia untuk semua pengguna pengelola.';
$_lang['sources'] = 'Sumber media';
$_lang['sources.intro_msg'] = 'Mengelola semua Media sumber Anda di sini.';
$_lang['user_group'] = 'Kelompok pengguna';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes';
$_lang['prop_file.allowedFileTypes_desc'] = 'Jika set, akan membatasi file yang ditunjukkan untuk hanya ekstensi tertentu. Silakan tentukan dalam daftar dipisahkan dengan koma, tanpa titik-titik yang sebelumnya ekstensi.';
$_lang['basePath'] = 'basePath';
$_lang['prop_file.basePath_desc'] = 'File path mengarahkan ke sumber.';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'Jika pengaturan dasar jalan di atas tidak relatif jalur menginstal MODX, pengaturan No.';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'URL yang sumber ini dapat diakses dari.';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'Jika benar, MODX hanya akan menambah baseUrl jika tidak ada garis miring (/) ditemukan pada permulaan URL ketika me-render TV. Berguna untuk menentukan nilai TV di luar baseUrl.';
$_lang['baseUrlRelative'] = 'baseUrlRelative';
$_lang['prop_file.baseUrlRelative_desc'] = 'Jika pengaturan dasar jalan di atas tidak relatif jalur menginstal MODX, pengaturan di batalkan.';
$_lang['imageExtensions'] = 'imageExtensions';
$_lang['prop_file.imageExtensions_desc'] = 'Daftar ekstensi file yang dipisahkan dengan koma digunakan sebagai gambar. MODX akan berusaha untuk membuat thumbnail file dengan ekstensi ini.';
$_lang['skipFiles'] = 'skipFiles';
$_lang['prop_file.skipFiles_desc'] = 'Daftar yang dipisahkan oleh koma. MODX akan melewatkan dan menyembunyikan file dan folder yang cocok dengan salah satu ini.';
$_lang['thumbnailQuality'] = 'thumbnailQuality';
$_lang['prop_file.thumbnailQuality_desc'] = 'Kualitas thumbnail diberikan, dalam skala dari 0-100.';
$_lang['thumbnailType'] = 'thumbnailType';
$_lang['prop_file.thumbnailType_desc'] = 'Jenis gambar dibuat sebagai thumbnail.';

/* s3 source type */
$_lang['bucket'] = 'Bucket';
$_lang['prop_s3.bucket_desc'] = 'Data yang dimuat berasal dari bucket S3.';
$_lang['prop_s3.key_desc'] = 'Amazon kunci untuk otentikasi ke bucket.';
$_lang['prop_s3.imageExtensions_desc'] = 'Daftar ekstensi file yang dipisahkan dengan koma digunakan sebagai gambar. MODX akan berusaha untuk membuat thumbnail file dengan ekstensi ini.';
$_lang['prop_s3.secret_key_desc'] = 'Kunci rahasia Amazon untuk otentikasi ke bucket.';
$_lang['prop_s3.skipFiles_desc'] = 'Daftar yang dipisahkan oleh koma. MODX akan melewatkan dan menyembunyikan file dan folder yang cocok dengan salah satu ini.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'Kualitas thumbnail diberikan, dalam skala dari 0-100.';
$_lang['prop_s3.thumbnailType_desc'] = 'Jenis gambar dibuat sebagai thumbnail.';
$_lang['prop_s3.url_desc'] = 'URL adalah contoh Amazon S3.';
$_lang['s3_no_move_folder'] = 'Pemindahan folder saat ini tidak didukung oleh S3 driver.';
$_lang['prop_s3.region_desc'] = 'Daerah ember. Contoh: kami-barat-1';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';
