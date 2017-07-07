<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'ตรวจสอบว่า <span class="mono">[[+file]]</span> มีอยู่จริงและสามารถเขียนได้: ';
$_lang['test_config_file_nw'] = 'สำหรับการติดตั้งใหม่ใน ลีนุกซ์/ยูนิกซ์ กรุณาสร้างไฟล์เปล่าชื่อว่า <span class="mono">[[+key]].inc.php</span> ในไดเรกทอรี core <span class="mono">config/</span> ใน MODX ของคุณแล้วตั้งค่าสิทธิ์การใช้งานให้เป็นสามารถเขียนได้';
$_lang['test_db_check'] = 'การเชื่อมต่อกับฐานข้อมูล: ';
$_lang['test_db_check_conn'] = 'ตรวจสอบรายละเอียดการเชื่อมต่อแล้วลองอีกครั้ง';
$_lang['test_db_failed'] = 'การติดต่อกับฐานข้อมูลล้มเหลว!';
$_lang['test_db_setup_create'] = 'การติดตั้งจะทดลองสร้างฐานข้อมูล';
$_lang['test_dependencies'] = 'ตรวจสอบ PHP เพื่อหาส่วนขยาย zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'ในการติดตั้ง PHP ของคุณไม่มีส่วนขยาย "zlib" ติดตั้งอยู่ ส่วนขยายนี้จำเป็นต่อการเรียกใช้งาน MODX กรุณาเปิดการใช้งานมันเพื่อดำเนินการต่อ';
$_lang['test_directory_exists'] = 'ตรวจสอบว่าไดเรกทอรี <span class="mono">[[+dir]]</span> มีอยู่จริง: ';
$_lang['test_directory_writable'] = 'ตรวจสอบว่าไดเรกทอรี <span class="mono">[[+dir]]</span> สามารถเขียนได้: ';
$_lang['test_memory_limit'] = 'ตรวจสอบการจำกัดหน่วยความจำอย่างน้อย 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX พบว่าการตั้งค่า memory_limit ไว้ที่ [[+memory]] ซึ่งน้อยกว่าขั้นแนะนำคือ 24M ดังนั้น MODX จึงพยายามที่จะตั้ง memory_limit ให้เป็น 24M แต่ไม่ประสบความสำเร็จ กรุณาตั้งค่า memory_limit ในไฟล์ php.ini เป็น 24M หรือสูงกว่าเพื่อดำเนินการต่อ';
$_lang['test_memory_limit_success'] = 'เรียบร้อย! ตั้งเป็น [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX มีปัญหากับ MySQL เวอร์ชัน ([[+version]]) ซึ่งเกิดจากข้อผิดพลาดมากมายที่เกี่ยวข้องกับการทำงานของไดรเวอร์ PDO บนเวอร์ชันนี้ กรุณาอัปเกรด MySQL เพื่อแก้ปัญหาเหล่านี้ ถึงแม้ว่าคุณเลือกที่จะไม่ใช้ MODX  เราก็ยังขอแนะนำให้คุณอัปเกรดเพื่อความปลอดภัยและความเสถียรของเว็บไซต์คุณเอง';
$_lang['test_mysql_version_client_nf'] = 'ไม่สามารถตรวจสอบเวอร์ชันของ MySQL client!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX ไม่สามารถตรวจสอบเวอร์ชันของ MySQL client ผ่านทาง mysql_get_client_info() ได้ กรุณาตรวจสอบเพื่อความมั่นใจเองว่าเวอร์ชัน MySQL client ของคุณนั้นอย่างน้อยเป็นเวอร์ชัน 4.1.20 ก่อนที่จะทำการติดตั้งต่อ';
$_lang['test_mysql_version_client_old'] = 'MODX อาจมีปัญหาเพราะว่าคุณกำลังใช้งาน MySQL client ที่เก่ามาก คือเวอร์ชัน ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX จะยอมให้ทำการติดตั้งบน MySQL client เวอร์ชันนี้ได้ แต่เราจะไม่การันตีว่าฟังก์ชันการทำงานทั้งหมดจะทำงานได้อย่างถูกต้องเมื่อใช้งานบนไลบารีของ MySQL client ที่เก่ามากๆ นี้';
$_lang['test_mysql_version_client_start'] = 'กำลังตรวจสอบเวอร์ชันของ MySQL client:';
$_lang['test_mysql_version_fail'] = 'คุณกำลังใช้งาน MySQL เวอร์ชัน [[+version]] และ MODX Revolution ต้องการ MySQL เวอร์ชัน 4.1.20 หรือมากกว่า กรุณาอัปเกรด MySQL ให้เป็นอย่างน้อยเวอร์ชัน 4.1.20';
$_lang['test_mysql_version_server_nf'] = 'ไม่สามารถตรวจสอบเวอร์ชันของ MySQL server!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX ไม่สามารถตรวจสอบเวอร์ชันของ MySQL server ผ่านทาง mysql_get_server_info() ได้ กรุณาตรวจสอบเพื่อความมั่นใจเองว่าเวอร์ชัน MySQL server ของคุณนั้นอย่างน้อยเป็นเวอร์ชัน 4.1.2 ก่อนที่จะทำการติดตั้งต่อ';
$_lang['test_mysql_version_server_start'] = 'กำลังตรวจสอบเวอร์ชันของ MySQL server:';
$_lang['test_mysql_version_success'] = 'เรียบร้อย! กำลังทำงานบนเวอร์ชัน: [[+version]]';
$_lang['test_nocompress'] = 'Checking if we should disable CSS/JS compression: ';
$_lang['test_nocompress_disabled'] = 'OK! Disabled.';
$_lang['test_nocompress_skip'] = 'Not selected, skipping test.';
$_lang['test_php_version_fail'] = 'คุณกำลังใช้ PHP เวอร์ชัน [[+version]] แต่ MODX Revolution ต้องการ PHP 5.1.1 หรือสูงกว่า กรุณาอัปเกรด PHP ให้อย่างน้อยเป็นเวอร์ชัน 5.1.1 เราแนะนำเวอร์ชัน 5.3.2+';
$_lang['test_php_version_start'] = 'ตรวจสอบเวอร์ชัน PHP:';
$_lang['test_php_version_success'] = 'เรียบร้อย! กำลังทำงานบนเวอร์ชัน: [[+version]]';
$_lang['test_safe_mode_start'] = 'กำลังตรวจสอบว่า safe_mode ถูกปิดไว้:';
$_lang['test_safe_mode_fail'] = 'MODX ตรวจพบว่า safe_mode ถูกเปิดอยู่ คุณต้องปิดการทำงานของ safe_mode ในการตั้งค่า PHP เพื่อดำเนินการต่อ';
$_lang['test_sessions_start'] = 'กำลังตรวจสอบเซสซันว่าถูกตั้งค่าอย่างเหมาะสม:';
$_lang['test_simplexml'] = 'ตรวจสอบ SimpleXML:';
$_lang['test_simplexml_nf'] = 'ไม่พบ SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX ไม่พบ SimpleXML บนสภาพแวดล้อมของ PHP การจัดการแพคเกจและฟังก์ชันอื่นๆ จะไม่ทำงาน ซึ่งคุณสามารถดำเนินการติดตั้งต่อไปได้ แต่ขอแนะนำให้เปิดการใช้ SimpleXML เพื่อคุณสมบัติและฟังก์ชันการทำงานขั้นสูง';
$_lang['test_suhosin'] = 'กำลังตรวจสอบปัญหาของ suhosin:';
$_lang['test_suhosin_max_length'] = 'Suhosin GET max value มีค่าต่ำมาก!';
$_lang['test_suhosin_max_length_err'] = 'ในตอนนี้ คุณกำลังใช้ตัวขยาย PHP suhosin และ suhosin.get.max_value_length ถูกตั้งไว้เป็นค่าที่ต่ำมากสำหรับ MODX เพื่อที่จะทำการบีบอัดไฟล์ JS ในส่วนของผู้จัดการระบบได้อย่างถูกต้อง MODX แนะนำให้เพิ่มค่านี้เป็น 4096 ไม่เช่นนั้น MODX ตั้งค่าการบีบอัดไฟล์ JS (compress_js setting) เป็น 0 โดยอัตโนมัติเพื่อป้องกันการเกิดข้อผิดพลาด';
$_lang['test_table_prefix'] = 'การตรวจสอบคำนำหน้าตาราง `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'คำนำหน้าตารางนี้มีและใช้ในฐานข้อมูลอยู่แล้ว!';
$_lang['test_table_prefix_inuse_desc'] = 'ไม่สามารถติดตั้งลงในฐานข้อมูลที่เลือกได้  อาจเป็นเพราะว่ามีตารางที่มีคำนำหน้าตารางตามที่คุณระบุอยู่แล้ว กรุณาแก้ไขคำนำหน้าตารางและทำการติดตั้งใหม่อีกครั้ง';
$_lang['test_table_prefix_nf'] = 'คำนำหน้าตารางไม่มีในฐานข้อมูลนี้!';
$_lang['test_table_prefix_nf_desc'] = 'ไม่สามารถติดตั้งลงในฐานข้อมูลที่เลือกได้  อาจเป็นเพราะว่าไม่มีตารางที่มีคำนำหน้าเหมือนกับที่คุณระบุเพื่อจะทำการอัปเกรด กรุณาเลือกคำนำหน้าตารางที่มีอยู่จริงและทำการติดตั้งใหม่อีกครั้ง';
$_lang['test_zip_memory_limit'] = 'ตรวจสอบการจำกัดหน่วยความจำอย่างน้อย 24M สำหรับไฟล์นามสกุล zip: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX พบว่าการตั้งค่า memory_limit ต่ำกว่าขั้นแนะนำคือ 24M ดังนั้น MODX จึงพยายามที่จะตั้ง memory_limit ให้เป็น 24M แต่ไม่ประสบความสำเร็จ กรุณาตั้ง memory_limit ในไฟล์ php.ini เป็น 24M หรือสูงกว่าเพื่อดำเนินการต่อ เผื่อว่าไฟล์นามสกุล zip จะสามารถทำงานได้อย่างถูกต้อง';