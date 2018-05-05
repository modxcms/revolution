<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Peristiwa';
$_lang['system_event'] = 'System Event';
$_lang['system_events'] = 'Sistem kegiatan';
$_lang['system_events.desc'] = 'System Event adalah event yang digunakan oleh plugin MODX. Mereka "dijalankan" di dalam MODX yang memungkinkan plugin untuk berinteraksi dengan MODX dan menambahkan fungsionalitas tambahan pada MODX tanpa harus melakukan hacking atau perubahan pada program core MODx. Anda juga dapat menambahkan event tertentu untuk Anda pergunakan secara spesifik. Event bawaan MODX tidak dapat dihapus, kecuali event yang Anda tambahkan sendiri.';
$_lang['system_events.search_by_name'] = 'Cari berdasarkan nama event';
$_lang['system_events.create'] = 'Buat Event Baru';
$_lang['system_events.name_desc'] = 'Nama event. Anda dapat memanggilnya dengan perintah &dollar;modx->invokeEvent(name, properties).';
$_lang['system_events.groupname'] = 'Kelompok';
$_lang['system_events.groupname_desc'] = 'Nama grup untuk event yang baru dibuat. Pilih grup yang sudah ada atau tambahkan grup baru.';
$_lang['system_events.plugins'] = 'Plugin';
$_lang['system_events.plugins_desc'] = 'Daftar plugin yang menyertai acara. Angkat plugin yang harus dilampirkan ke acara.';

$_lang['system_events.service'] = 'Service';
$_lang['system_events.service_1'] = 'Parser Service Events';
$_lang['system_events.service_2'] = 'Manager Access Events';
$_lang['system_events.service_3'] = 'Web Access Service Events';
$_lang['system_events.service_4'] = 'Cache Service Events';
$_lang['system_events.service_5'] = 'Template Service Events';
$_lang['system_events.service_6'] = 'User Defined Events';

$_lang['system_events.remove'] = 'Hapus Event';
$_lang['system_events.remove_confirm'] = 'Apakah Anda ingin menghapus event <b>[[+name]]</b>? Event yang sudah dihapus tidak dapat dikembalikan!';

$_lang['system_events_err_ns'] = 'Nama untuk System Event masih kosong.';
$_lang['system_events_err_ae'] = 'Nama untuk System Event sudah digunakan.';
$_lang['system_events_err_startint'] = 'Nama untuk System Event tidak boleh diawali dengan angka.';
$_lang['system_events_err_remove_not_allowed'] = 'Anda tidak diperkenankan menghapus System Event ini.';
