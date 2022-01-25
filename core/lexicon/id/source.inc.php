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
$_lang['source_access_remove'] = 'Delete Access';
$_lang['source_access_remove_confirm'] = 'Are you sure you want to delete Access to this Source for this User Group?';
$_lang['source_access_update'] = 'Edit Access';
$_lang['source_description_desc'] = 'Deskripsi singkat dari sumber Media.';
$_lang['source_err_ae_name'] = 'Sumber Media dengan nama itu sudah ada! Silakan tentukan nama baru.';
$_lang['source_err_nf'] = 'Media sumber tidak ditemukan!';
$_lang['source_err_init'] = 'Could not initialize "[[+source]]" Media Source!';
$_lang['source_err_nfs'] = 'Ada sumber Media dapat ditemukan dengan id: [[+id]].';
$_lang['source_err_ns'] = 'Silakan tentukan sumber Media.';
$_lang['source_err_ns_name'] = 'Silakan tentukan nama untuk sumber Media.';
$_lang['source_name_desc'] = 'Nama sumber Media.';
$_lang['source_properties.intro_msg'] = 'Mengelola properti sumber ini di bawah ini.';
$_lang['source_remove_confirm'] = 'Are you sure you want to delete this Media Source? This might break any TVs you have assigned to this source.';
$_lang['source_remove_multiple_confirm'] = 'Apakah Anda yakin Anda ingin menghapus sumber Media ini? Ini mungkin menghentikan setiap TV yang telah ditetapkan untuk sumber ini.';
$_lang['source_type'] = 'Jenis Sumber';
$_lang['source_type_desc'] = 'Jenisnya atau driver dari sumber Media. Sumber akan menggunakan driver ini untuk menyambungkan ketika mengumpulkan data. Sebagai contoh: sistem berkas akan mengambil file dari sistem file. S3 akan mendapatkan file dari S3 ember.';
$_lang['source_type.file'] = 'Sistem berkas';
$_lang['source_type.file_desc'] = 'Sumber berbasis filesystem yang menavigasi file server Anda.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Menavigasi Amazon S3.';
$_lang['source_type.ftp'] = 'File Transfer Protocol';
$_lang['source_type.ftp_desc'] = 'Navigates an FTP remote server.';
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
$_lang['prop_file.basePath_desc'] = 'The file path to point the Source to, for example: assets/images/<br>The path may depend on the "basePathRelative" parameter';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'Jika pengaturan dasar jalan di atas tidak relatif jalur menginstal MODX, pengaturan No.';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'The URL that this source can be accessed from, for example: assets/images/<br>The path may depend on the "baseUrlRelative" parameter';
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
$_lang['prop_file.visibility_desc'] = 'Default visibility for new files and folders.';
$_lang['no_move_folder'] = 'The Media Source driver does not support moving of folders at this time.';

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
$_lang['prop_s3.endpoint_desc'] = 'Alternative S3-compatible endpoint URL, e.g., "https://s3.<region>.example.com". Review your S3-compatible provider’s documentation for the endpoint location. Leave empty for Amazon S3';
$_lang['prop_s3.region_desc'] = 'Daerah ember. Contoh: kami-barat-1';
$_lang['prop_s3.prefix_desc'] = 'Optional path/folder prefix';
$_lang['s3_no_move_folder'] = 'Pemindahan folder saat ini tidak didukung oleh S3 driver.';

/* ftp source type */
$_lang['prop_ftp.host_desc'] = 'Server hostname or IP address';
$_lang['prop_ftp.username_desc'] = 'Username for authentication. Can be "anonymous".';
$_lang['prop_ftp.password_desc'] = 'Password of user. Leave empty for anonymous user.';
$_lang['prop_ftp.url_desc'] = 'If this FTP is has a public URL, you can enter its public http-address here. This will also enable image previews in the media browser.';
$_lang['prop_ftp.port_desc'] = 'Port of the server, default is 21.';
$_lang['prop_ftp.root_desc'] = 'The root folder, it will be opened after connection';
$_lang['prop_ftp.passive_desc'] = 'Enable or disable passive ftp mode';
$_lang['prop_ftp.ssl_desc'] = 'Enable or disable ssl connection';
$_lang['prop_ftp.timeout_desc'] = 'Timeout for connection in seconds.';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';
