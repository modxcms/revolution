<?php
/**
 * Test-related Thai Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'ตรวจสอบว่า <span class="mono">[[+file]]</span> มีอยู่และสามารถเขียนได้: ';
$_lang['test_config_file_nw'] = 'สำหรับการติดตั้งใหม่ใน ลีนุกซ์/ยูนิกซ์ กรุณาสร้างไฟล์เปล่าชื่อว่า <span class="mono">[[+key]].inc.php</span> ในไดเรกทอรี core <span class="mono">config/</span> ใน MODX ของคุณแล้วตั้งค่าสิทธิ์การใช้งานเป็นสามารถเขียนได้';
$_lang['test_db_check'] = 'การเชื่อมต่อกับฐานข้อมูล: ';
$_lang['test_db_check_conn'] = 'ตรวจสอบรายละเอียดการเชื่อมต่อแล้วลองอีกครั้ง';
$_lang['test_db_failed'] = 'การติดต่อกับฐานข้อมูลล้มเหลว!';
$_lang['test_db_setup_create'] = 'การติดตั้งจะทดลองสร้างฐานข้อมูล';
$_lang['test_dependencies'] = 'ตรวจสอบ PHP เพื่อหาส่วนขยาย zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'ในการติดตั้ง PHP ของคุณไม่มีส่วนขยาย "zlib" ติดตั้งอยู่ ส่วนขยายนี้จำเป็นต่อการเรียกใช้งาน MODX กรุณาเปิดการใช้งานมันเพื่อดำเนินการต่อ';
$_lang['test_directory_exists'] = 'ตรวจสอบว่าไดเรกทอรี <span class="mono">[[+dir]]</span> มีอยู่จริง: ';
$_lang['test_directory_writable'] = 'ตรวจสอบว่าไดเรกทอรี <span class="mono">[[+dir]]</span> สามารถเขียนได้: ';
$_lang['test_memory_limit'] = 'ตรวจสอบการจำกัดหน่วยความจำอย่างน้อย 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX พบว่าการตั้งค่า memory_limit น้อยกว่าขั้นแนะนำคือ 24M. ดังนั้น MODX จึงพยายามที่จะตั้ง memory_limit ให้เป็น 24M แต่ไม่ประสบความสำเร็จ กรุณาตั้งค่า memory_limit ในไฟล์ php.ini เป็น 24M หรือสูงกว่าเพื่อดำเนินการต่อ';
$_lang['test_memory_limit_success'] = 'เรียบร้อย! ตั้งเป็น [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX มีปัญหากับ MySQL เวอร์ชัน ([[+version]]) ซึ่งเกิดจากข้อผิดพลาดมากมายที่เกี่ยวข้องกับการทำงานของไดรเวอร์ PDO บนเวอร์ชันนี้ กรุณาปรับปรุงรุ่น MySQL เพื่อแก้ปัญหาเหล่านี้ เว้นแต่ว่าคุณเลือกที่จะไม่ใช้ MODX  เราขอแนะนำให้คุณปรับปรุงรุ่นเพื่อความมั่นคงและความปลอดภัยของเว็บไซต์คุณเอง';
$_lang['test_mysql_version_client_nf'] = 'ตรวจไม่พบเวอร์ชันของ MySQL client!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX ไม่สามารถตรวจพบเวอร์ชันของ MySQL client ผ่านทาง mysql_get_client_info() กรุณาตรวจสอบด้วยตัวคุณเองว่าเวอร์ชันของ MySQL client อย่างน้อยต้องเป็นเวอร์ชัน 4.1.20 ก่อนที่จะดำเนินการต่อ';
$_lang['test_mysql_version_client_old'] = 'MODX อาจมีปัญหาเพราะว่าคุณกำลังใช้งาน MySQL client ที่เก่ามาก คือเวอร์ชัน ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX จะยอมให้ทำการติดตั้งบน MySQL client เวอร์ชันนี้ได้ แต่เราจะไม่สามารถยืนยันได้ว่าฟังก์ชันการทำงานทั้งหมดจะทำงานได้อย่างถูกต้องเมื่อใช้งานบนไลบารีของ MySQL client ที่เก่ามากๆ';
$_lang['test_mysql_version_client_start'] = 'ตรวจสอบเวอร์ชันของ MySQL client: ';
$_lang['test_mysql_version_fail'] = 'คุณกำลังใช้ MySQL เวอร์ชัน [[+version]] แต่ MODX Revolution ต้องการ MySQL 4.1.20 หรือสูงกว่า กรุณาปรับปรุงรุ่น MySQL อย่างน้อยเป็นเวอร์ชัน 4.1.20';
$_lang['test_mysql_version_server_nf'] = 'ตรวจไม่พบเวอร์ชันของ MySQL เซิร์ฟเวอร์!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX ไม่สามารถตรวจสอบเวอร์ชันของ MySQL เซิร์ฟเวอร์ผ่านทาง mysql_get_server_info() กรุณาตรวจสอบด้วยตัวคุณเองว่าเวอร์ชันของ MySQL เซิร์ฟเวอร์ อย่างน้อยต้องเป็นเวอร์ชัน 4.1.20 ก่อนที่จะดำเนินการต่อ';
$_lang['test_mysql_version_server_start'] = 'ตรวจสอบเวอร์ชันของ MySQL เซิร์ฟเวอร์: ';
$_lang['test_mysql_version_success'] = 'เรีบยร้อย! กำลังใช้งานบนเวอร์ชัน: [[+version]]';
$_lang['test_php_version_fail'] = 'คุณกำลังใช้ PHP เวอร์ชัน [[+version]] แต่ MODX Revolution ต้องการ PHP 5.1.1 หรือสูงกว่า กรุณาปรับปรุงรุ่น PHP อย่างน้อยเป็นเวอร์ชัน 5.1.1 เราแนะนำเวอร์ชัน 5.3.0';
$_lang['test_php_version_516'] = 'MODX มีปัญหากับ PHP เวอร์ชัน ([[+version]]) ซึ่งเกิดจากข้อผิดพลาดมากมายที่เกี่ยวข้องกับการทำงานของไดรเวอร์ PDO บนเวอร์ชันนี้ กรุณาปรับปรุงรุ่น PHP เป็นเวอร์ชัน 5.3.0 หรือสูงกว่า เราแนะนำเวอร์ชัน 5.3.2+ เพื่อแก้ปัญหาเหล่านี้ เว้นแต่ว่าคุณเลือกที่จะไม่ใช้ MODX  เราขอแนะนำให้คุณปรับปรุงรุ่นเพื่อความมั่นคงและความปลอดภัยของเว็บไซต์คุณเอง';
$_lang['test_php_version_520'] = 'MODX มีปัญหากับ PHP เวอร์ชัน ([[+version]]) ซึ่งเกิดจากข้อผิดพลาดมากมายที่เกี่ยวข้องกับการทำงานของไดรเวอร์ PDO บนเวอร์ชันนี้ กรุณาปรับปรุงรุ่น PHP เป็นเวอร์ชัน 5.3.0 หรือสูงกว่า เราแนะนำเวอร์ชัน 5.3.2+ เพื่อแก้ปัญหาเหล่านี้ เว้นแต่ว่าคุณเลือกที่จะไม่ใช้ MODX  เราขอแนะนำให้คุณปรับปรุงรุ่นเพื่อความมั่นคงและความปลอดภัยของเว็บไซต์คุณเอง';
$_lang['test_php_version_start'] = 'ตรวจสอบเวอร์ชันของ PHP: ';
$_lang['test_php_version_success'] = 'เรียบร้อย! กำลังใช้งานบนเวอร์ชัน: [[+version]]';
$_lang['test_safe_mode_start'] = 'Checking to make sure safe_mode is off:';
$_lang['test_safe_mode_fail'] = 'MODX has found safe_mode to be on. You must disable safe_mode in your PHP configuration to proceed.';
$_lang['test_sessions_start'] = 'ตรวจสอบการตั้งค่าวาระการเข้าใช้งาน:';
$_lang['test_simplexml'] = 'ตรวจสอบ SimpleXML:';
$_lang['test_simplexml_nf'] = 'ไม่พบ SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX ไม่พบ SimpleXML บนสภาพแวดล้อมของ PHP การจัดการแพคเกจและฟังก์ชันอื่นๆ จะไม่ทำงาน ซึ่งคุณสามารถดำเนินการติดตั้งต่อไปได้ แต่ขอแนะนำให้เปิดการใช้ SimpleXML เพื่อคุณสมบัติและฟังก์ชันการทำงานขั้นสูง';
$_lang['test_table_prefix'] = 'การตรวจสอบคำนำหน้าตาราง `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'คำนำหน้าตารางนี้มีและใช้ในฐานข้อมูลอยู่แล้ว!';
$_lang['test_table_prefix_inuse_desc'] = 'ไม่สามารถติดตั้งลงในฐานข้อมูลที่เลือกได้  อาจเป็นเพราะว่ามีตารางที่มีคำนำหน้าตารางตามที่คุณระบุอยู่แล้ว กรุณาแก้ไขคำนำหน้าตารางและทำการติดตั้งใหม่อีกครั้ง';
$_lang['test_table_prefix_nf'] = 'คำนำหน้าตารางไม่มีในฐานข้อมูลนี้!';
$_lang['test_table_prefix_nf_desc'] = 'ไม่สามารถติดตั้งลงในฐานข้อมูลที่เลือกได้  อาจเป็นเพราะว่าไม่มีตารางที่มีคำนำหน้าเหมือนกับที่คุณระบุเพื่อจะทำการปรับปรุงรุ่น กรุณาเลือกคำนำหน้าตารางที่มีอยู่จริงและทำการติดตั้งใหม่อีกครั้ง';
$_lang['test_zip_memory_limit'] = 'ตรวจสอบการจำกัดหน่วยความจำอย่างน้อย 24M สำหรับไฟล์นามสกุล zip: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX พบว่าการตั้งค่า memory_limit ต่ำกว่าขั้นแนะนำคือ 24M. ดังนั้น MODX จึงพยายามที่จะตั้ง memory_limit ให้เป็น 24M แต่ไม่ประสบความสำเร็จ กรุณาตั้ง memory_limit ในไฟล์ php.ini เป็น 24M หรือสูงกว่าเพื่อดำเนินการต่อ เผื่อว่าไฟล์นามสกุล zip จะสามารถทำงานได้อย่างถูกต้อง';