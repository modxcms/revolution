<?php
/**
 * Thai Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 * by Mr.Kittipong Intaboot COE#18,KKU (@kittipongint)
 * updated 30/01/2012
 */
$_lang['add_column'] = 'เพิ่มใหม่ `[[+column]]` คอลัมน์เป็น `[[+table]]`';
$_lang['add_index'] = 'เพิ่มดัชนีใหม่ใน `[[+index]]` สำหรับตาราง `[[+table]]`';
$_lang['add_moduser_classkey'] = 'เพิ่มฟิลด์ class_key เพื่อรองรับตรา สารอนุพันธ์ของ modUser';
$_lang['added_cachepwd'] = 'เพิ่มฟิลด์ cachepwd ที่ไม่มีในรุ่น Revolution ก่อนหน้านี้';
$_lang['added_content_ft_idx'] = 'เพิ่ม `content_ft_idx` ดัชนีข้อความเต็มบนฟิลด์ `pagetitle`, `longtitle`, `description`, `introtext`, `content`';
$_lang['allow_null_properties'] = 'แก้ไขให้ยินยอมใช้ค่า NULL ไปใน `[[+class]]`.`properties`';
$_lang['alter_activeuser_action'] = 'ดัดแปลงฟิลด์ modActiveUser `action` เพื่อให้ใช้ชื่อแอ็กชันแบบยาวๆได้';
$_lang['alter_usermessage_messageread'] = 'เปลี่ยนฟิลด์ modUserMessage `messageread` เป็น `read`';
$_lang['alter_usermessage_postdate'] = 'เปลี่ยนฟิลด์ modUserMessage `postdate` จาก INT เป็น DATETIME และชื่อว่า `date_sent`';
$_lang['alter_usermessage_subject'] = 'เปลี่ยนฟิลด์ modUserMessage `subject` จาก VARCHAR(60) เป็น VARCHAR(255)';
$_lang['change_column'] = 'เปลี่ยนฟิลด์ `[[+old]]` เป็น `[[+new]]` ในตาราง `[[+table]]`';
$_lang['change_default_value'] = 'เปลี่ยนค่าปริยายของคอลัมน์ `[[+column]]` เป็น `[[+value]]` ในตาราง `[[+table]]`';
$_lang['connector_acls_removed'] = 'ลบ ACLs ของตัวเชื่อมต่อบริบทออก';
$_lang['connector_acls_not_removed'] = 'ไม่สามารถลบ  ACLs ของตัวเชื่อมต่อบริบท';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'ไม่สามารถลบตัวเชื่อมต่อบริบท';
$_lang['data_remove_error'] = 'เกิดข้อผิดพลาดในการลบข้อมูลของคลาส `[[+class]]`';
$_lang['data_remove_success'] = 'ลบข้อมูลออกจากตารางของคลาส `[[+class]]` สำเร็จ';
$_lang['drop_column'] = 'ลดคอลัมน์ `[[+column]]` ในตาราง `[[+table]]` ลง';
$_lang['drop_index'] = 'ลดดัชนี `[[+index]]` ในตาราง `[[+table]]` ลง';
$_lang['lexiconentry_createdon_null'] = 'เปลี่ยนฟิลด์ modLexiconEntry `createdon` ให้รับค่า null ได้';
$_lang['lexiconentry_focus_alter'] = 'เปลี่ยนฟิลด์ modLexiconEntry `focus` จาก VARCHAR(100) เป็น INT(10)';
$_lang['lexiconentry_focus_alter_int'] = 'อัปเดตคอลัมน์ข้อมูล modLexiconEntry `focus` จากส string เป็น int จากคีย์ของ modLexiconTopic';
$_lang['lexiconfocus_add_id'] = 'เพิ่ม modLexiconFocus คอลัมน์ `id`';
$_lang['lexiconfocus_add_pk'] = 'เพิ่ม modLexiconFocus PRIMARY KEY ใส่คอลัมน์ `id`';
$_lang['lexiconfocus_alter_pk'] = 'เปลี่ยน modLexiconFocus `name` จาก PRIMARY KEY เป็น UNIQUE KEY';
$_lang['lexiconfocus_drop_pk'] = 'ลด modLexiconFocus PRIMARY KEY';
$_lang['modify_column'] = 'เปลี่ยนคอลัมน์ `[[+column]]` จาก `[[+old]]` เป็น `[[+new]]` ในตาราง `[[+table]]`';
$_lang['rename_column'] = 'เปลี่ยนชื่อคอลัมน์ `[[+old]]` เป็น `[[+new]]` บนตาราง `[[+table]]`';
$_lang['rename_table'] = 'เปลี่ยนชื่อตาราง `[[+old]]` เป็น `[[+new]]`';
$_lang['remove_fulltext_index'] = 'ลบดัชนีข้อความเต็ม `[[+index]]`';
$_lang['systemsetting_xtype_fix'] = 'แก้ไข xtypes ใน modSystemSettings สำเร็จ';
$_lang['transportpackage_manifest_text'] = 'ดัดแปลงคอลัมน์ `manifest` เป็น TEXT จากเดิม MEDIUMTEXT ใน `[[+class]]`';
$_lang['update_closure_table'] = 'อัปเดตข้อมูลการปิดท้ายตารางสำหรับคลาส `[[+class]]`';
$_lang['update_table_column_data'] = 'อัปเดตข้อมูลในคอลัมน์ [[+column]] ของตาราง [[+table]] ( [[+class]] )';