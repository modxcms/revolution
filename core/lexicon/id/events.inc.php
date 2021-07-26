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
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'Cari berdasarkan nama event';
$_lang['system_events.name_desc'] = 'Nama event. Anda dapat memanggilnya dengan perintah &dollar;modx->invokeEvent(name, properties).';
$_lang['system_events.groupname'] = 'Kelompok';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'Plugin';
$_lang['system_events.plugins_desc'] = 'Daftar plugin yang menyertai acara. Angkat plugin yang harus dilampirkan ke acara.';

$_lang['system_events.service'] = 'Service';
$_lang['system_events.service_1'] = 'Parser Service Events';
$_lang['system_events.service_2'] = 'Manager Access Events';
$_lang['system_events.service_3'] = 'Web Access Service Events';
$_lang['system_events.service_4'] = 'Cache Service Events';
$_lang['system_events.service_5'] = 'Template Service Events';
$_lang['system_events.service_6'] = 'User Defined Events';

$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Nama untuk System Event masih kosong.';
$_lang['system_events_err_ae'] = 'Nama untuk System Event sudah digunakan.';
$_lang['system_events_err_startint'] = 'Nama untuk System Event tidak boleh diawali dengan angka.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
