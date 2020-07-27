<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'กรุณาติดต่อผู้ดูแลระบบเว็บไซต์ของคุณและแจ้งเตือนพวกเขาเกี่ยวกับข้อความนี้!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'allow_tags_in_post Context Setting Enabled outside `mgr`';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'The allow_tags_in_post Context Setting is enabled in your installation outside the mgr Context. MODX recommends this setting be disabled unless you need to explicitly allow users to submit MODX tags, numeric entities, or HTML script tags via the POST method to a form in your site. This should generally be disabled except in the mgr Context.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post System Setting Enabled';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'The allow_tags_in_post System Setting is enabled in your installation. MODX recommends this setting be disabled unless you need to explicitly allow users to submit MODX tags, numeric entities, or HTML script tags via the POST method to a form in your site. It is better to enable this via Context Settings for specific Contexts.';
$_lang['configcheck_cache'] = 'โฟลเดอร์แคชไม่สามารถเขียนได้';
$_lang['configcheck_cache_msg'] = 'MODX ไม่สามารถเขียนลงโฟลเดอร์แคช MODX จะยังคงทำงานได้ตามปกติ แต่จะไม่ทำการแคช ทางแก้ปัญหาคือไปทำให้โฟลเดอร์ /_cache/ สามารถเขียนได้';
$_lang['configcheck_configinc'] = 'ไฟล์การตั้งค่า(Config) ยังคงสามารถเขียนได้!';
$_lang['configcheck_configinc_msg'] = 'เว็บไซต์ของคุณอาจถูกโจมตีได้ง่ายจากเหล่าแฮกเกอร์ซึ่งอาจก่อให้เกิดอันตรายอย่างมากต่อเว็บไซต์ของคุณ กรุณาทำให้ไฟล์การตั้งค่า (config) ของคุณสามารถอ่านได้เท่านั้น! ถ้าคุณไม่ได้เป็นผู้ดูแลระบบเว็บไซต์ กรุณาติดต่อผู้ดูแลระบบเว็บไซต์ของคุณและแจ้งเตือนพวกเขาเกี่ยวกับข้อความนี้! ไฟล์ตั้งอยู่ที่  [[+path]]';
$_lang['configcheck_default_msg'] = 'พบคำเตือนที่ไม่ระบุรายละเอียด ซึ่งเป็นเรื่องแปลก';
$_lang['configcheck_errorpage_unavailable'] = 'Error page ของเว็บไซต์คุณ ไม่สามารถใช้งานได้';
$_lang['configcheck_errorpage_unavailable_msg'] = 'นี่หมายความว่า Error page ของคุณไม่สามรถเข้าถึงได้ตามปกติหรือไม่มีอยู่ ซึ่งอาจนำไปยังสภาพการวนรอบไม่รู้จบและอาจมีข้อผิดพลาดจำนวนมากในบันทึกข้อผิดพลาดของคุณ ตรวจให้แน่ใจว่าไม่มีกลุ่มผู้ใช้เว็บกลุ่มใดถูกกำหนดใส่หน้านี้';
$_lang['configcheck_errorpage_unpublished'] = 'Error page ของเว็บไซต์คุณไม่ถูกเผยแพร่หรือไม่มีอยู่จริง';
$_lang['configcheck_errorpage_unpublished_msg'] = 'นี่หมายความว่า Error page ของคุณไม่สามารถเข้าถึงได้โดยสาธารณชนทั่วไป ทำการเผยแพร่หน้านี้หรือตรวจสอบว่าเป็นเอกสารที่มีอยู่ในแผนผังของเว็บไซต์คุณ &gt; เมนูการตั้งค่าระบบ';
$_lang['configcheck_htaccess'] = 'Core folder is accessible by web';
$_lang['configcheck_htaccess_msg'] = 'MODX detected that your core folder is (partially) accessible to the public.
<strong>This is not recommended and a security risk.</strong>
If your MODX installation is running on a Apache webserver
you should at least set up the .htaccess file inside the core folder <em>[[+fileLocation]]</em>.
This can be easily done by renaming the existing ht.access example file there to .htaccess.
<p>There are other methods and webservers you may use, please read the <a href="https://docs.modx.com/current/en/getting-started/maintenance/securing-modx">Hardening MODX Guide</a>
for further information about securing your site.</p>
If you setup everything correctly, browsing e.g. to the <a href="[[+checkUrl]]" target="_blank">Changelog</a>
should give you a 403 (permission denied) or better a 404 (not found). If you can see the changelog
there in the browser, something is still wrong and you need to reconfigure or call an expert to solve this.';
$_lang['configcheck_images'] = 'โฟลเดอร์รูปภาพไม่สามารถเขียนได้';
$_lang['configcheck_images_msg'] = 'โฟลเดอร์รูปภาพไม่สามารถเขียนได้หรือไม่มีอยู่  นี่หมายความว่าฟังก์ชันตัวจัดการรูปภาพในอิดิเตอร์จะไม่ทำงาน!';
$_lang['configcheck_installer'] = 'ตัวติดตั้งยังคงทำงานอยู่';
$_lang['configcheck_installer_msg'] = 'โฟลเดอร์ setup/ ที่บรรจุตัวติดตั้งสำหรับ MODX ยังคงอยู่ ลองจินตนาการถึงสิ่งที่อาจเกิดขึ้นถ้าผู้โจมตีสามารถหาโฟลเดอร์นี้และเริ่มการติดตั้ง! อาจจะไม่ยากเกินไปในการติดตั้งใหม่ เพราะเพียงแค่เขารู้ข้อมูลของฐานข้อมูลเท่านั้น แต่จะดีกว่าถ้าคุณลบโฟลเดอร์นี้ออกจากเซิร์ฟเวอร์ของคุณ มันตั้งอยู่ที่: [[+path]]';
$_lang['configcheck_lang_difference'] = 'ตัวเลขของเอนทรีในไฟล์ภาษาไม่ถูกต้อง';
$_lang['configcheck_lang_difference_msg'] = 'การเลือกภาษาในตอนนี้มีหมายเลขของเอนทรีกับภาษาปริยาย แต่ที่ไม่ได้เป็นปัญหาที่สำคัญ นี่หมายความว่าไฟล์ภาษาจำเป็นต้องถูกอัปเดต';
$_lang['configcheck_notok'] = 'มีหนึ่งหรือมากกว่าของรายละเอียดการตั้งค่าได้แจ้งว่าไม่เรียบร้อย: ';
$_lang['configcheck_ok'] = 'ผ่านการตรวจสอบเรียบร้อย - ไม่มีรายงานคำเตือน';
$_lang['configcheck_phpversion'] = 'PHP version is outdated';
$_lang['configcheck_phpversion_msg'] = 'Your PHP version [[+phpversion]] is no longer maintained by the PHP developers, which means no security updates are available. It is also likely that MODX or an extra package now or in the near future will no longer support this version. Please update your environment at least to PHP [[+phprequired]] as soon as possible to secure your site.';
$_lang['configcheck_register_globals'] = 'register_globals ถูกตั้งเป็น ON ในไฟล์การตั้งค่า php.ini ของคุณ';
$_lang['configcheck_register_globals_msg'] = 'การตั้งค่านี้ ทำให้เว็บไซต์ของคุณไวต่อการถูกโจมตีข้ามเว็บไซต์ด้วยสคริปต์ (XSS) คุณควรติดต่อโฮสต์ของคุณว่าคุณควรทำอย่างไรหากต้องการปิดการใช้งานการตั้งค่านี้';
$_lang['configcheck_title'] = 'ตรวจสอบการตั้งค่า';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Unauthorized page ของเว็บไซต์คุณ ไม่ถูกเผยแพร่หรือไม่มีอยู่จริง.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'นี่หมายความว่า Unauthorized page ไม่สามารถเข้าถึงได้ตามปกติหรือไม่มีอยู่จริง ซึ่งอาจนำไปยังสภาพการวนรอบไม่รู้จบและอาจมีข้อผิดพลาดจำนวนมากในบันทึกข้อผิดพลาดของคุณ ตรวจให้แน่ใจว่าไม่มีกลุ่มผู้ใช้เว็บกลุ่มใดถูกกำหนดใส่หน้านี้';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Unauthorized page ที่ถูกระบุไว้ในการตั้งค่าของเว็บไซต์ไม่ถูกเผพแพร่';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'นี่หมายความว่า Unauthorized page ของคุณไม่สามารถเข้าถึงได้โดยสาธารณชนทั่วไป ทำการเผยแพร่หน้านี้หรือตรวจสอบว่าเป็นเอกสารที่มีอยู่ในแผนผังของเว็บไซต์คุณ &gt; เมนูการตั้งค่าระบบ';
$_lang['configcheck_warning'] = 'คำเตือนการตั้งค่า:';
$_lang['configcheck_what'] = 'นี่หมายความว่าอย่างไร?';
