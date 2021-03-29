<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Olaylar';
$_lang['system_event'] = 'Sistem Olayı';
$_lang['system_events'] = 'Sistem Etkinlikleri';
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'Olay adına göre ara';
$_lang['system_events.name_desc'] = 'Durumun adı. Bir &dollar;modx-> invokeEvent (isim, özellik) çağrısında hangisini kullanmalısınız.';
$_lang['system_events.groupname'] = 'Grup';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'Eklentiler';
$_lang['system_events.plugins_desc'] = 'Etkinliğe eklenen eklentilerin listesi. Etkinliğe eklenmesi gereken eklentileri seçin.';

$_lang['system_events.service'] = 'Hizmet';
$_lang['system_events.service_1'] = 'Ayrıştırıcı Hizmet Olayları';
$_lang['system_events.service_2'] = 'Yönetici Erişim Olayları';
$_lang['system_events.service_3'] = 'Web Erişimi Hizmet Olayları';
$_lang['system_events.service_4'] = 'Önbellek Hizmet Etkinlikleri';
$_lang['system_events.service_5'] = 'Şablon Hizmeti Etkinlikleri';
$_lang['system_events.service_6'] = 'Kullanıcı Tanımlı Olaylar';

$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Sistem Olayının adı belirtilmedi.';
$_lang['system_events_err_ae'] = 'Sistem olayının adı zaten var.';
$_lang['system_events_err_startint'] = 'İsmin bir rakam ile başlatılması kabul edilemez.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
