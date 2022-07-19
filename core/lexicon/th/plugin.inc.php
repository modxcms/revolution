<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'เหตุการณ์';
$_lang['events'] = 'เหตุการณ์';
$_lang['plugin'] = 'ปลั๊กอิน';
$_lang['plugin_add'] = 'เพิ่มปลั๊กอิน';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'การตั้งค่าปลั๊กอิน';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'คุณแน่ใจว่าต้องการลบปลั๊กอินนี้หรือไม่?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'คุณแน่ใจว่าต้องการทำสำเนาปลั๊กอินนี้หรือไม่?';
$_lang['plugin_err_create'] = 'เกิดข้อผิดพลาดขณะกำลังสร้างปลั๊กอิน';
$_lang['plugin_err_ae'] = 'มีปลั๊กอินชื่อ "[[+name]]" อยู่แล้ว';
$_lang['plugin_err_invalid_name'] = 'ชื่อปลั๊กอินนี้ใช้ไม่ได้';
$_lang['plugin_err_duplicate'] = 'An error occurred while trying to duplicate the plugin.';
$_lang['plugin_err_nf'] = 'ไม่พบปลั๊กอิน!';
$_lang['plugin_err_ns'] = 'ไม่มีการระบุปลั๊กอิน';
$_lang['plugin_err_ns_name'] = 'กรุณาระบุชื่อปลั๊กอิน';
$_lang['plugin_err_remove'] = 'An error occurred while trying to delete the plugin.';
$_lang['plugin_err_save'] = 'เกิดข้อผิดพลาดขณะกำลังบันทึกปลั๊กอิน';
$_lang['plugin_event_err_duplicate'] = 'เกิดข้อผิดพลาดขณะที่พยายามทำสำเนาเหตุการณ์ของปลั๊กอิน';
$_lang['plugin_event_err_nf'] = 'ไม่พบเหตุการณ์ของปลั๊กอิน';
$_lang['plugin_event_err_ns'] = 'ไม่ระบุเหตุการณ์ของปลั๊กอิน';
$_lang['plugin_event_err_remove'] = 'An error occurred while trying to delete the plugin event.';
$_lang['plugin_event_err_save'] = 'เกิดข้อผิดพลาดขณะกำลังบันทึกเหตุการณ์ของปลั๊กอิน';
$_lang['plugin_event_msg'] = 'เลือกเหตุการณ์ที่คุณต้องการให้ปลั๊กอินนี้เกิดการทำงาน';
$_lang['plugin_event_plugin_remove_confirm'] = 'Are you sure you want to delete this plugin from this event?';
$_lang['plugin_lock'] = 'Plugin locked for editing';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'ปลั๊กอินนี้ถูกป้องกัน';
$_lang['plugin_management_msg'] = 'คุณสามารถเลือกปลั๊กอินที่คุณต้องการแก้ไข';
$_lang['plugin_name_desc'] = 'ชื่อของปลั๊กอินนี้';
$_lang['plugin_new'] = 'Create Plugin';
$_lang['plugin_priority'] = 'แก้ไขปลั๊กอินโดยจัดการเรียงตามเหตุการณ์';
$_lang['plugin_properties'] = 'คุณสมบัติปลั๊กอิน';
$_lang['plugin_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Plugin</em> as well as its content. The content must be PHP, either placed in the <em>Plugin Code</em> field below or in a static external file. The PHP code entered runs in response to one or more MODX System Events that you specify.';
$_lang['plugins'] = 'ปลั๊กอิน';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];
