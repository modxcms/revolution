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
$_lang['system_events.desc'] = 'Sistem Olayları, Eklentilerin kayıtlı olduğu MODX\'teki olaylardır. Eklentilerin MODX kodu ile etkileşime girmesine ve çekirdek kodu hacklemeden özel işlevsellikler eklemesine izin vererek MODX kodu içinde çalıştırılırlar. Kendi özel projeleriniz için kendi olaylarınızı da burada oluşturabilirsiniz. Ana olayları kaldırmazsınız, sadece kendi olaylarınızı kaldırabilirsiniz.';
$_lang['system_events.search_by_name'] = 'Olay adına göre ara';
$_lang['system_events.create'] = 'Yeni Olay Oluştur';
$_lang['system_events.name_desc'] = 'Durumun adı. Bir &dollar;modx-> invokeEvent (isim, özellik) çağrısında hangisini kullanmalısınız.';
$_lang['system_events.groupname'] = 'Grup';
$_lang['system_events.groupname_desc'] = 'Yeni etkinliğin ait olduğu grubun adı. Var olanı seçin veya yeni bir grup adı yazın.';
$_lang['system_events.plugins'] = 'Eklentiler';
$_lang['system_events.plugins_desc'] = 'Etkinliğe eklenen eklentilerin listesi. Etkinliğe eklenmesi gereken eklentileri seçin.';

$_lang['system_events.service'] = 'Hizmet';
$_lang['system_events.service_1'] = 'Ayrıştırıcı Hizmet Olayları';
$_lang['system_events.service_2'] = 'Yönetici Erişim Olayları';
$_lang['system_events.service_3'] = 'Web Erişimi Hizmet Olayları';
$_lang['system_events.service_4'] = 'Önbellek Hizmet Etkinlikleri';
$_lang['system_events.service_5'] = 'Şablon Hizmeti Etkinlikleri';
$_lang['system_events.service_6'] = 'Kullanıcı Tanımlı Olaylar';

$_lang['system_events.remove'] = 'Etkinliği Kaldır';
$_lang['system_events.remove_confirm'] = '<b>[[+name]]</b> etkinliğini kaldırmak istediğinizden emin misiniz? Bu işlem geri döndürülemez!';

$_lang['system_events_err_ns'] = 'Sistem Olayının adı belirtilmedi.';
$_lang['system_events_err_ae'] = 'Sistem olayının adı zaten var.';
$_lang['system_events_err_startint'] = 'İsmin bir rakam ile başlatılması kabul edilemez.';
$_lang['system_events_err_remove_not_allowed'] = 'Bu sistem olayını kaldırmanıza izin verilmemektedir.';
