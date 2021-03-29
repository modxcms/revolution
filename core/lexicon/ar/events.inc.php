<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'أحداث';
$_lang['system_event'] = 'أحداث النظام';
$_lang['system_events'] = 'أحداث النظام';
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'البحث عن طريق اسم الحدث';
$_lang['system_events.name_desc'] = 'اسم الحدث. الذي ينبغي استخدامه في الدالة &dollar;modx->invokeEvent(name, properties).';
$_lang['system_events.groupname'] = 'مجموعة';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'إضافات';
$_lang['system_events.plugins_desc'] = 'The list of plugins attached to the event. Pick up plugins that should be attached to event.';

$_lang['system_events.service'] = 'الخدمة';
$_lang['system_events.service_1'] = 'أحداث خدمة المحلل';
$_lang['system_events.service_2'] = 'أحداث الوصول إلى صفحة الإدارة';
$_lang['system_events.service_3'] = 'أحداث خدمة الوصول إلى ويب';
$_lang['system_events.service_4'] = 'أحداث خدمة ذاكرة التخزين المؤقت';
$_lang['system_events.service_5'] = 'أحداث خدمة القالب';
$_lang['system_events.service_6'] = 'الأحداث المعرفة من قبل المستخدم';

$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'اسم "حدث النظام" غير محدد.';
$_lang['system_events_err_ae'] = 'اسم "حدث النظام" موجود مسبقاُ.';
$_lang['system_events_err_startint'] = 'لا يسمح ببدأ الاسم برقم.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
