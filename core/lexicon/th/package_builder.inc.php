<?php
/**
 * Package Builder Thai lexicon topic
 *
 * @language th
 * @package modx
 * @subpackage lexicon
 
  * @author Mr.Kittipong Intaboot COE#18,KKU
 * @updated 2010-07-21
 */
$_lang['as_system_settings'] = 'ตั้งค่าระบบ';
$_lang['as_context_settings'] = 'ตั้งค่าบริบท';
$_lang['as_lexicon_entries'] = 'Lexicon เอนทรี';
$_lang['as_lexicon_topics'] = 'หัวข้อ Lexicon';
$_lang['build'] = 'Build';
$_lang['class_key'] = 'คลาสคีย์';
$_lang['class_key_desc'] = 'ประเภทของอ็อบเจคที่คุณต้องการเชื่อมโยงกับแผนที่ไปยัง vehicle.';
$_lang['class_key_custom'] = 'Or Custom Class';
$_lang['class_key_custom_desc'] = 'คุณสามารถระบุชื่อคลาส custom xPDOObject ที่ไม่อยู่ในรายการข้างล่างนี้';
$_lang['file'] = 'ไฟล์';
$_lang['index'] = 'ดัชนี';
$_lang['object'] = 'อ็อบเจค';
$_lang['object_id'] = 'อ็อบเจคไอดี';
$_lang['object_id_desc'] = 'The exact object ที่คุณต้องการ map. Required.';
$_lang['package_autoselects'] = 'Package Auto-Includes';
$_lang['package_autoselects_desc'] = 'กรุณาเลือกรีซอร์สที่คุณต้องการให้รวมใน Package Builder โดยอัตโนมัติ หมายเหตุ: ถ้า building จาก core แนะนำว่าไม่ควรเช็คตัวเลือกใดๆ ในนี้';
$_lang['package_build'] = 'Build the Package';
$_lang['package_build_desc'] = 'ตอนนี้คุณพร้อม build the package มันจะถูกเก็บไว้ที่โฟลเดอร์ core/packages';
$_lang['package_build_err'] = 'เกิดข้อผิดพลาดขณะที่พยายาม build the package.';
$_lang['package_build_xml'] = 'Build Package จาก XML';
$_lang['package_build_xml_desc'] = 'กรุณาเลือกไฟล์ valid build XML สำหรับส่วนประกอบของคุณ';
$_lang['package_builder'] = 'Package Builder';
$_lang['package_built'] = 'The package has been built.';
$_lang['package_info'] = 'ข้อมูลแพคเกจ';
$_lang['package_info_desc'] = 'ขั้นแรกระบุข้อมูลแพคเกจ เช่น เวอร์ชันและการปล่อย';
$_lang['package_method'] = 'g]nvd;bเลือกวิธีการ Packaging';
$_lang['package_method_desc'] = 'กรุณาเลือกวิธีาการ package building ที่คุณต้องการเลือกใช้';
$_lang['php_script'] = 'PHP Script';
$_lang['preserve_keys'] = 'Preserve Keys';
$_lang['preserve_keys_desc'] = 'นี่จะเก็บ primary keys เป็นค่าที่พวกเขาเป็นอยู่ในฐานข้อมูลในขณะนี้';
$_lang['release'] = 'ปล่อย';
$_lang['resolve_files'] = 'Resolve Files';
$_lang['resolve_files_desc'] = 'เมื่อเช็คถูก จะแก้ไฟล์ที่ระบุใน resolvers';
$_lang['resolve_php'] = 'Resolve PHP Scripts';
$_lang['resolve_php_desc'] = 'เมื่อเช็คถูก จะแก้ PHP scripts ที่ระบุใน resolvers';
$_lang['resolver_add'] = 'เพิ่ม Resolver';
$_lang['resolver_create'] = 'สร้าง Resolver';
$_lang['resolver_name_desc'] = 'ชื่อของ resolver ใช้สำหรับจุดประสงค์ในการจัดเก็บ';
$_lang['resolver_remove'] = 'ลบ Resolver';
$_lang['resolver_remove_confirm'] = 'คุณแน่ใจว่าต้องการลบ resolver นี้?';
$_lang['resolver_source_desc'] = 'เส้นทางที่แน่นอนของซอร์สของ resolver ถ้าเป็น file resolver เลือกโฟลเดอร์ของไฟล์ที่คุณต้องการคัดลอก ถ้าเป็น PHP Script ให้ระบุสคริปต์ ตัวอย่าง: <br /><br />/public_html/modx/_build/components/demo/';
$_lang['resolver_target_desc'] = 'เส้นทางเป้าหมายที่แน่นอนสำหรับสถานที่ที่ resolver จะเก็บไฟล์หรือแอ็กชันไว้ ตามปิกติแล้วคุณไม่ควรเปลี่ยนมัน ตัวอย่าง: <br /><br />return MODX_ASSETS_PATH . "snippets/";';
$_lang['resolver_type_desc'] = 'File resolvers จะตรวจะสอบให้แน่ใจว่าได้คัดลอกไฟล์ทั้งหมดในไดเรกทอรีของซอร์สไปที่เป้าหมาย ส่วน PHP Script resolvers execute ไฟล์ซอร์สเป็นแบบ PHP';
$_lang['resolvers'] = 'ตัวแก้ปัญหา';
$_lang['source'] = 'ซอร์ส';
$_lang['target'] = 'เป้าหมาย';
$_lang['type'] = 'ประเภท';
$_lang['unique_key'] = 'Unique Key';
$_lang['unique_key_desc'] = 'A unique key เป็นการระบุวิธิการค้นหาของอ็อบเจค สามารถเป็นสตริงหรือรายการที่คั่นด้วยจุลภาค ตัวอย่าง: <br />"name" สำหรับ modPlugin<br />"templatename" สำหรับ modTemplate<br />หรือซับซ้อนมากขึ้น "pluginid,evtid" สำหรับ modPluginEvent';
$_lang['update_object'] = 'ปรับปรุงอ็อบเจคต์';
$_lang['update_object_desc'] = 'ถ้าเช็คถูก จะปรับปรุงอ็อบเจคถ้ามันถูกพบ ถ้าไม่เช็ค จะไม่บันทึกอ็อบเจค ถ้ามันเจอแล้ว';
$_lang['use_wizard'] = 'ใช้ตัวช่วยสร้าง';
$_lang['use_xml'] = 'สร้างจากไฟล์ XML';
$_lang['vehicle'] = 'Vehicle';
$_lang['vehicle_add'] = 'เพิ่ม Vehicle';
$_lang['vehicle_create'] = 'สร้าง Vehicle';
$_lang['vehicle_remove'] = 'ลบ Vehicle';
$_lang['vehicle_remove_confirm'] = 'คุณแน่ใจว่าต้องการลบ vehicle นี้?';
$_lang['vehicles'] = 'Vehicles';
$_lang['vehicles_add'] = 'เพิ่ม Vehicles';
$_lang['vehicles_desc'] = 'Vehicles เป็นอ็อบเจคที่ถูกบรรจุในแพคเกจ คุณอาจเพิ่มมันได้ที่นี่';
$_lang['version'] = 'เวอร์ชัน';
$_lang['xml_file_err_upload'] = 'มีข้อผิดพลาดขณะที่พยายามอัพโหลดไฟล์ XML';