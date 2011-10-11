<?php
/**
 * Config Check Thai lexicon topic
 *
 * @language th
 * @package modx
 * @subpackage lexicon
 
 * @author Mr.Kittipong Intaboot COE#18,KKU
 * @updated 2010-07-21
 */
$_lang['configcheck_admin'] = 'กรุณาติดต่อผู้ดูแลระบบเว็บไซต์ของคุณและแจ้งเตือนพวกเขาเกี่ยวกับข้อความนี้!';
$_lang['configcheck_cache'] = 'โฟลเดอร์แคชไม่สามารถเขียนได้';
$_lang['configcheck_cache_msg'] = 'MODX ไม่สามารถเขียนลงโฟลเดอร์แคช MODX จะยังคงทำงานได้ตามปกติ แต่จะไม่ทำการแคช ทางแก้ปัญหาคือไปทำให้โฟลเดอร์ /_cache/ สามารถเขียนได้';
$_lang['configcheck_configinc'] = 'ไฟล์การตั้งค่า(Config) ยังคงสามารถเขียนได้!';
$_lang['configcheck_configinc_msg'] = 'เว็บไซต์ของคุณอาจถูกโจมตีได้ง่ายจากเหล่าแฮกเกอร์ซึ่งอาจก่อให้เกิดอันตรายอย่างมากต่อเว็บไซต์ของคุณ กรุณาทำให้ไฟล์การตั้งค่า (config) ของคุณสามารถเขียนได้เท่านั้น! ถ้าคุณไม่ได้เป็นผู้ดูแลระบบเว็บไซต์ กรุณาติดต่อผู้ดูแลระบบเว็บไซต์ของคุณและแจ้งเตือนพวกเขาเกี่ยวกับข้อความนี้! ไฟล์ตั้งอยู่ที่ [[+path]]';
$_lang['configcheck_default_msg'] = 'พบคำเตือนที่ยังไม่ระบุ ซึ่งเป็นเรื่องแปลก';
$_lang['configcheck_errorpage_unavailable'] = 'Error page ของเว็บไซต์คุณ ไม่สามารถใช้งานได้';
$_lang['configcheck_errorpage_unavailable_msg'] = 'นี่หมายความว่า Error page ของคุณไม่สามรถเข้าถึงได้ตามปกติหรือไม่มีอยู่ นี่อาจนำไปยังสภาพการวนรอบกลับไปกลับมาและอาจมีข้อผิดพลาดจำนวนมากในบันทึกข้อผิดพลาดของคุณ ตรวจให้แน่ใจว่าไม่มีกลุ่มผู้ใช้เว็บกำหนดใส่หน้านี้';
$_lang['configcheck_errorpage_unpublished'] = 'Error page ของเว็บไซต์คุณไม่ถูกเผยแพร่หรือไม่มีอยู่จริง';
$_lang['configcheck_errorpage_unpublished_msg'] = 'นี่หมายความว่า Error page ของคุณไม่สามารถเข้าถึงได้โดยสาธารณชนทั่วไป ทำการเผยแพร่หน้านี้หรือตรวจสอบว่าเป็นเอกสารที่มีอยู่ในแผนผังของเว็บไซต์คุณ &gt; เมนูการตั้งค่าระบบ';
$_lang['configcheck_images'] = 'โฟลเดอร์รูปภาพไม่สามารถเขียนได้';
$_lang['configcheck_images_msg'] = 'โฟลเดอร์รูปภาพไม่สามารถเขียนได้หรือไม่มีอยู่  นี่หมายความว่าฟังก์ชันตัวจัดการรูปภาพในอิดิเตอร์จะไม่ทำงาน!';
$_lang['configcheck_installer'] = 'ตัวติดตั้งยังคงอยู่';
$_lang['configcheck_installer_msg'] = 'โฟลเดอร์ setup/ ที่บรรจุตัวติดตั้งสำหรับ MODX ยังคงอยู่ ลองจินตนาการถึงสิ่งที่อาจเกิดขึ้นถ้าผู้โจมตีสามารถหาโฟลเดอร์นี้และเริ่มการติดตั้ง! อาจจะไม่ยากเกินไปในการติดตั้งใหม่ เพราะเพียงแค่เขารู้ข้อมูลของฐานข้อมูลเท่านั้น แต่จะดีกว่าถ้าคุณลบโฟลเดอร์นี้ออกจากเซิร์ฟเวอร์ของคุณ It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = 'ตัวเลขของเอนทรีในไฟล์ภาษาไม่ถูกต้อง';
$_lang['configcheck_lang_difference_msg'] = 'การเลือกภาษาในตอนนี้มีหมายเลขของเอนทรีกับภาษาปริยาย แต่ที่ไม่ได้เป็นปัญหาที่สำคัญ นี่หมายความว่าไฟล์ภาษาจำเป็นต้องถูกปรับปรุง';
$_lang['configcheck_notok'] = 'หนึ่งหรือมากว่ารายละเอียดของการตั้งค่าแจ้งว่าไม่เรียบร้อย: ';
$_lang['configcheck_ok'] = 'ผ่านการตรวจสอบเรียบร้อย - ไม่มีรายงานคำเตือน';
$_lang['configcheck_register_globals'] = 'register_globals ถูกตั้งเป็น ON ในไฟล์การตั้งค่า php.ini ของคุณ';
$_lang['configcheck_register_globals_msg'] = 'การตั้งค่านี้ ทำให้เว็บไซต์ของคุณไวต่อการถูกโจมตีข้ามเว็บไซต์ด้วยสคริปต์ (XSS) คุณควรบอกโฮสต์ของคุณว่าคุณควรทำอย่างไรหากต้องการปิดการใช้งานการตั้งค่านี้';
$_lang['configcheck_title'] = 'ตรวจสอบการตั้งค่า';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Unauthorized page ของเว็บไซต์คุณ ไม่ถูกเผยแพร่หรือไม่มีอยู่จริง.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'นี่หมายความว่า Unauthorized page ไม่สามารถเข้าถึงได้ตามปกติหรือไม่มีอยู่จริง นี่อาจนำไปยังสภาพการวนรอบกลับไปกลับมาและอาจมีข้อผิดพลาดจำนวนมากในบันทึกข้อผิดพลาดของคุณ ตรวจให้แน่ใจว่าไม่มีกลุ่มผู้ใช้เว็บกำหนดใส่หน้านี้';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Unauthorized page ที่ถูกระบุไว้ในการตั้งค่าของเว็บไซต์ไม่ถูกเผพแพร่';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'นี่หมายความว่า Unauthorized page ของคุณไม่สามารถเข้าถึงได้โดยสาธารณชนทั่วไป ทำการเผยแพร่หน้านี้หรือตรวจสอบว่าเป็นเอกสารที่มีอยู่ในแผนผังของเว็บไซต์คุณ &gt; เมนูการตั้งค่าระบบ';
$_lang['configcheck_warning'] = 'คำเตือนการตั้งค่า:';
$_lang['configcheck_what'] = 'หมายความว่าอย่างไร?';