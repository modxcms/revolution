<?php
/**
 * Thai Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 * by Mr.Kittipong Intaboot COE#18,KKU (@kittipongint)
 * updated 30/01/2012
 */
$_lang['mysql_err_ext'] = 'MODX ต้องการตัวขยาย mysql สำหรับ PHP และมันก็ไม่ปรากฏว่าถูกเรียกขึ้นมาทำงานแล้ว';
$_lang['mysql_err_pdo'] = 'MODX ต้องการไดรเวอร์ pdo_mysql เมื่อ native PDO กำลังถูกใช้งาน และมันก็ไม่ปรากฏว่าถูกเรียกขึ้นมาทำงานแล้ว';
$_lang['mysql_version_5051'] = 'MODX จะมีปัญหาบน MySQL เวอร์ชัน ([[+version]]) ของคุณ เพราะว่ามีบั๊กที่เกี่ยวกับไดวเวอร์ PDO ในเวอร์ชันนี้ กรุณาอัปเกรด MySQL เพื่อแก้ไขปัญหาเหล่านี้ ถึงแม้ว่าคุณเลือกที่จะไม่ใช้งาน MODX เราก็ยังแนะนำให้คุณอัปเกรดเพื่อความปลอดภัยและความเสถียรของเว็บไซต์คุณเอง';
$_lang['mysql_version_client_nf'] = 'MODX ไม่สามารถตรวจสอบเวอร์ชันของ MySQL client ผ่านทาง mysql_get_client_info() ได้ กรุณาตรวจสอบเพื่อความมั่นใจเองว่าเวอร์ชัน MySQL client ของคุณนั้นอย่างน้อยเป็นเวอร์ชัน 4.1.20 ก่อนที่จะทำการติดตั้งต่อ';
$_lang['mysql_version_client_start'] = 'กำลังตรวจสอบเวอร์ชันของ MySQL client:';
$_lang['mysql_version_client_old'] = 'MODX อาจมีปัญหาเพราะว่าคุณกำลังใช้งาน MySQL client เวอร์ชัน ([[+version]]) ซึ่งมันเก่ามากแล้ว MODX จะยอมให้ติดตั้งโดยใช้ MySQL client เวอร์ชันนี้ได้ แต่เราจะไม่การันตีว่าจะมีครบทุกฟังก์ชันหรือทำงานได้อย่างสมบูรณ์';
$_lang['mysql_version_fail'] = 'คุณกำลังใช้งาน MySQL เวอร์ชัน [[+version]] และ MODX Revolution ต้องการ MySQL เวอร์ชัน 4.1.20 หรือมากกว่า กรุณาอัปเกรด MySQL ให้เป็นอย่างน้อยเวอร์ชัน 4.1.20';
$_lang['mysql_version_server_nf'] = 'MODX ไม่สามารถตรวจสอบเวอร์ชันของ MySQL server ผ่านทาง mysql_get_server_info() ได้ กรุณาตรวจสอบเพื่อความมั่นใจเองว่าเวอร์ชัน MySQL server ของคุณนั้นอย่างน้อยเป็นเวอร์ชัน 4.1.2 ก่อนที่จะทำการติดตั้งต่อ';
$_lang['mysql_version_server_start'] = 'กำลังตรวจสอบเวอร์ชันของ MySQL server:';
$_lang['mysql_version_success'] = 'เรียบร้อย! กำลังทำงานบนเวอร์ชัน: [[+version]]';

$_lang['sqlsrv_version_success'] = 'เรียบร้อย!';
$_lang['sqlsrv_version_client_success'] = 'เรียบร้อย!';