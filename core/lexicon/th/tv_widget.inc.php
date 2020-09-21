<?php
/**
 * TV Widget English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['attributes'] = 'คุณลักษณะ';
$_lang['capitalize'] = 'ใช้อักษรตัวพิมพ์ใหญ่';
$_lang['checkbox'] = 'Check Box';
$_lang['checkbox_columns'] = 'คอลัมน์';
$_lang['checkbox_columns_desc'] = 'The number of columns the checkboxes are displayed in.';
$_lang['class'] = 'คลาส';
$_lang['combo_allowaddnewdata'] = 'อนุญาตให้เพิ่มไอเท็มใหม่';
$_lang['combo_allowaddnewdata_desc'] = 'เมื่อเป็นใช่ จะอนุญาตให้ไอเท็มที่ไม่มีอยู่ก่อนหน้าในรายการถูกเพิ่มได้ ค่าปริยายคือไม่';
$_lang['combo_forceselection'] = 'บังคับการเลือกรายการ';
$_lang['combo_forceselection_desc'] = 'ถ้าใช้การพิมพ์ล่วงหน้า ถ้าตั้งค่านี้เป็นไม่ จะอนุญาตเฉพาะการป้อนอินพุตของไอเท็มในรายการเท่านั้น';
$_lang['combo_forceselection_multi_desc'] = 'If this is set to Yes, only items already in the list are allowed. If No, new values can be entered a well.';
$_lang['combo_listempty_text'] = 'ข้อความรายการว่างเปล่า';
$_lang['combo_listempty_text_desc'] = 'ถ้าการพิมพ์ล่วงหน้าถูกเปิด และผู้ใช้ป้อนค่าที่ไม่อยู่ในรายการ จะแสดงข้อความนี้';
$_lang['combo_listheight'] = 'List Height';
$_lang['combo_listheight_desc'] = 'The height, in pixels, of the dropdown list itself. Defaults to the height of the combobox.';
$_lang['combo_listwidth'] = 'ความกว้างของรายการ';
$_lang['combo_listwidth_desc'] = 'ความกว้างเป็นหน่วยพิกเซลของรายการแบบ dropdown ค่าปริยายคือความกว้างของ combobox';
$_lang['combo_maxheight'] = 'ความสูงมากที่สุด';
$_lang['combo_maxheight_desc'] = 'ความสูงมากที่สุดในหน่วยพิกเซลของรายการแบบ dropdown ก่อนที่จะแสดงแถบเลื่อน (ค่าปริยายคือ 300)';
$_lang['combo_stackitems'] = 'กองซ้อนไอเท็มที่เลือก';
$_lang['combo_stackitems_desc'] = 'เมื่อตั้งค่าเป็นใช่ ไอเท็มจะถูกกองซ้อน 1 ต่อบรรทัด ค่าปริยายเป็นไม่ซึ่งจะแสดงไอเท็มแบบอินไลน์';
$_lang['combo_title'] = 'ส่วนหัวของรายการ';
$_lang['combo_title_desc'] = 'เอเลเมนต์ส่วนหัวจะถูกสร้างให้มีข้อความนี้และถูกเพิ่มเข้าไปในส่วนบนของรายการแบบ dropdown';
$_lang['combo_typeahead'] = 'เปิดใช้งานการพิมพ์ล่วงหน้า';
$_lang['combo_typeahead_desc'] = 'ถ้าค่าเป็นใช่ การเติมและเลือกอัตโนมัติส่วนที่เหลือของข้อความที่กำลังถูกพิมพ์หลังค่าหน่วงเวลาที่กำหนด (ค่าหน่วงเวลาการพิมพ์ล่วงหน้า) ถ้ามันตรงกับค่าที่รู้จัก (ค่าปริยายคือปิดการทำงาน)';
$_lang['combo_typeahead_delay'] = 'ค่าหน่วงเวลาการพิมพ์ล่วงหน้า';
$_lang['combo_typeahead_delay_desc'] = 'ความยาวของเวลาในหน่วยมิลลิวินาทีเพื่อรอจนกว่าข้อความการพิมพ์ล่วงหน้าจะถูกแสดงถ้าการพิมพ์ล่วงหน้าถูกเปิดใช้งาน (ค่าปริยายคือ 250)';
$_lang['date'] = 'วันที่';
$_lang['date_format'] = 'รูปแบบวันที่';
$_lang['date_use_current'] = 'ถ้าไม่ทราบให้ใช้วันที่ปัจจุบัน';
$_lang['default'] = 'ปริยาย';
$_lang['delim'] = 'ตัวคั่น';
$_lang['delimiter'] = 'ตัวคั่น';
$_lang['disabled_dates'] = 'วันที่ที่จะปิดใช้งาน';
$_lang['disabled_dates_desc'] = 'รายการคั่นด้วยเครื่องหมายจุลภาคของ "วันที่" ที่จะปิดการใช้งาน แบบเป็นสตริง สตริงเหล่านี้จะถูกใช้เพื่อสร้าง regular expressionแบบไดนามิก ดังนั้นจึงมีประสิทธิภาพมาก ตัวอย่าง:<br />
- ปิดใช้งานวันที่เหล่านี้โดยตรง: 2003-03-08,2003-09-16<br />
- ปิดใช้งานวันเหล่านี้ในทุกๆปี: 03-08,09-16<br />
- เฉพาะที่ตรงกับจุดเริ่มต้น (มีประโยชน์ถ้าคุณกำลังใช้ปีแบบสั้น): ^03-08<br />
- ปิดใช้งานทุกๆวันในเดือนมีนาคม ปี 2006: 03-..-2006<br />
- ปิดใช้งานทุกๆวันในทุกๆเดือนมีนาคม: ^03<br />
โปรดจำว่ารูปแบบของวันที่ที่แนบมาในรายการจะต้องตรงกับรูปแบบที่ตั้งค่าแป๊ะๆ เพื่อที่รองรับ regular expressions ถ้าคุณกำลังใช้รูปแบบวันที่ที่มี "." คุณจะต้องหลีกเลี่ยงจุดเมื่อทำการจำกัดวันที่';
$_lang['disabled_days'] = 'วันที่จะปิดใช้งาน';
$_lang['disabled_days_desc'] = 'รายการคั่นด้วยเครื่องหมายจุลภาคของวันที่จะปิดการใช้งาน (ค่าปริยายคือ null) ตัวอย่าง:<br />
- ปิดใช้งานวันอาทิตย์และวันเสาร์: 0,6<br />
- ปิดใช้งานวันธรรมดา: 1,2,3,4,5';
$_lang['dropdown'] = 'รายการเมนูแบบเลื่อนลง';
$_lang['earliest_date'] = 'วันที่แรกสุด';
$_lang['earliest_date_desc'] = 'วันที่แรกสุดที่อนุญาตให้สามารถเลือกได้';
$_lang['earliest_time'] = 'เวลาแรกสุด';
$_lang['earliest_time_desc'] = 'เวลาแรกสุดที่อนุญาตให้สามารถเลือกได้';
$_lang['email'] = 'อีเมล์';
$_lang['file'] = 'ไฟล์';
$_lang['height'] = 'สูง';
$_lang['hidden'] = 'ซ่อน';
$_lang['htmlarea'] = 'พื้นที่ของ HTML';
$_lang['htmltag'] = 'แท็ก HTML';
$_lang['image'] = 'รูปภาพ';
$_lang['image_align'] = 'การวางแนว';
$_lang['image_align_list'] = 'none,baseline,top,middle,bottom,texttop,absmiddle,absbottom,left,right';
$_lang['image_alt'] = 'ข้อความสำรอง';
$_lang['image_border_size'] = 'ขนาดเส้นขอบ';
$_lang['image_hspace'] = 'พื้นที่แนวนอน';
$_lang['image_vspace'] = 'พื้นที่แนวตั้ง';
$_lang['latest_date'] = 'วันที่ล่าสุด';
$_lang['latest_date_desc'] = 'วันที่ล่าสุดที่อนุญาตให้สามารถเลือกได้';
$_lang['latest_time'] = 'เวลาล่าสุด';
$_lang['latest_time_desc'] = 'เวลาล่าสุดที่อนุญาตให้สามารถเลือกได้';
$_lang['listbox'] = 'Listbox (เลือกได้อย่างเดียว)';
$_lang['listbox-multiple'] = 'Listbox (เลือกได้หลายอย่าง)';
$_lang['list-multiple-legacy'] = 'Legacy multiple list';
$_lang['lower_case'] = 'ตัวพิพม์เล็ก';
$_lang['max_length'] = 'ความยาวสูงสุด';
$_lang['min_length'] = 'ความยาวขั้นต่ำ';
$_lang['regex_text'] = 'Regular Expression Error';
$_lang['regex'] = 'Regular Expression Validator';
$_lang['name'] = 'ชื่อ';
$_lang['number'] = 'หมายเลข';
$_lang['number_allowdecimals'] = 'อนุญาตทศนิยม';
$_lang['number_allownegative'] = 'อนุญาตค่าติดลบ';
$_lang['number_decimalprecision'] = 'ความละเอียดของทศนิยม';
$_lang['number_decimalprecision_desc'] = 'ความละเอียดสูงสุดที่จะแสดงผลหลังตัวคั่นทศนิยม (ค่าปริยายคือ 2)';
$_lang['number_decimalseparator'] = 'ตัวคั่นทศนิยม';
$_lang['number_decimalseparator_desc'] = 'ตัวอักษรที่อนุญาตให้เป็นตัวคั่นทศนิยม (ค่าปริยายคือ ".")';
$_lang['number_maxvalue'] = 'ค่าสูงสุด';
$_lang['number_minvalue'] = 'ค่าต่ำสุด';
$_lang['option'] = 'ตัวเลือก';
$_lang['parent_resources'] = 'แพเรนต์รีซอร์ส';
$_lang['radio_columns'] = 'คอลัมน์';
$_lang['radio_columns_desc'] = 'จำนวนคอลัมน์ของ radio boxes ที่จะแสดงผล';
$_lang['rawtext'] = 'Raw Text (เลิกใช้แล้ว)';
$_lang['rawtextarea'] = 'Raw Textarea (เลิกใช้แล้ว)';
$_lang['required'] = 'อนุญาตให้ว่างเปล่า';
$_lang['required_desc'] = 'ถ้าตั้งค่าเป็นไม่ MODX จะไม่อนุญาตให้ผู้ใช้บันทึกรีซอร์สได้จนกว่ามันจะถูกต้องคือค่าที่ไม่ว่างเปล่าได้รับการป้อน';
$_lang['resourcelist'] = 'รายการรีซอร์ส';
$_lang['resourcelist_depth'] = 'ความลึก';
$_lang['resourcelist_depth_desc'] = 'ระดับความลึกที่การคิวรีข้อมูลจะเข้าไปดึงมาเป็นรายการของรีซอร์ส ค่าปริยายคือระดับ 10';
$_lang['resourcelist_includeparent'] = 'รวมถึงแพเรนต์';
$_lang['resourcelist_includeparent_desc'] = 'ถ้าตั้งค่าเป็นใช่ จะรวมการตั้งชื่อรีซอร์สไว้ในฟิลด์แพเรนต์ในรายการ';
$_lang['resourcelist_limitrelatedcontext'] = 'Limit to Related Context';
$_lang['resourcelist_limitrelatedcontext_desc'] = 'If Yes, will only include the Resources related to the context of the current Resource.';
$_lang['resourcelist_limit'] = 'จำกัด';
$_lang['resourcelist_limit_desc'] = 'จำนวนของรีซอร์สเพื่อจำกัดในรายการ 0 หรือว่างเปล่าแปลว่าไม่จำกัด';
$_lang['resourcelist_parents'] = 'แพเรนต์';
$_lang['resourcelist_parents_desc'] = 'รายการของไอดีเพื่อดึงลูกมาสำหรับรายการ';
$_lang['resourcelist_where'] = 'เงื่อนไข Where';
$_lang['resourcelist_where_desc'] = 'JSON อ็อบเจกต์ของเงื่อนไข where เพื่อกรองในการคิวรีข้อมูลที่ดึงมาจากรายการของรีซอร์ส (ไม่รองรับการค้นหาด้วยตัวแปรแม่แบบ)';
$_lang['richtext'] = 'RichText';
$_lang['sentence_case'] = 'Sentence Case';
$_lang['shownone'] = 'อนุญาตตัวเลือกที่ว่างเปล่า';
$_lang['shownone_desc'] = 'อนุญาตให้ผู้ใช้เลือกตัวเลือกที่ว่างเปล่าซึ่งเป็นค่าว่าง';
$_lang['start_day'] = 'วันเริ่มต้น';
$_lang['start_day_desc'] = 'ดัชนีของวันที่ควรเริ่มในสัปดาห์ (ค่าปริยายคือ 0 ซึ่งก็คือวันอาทิตย์)';
$_lang['string'] = 'สตริง';
$_lang['string_format'] = 'รูปแบบสตริง';
$_lang['style'] = 'รูปแบบ';
$_lang['tag_id'] = 'หมายเลขแท็ก';
$_lang['tag_name'] = 'ชื่อแท็ก';
$_lang['target'] = 'เป้าหมาย';
$_lang['text'] = 'ข้อความ';
$_lang['textarea'] = 'textarea';
$_lang['textareamini'] = 'Textarea (ขนาดย่อม)';
$_lang['textbox'] = 'กล่องข้อความ';
$_lang['time_increment'] = 'การเพิ่มของเวลา';
$_lang['time_increment_desc'] = 'จำนวนนาทีระหว่างทุกๆเวลาในรายการ (ค่าปริยายคือ 15)';
$_lang['hide_time'] = 'Hide time option for user';
$_lang['title'] = 'ชื่อเรื่อง';
$_lang['upper_case'] = 'ตัวพิมพ์ใหญ่';
$_lang['url'] = 'URL';
$_lang['url_display_text'] = 'ข้อความที่จะแสดง';
$_lang['width'] = 'ความกว้าง';
