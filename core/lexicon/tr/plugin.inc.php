<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Olay';
$_lang['events'] = 'Olaylar';
$_lang['plugin'] = 'Eklenti';
$_lang['plugin_add'] = 'Eklenti ekle';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Eklenti Yapılandırması';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'Bu eklentiyi silmek istediğinizden emin misiniz?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'Bu eklentiyi çoğaltmak istediğinizden emin misiniz?';
$_lang['plugin_err_create'] = 'Eklenti oluşturulurken hata meydana geldi.';
$_lang['plugin_err_ae'] = '[[+name]] isminde bir eklenti zaten mevcut.';
$_lang['plugin_err_invalid_name'] = 'Eklenti adı geçersiz.';
$_lang['plugin_err_duplicate'] = 'İçeriği yineleme sırasında bir hata meydana geldi.';
$_lang['plugin_err_nf'] = 'Eklenti bulunamadı!';
$_lang['plugin_err_ns'] = 'Eklenti belirtilmedi.';
$_lang['plugin_err_ns_name'] = 'Lütfen bu eklenti için bir ad belirtin.';
$_lang['plugin_err_remove'] = 'An error occurred while trying to delete the plugin.';
$_lang['plugin_err_save'] = 'Eklenti kaydedilirken bir hata meydana geldi.';
$_lang['plugin_event_err_duplicate'] = 'Eklentileri yenileme sırasında bir hata meydana geldi';
$_lang['plugin_event_err_nf'] = 'Eklenti bulunamadı.';
$_lang['plugin_event_err_ns'] = 'Eklenti belirtilmedi.';
$_lang['plugin_event_err_remove'] = 'An error occurred while trying to delete the plugin event.';
$_lang['plugin_event_err_save'] = 'Eklenti kaydedilirken bir hata meydan geldi.';
$_lang['plugin_event_msg'] = 'Bu olaylardan dinlemek istediğiniz etlentileri seçin.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Are you sure you want to delete this plugin from this event?';
$_lang['plugin_lock'] = 'Eklenti düzenleme için kilitlendi';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'Bu eklenti kilitli.';
$_lang['plugin_management_msg'] = 'Burada hangi eklentiyi düzenlemek istediğinizi seçebilirsiniz.';
$_lang['plugin_name_desc'] = 'Bu eklentinin adı.';
$_lang['plugin_new'] = 'Create Plugin';
$_lang['plugin_priority'] = 'Etkinliğe göre eklenti yürütme sırasını belirleyin';
$_lang['plugin_properties'] = 'Eklenti Özellikleri';
$_lang['plugin_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Plugin</em> as well as its content. The content must be PHP, either placed in the <em>Plugin Code</em> field below or in a static external file. The PHP code entered runs in response to one or more MODX System Events that you specify.';
$_lang['plugins'] = 'Eklentiler';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];
