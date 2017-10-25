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
$_lang['system_events.desc'] = 'نظام الأحداث هي الأحداث في MODX التي تكون الإضافات مسجلة إلىها. يتم إطلاقها في جميع أنحاء التعليمة البرمجية MODX، السماح للإضافات التفاعل مع التعليمات البرمجية MODX وإضافة وظيفة مخصصة دون القرصنة البرمجية الأساسية. يمكنك إنشاء الأحداث الخاصة بك للمشروع المخصص الخاص بك هنا أيضا. لا يمكنك إزالة الأحداث الأساسية، فقط الخاص بك.';
$_lang['system_events.search_by_name'] = 'البحث عن طريق اسم الحدث';
$_lang['system_events.create'] = 'إنشاء حدث جديد';
$_lang['system_events.name_desc'] = 'اسم الحدث. الذي ينبغي استخدامه في الدالة &dollar;modx->invokeEvent(name, properties).';
$_lang['system_events.groupname'] = 'مجموعة';
$_lang['system_events.groupname_desc'] = 'اسم المجموعة التي ينتمي إليها الحدث الجديد. حدد واحدة موجودة أو اكتب اسم مجموعة جديدة.';
$_lang['system_events.plugins'] = 'إضافات';
$_lang['system_events.plugins_desc'] = 'The list of plugins attached to the event. Pick up plugins that should be attached to event.';

$_lang['system_events.service'] = 'الخدمة';
$_lang['system_events.service_1'] = 'أحداث خدمة المحلل';
$_lang['system_events.service_2'] = 'أحداث الوصول إلى صفحة الإدارة';
$_lang['system_events.service_3'] = 'أحداث خدمة الوصول إلى ويب';
$_lang['system_events.service_4'] = 'أحداث خدمة ذاكرة التخزين المؤقت';
$_lang['system_events.service_5'] = 'أحداث خدمة القالب';
$_lang['system_events.service_6'] = 'الأحداث المعرفة من قبل المستخدم';

$_lang['system_events.remove'] = 'إزالة الحدث';
$_lang['system_events.remove_confirm'] = 'هل أنت متأكد من أنك تريد إزالة هذا الحدث <b>[[+name]]</b> وهذا أمر لا رجعة فيه!';

$_lang['system_events_err_ns'] = 'اسم "حدث النظام" غير محدد.';
$_lang['system_events_err_ae'] = 'اسم "حدث النظام" موجود مسبقاُ.';
$_lang['system_events_err_startint'] = 'لا يسمح ببدأ الاسم برقم.';
$_lang['system_events_err_remove_not_allowed'] = 'غير مسموح لك بحذف هذا الحدث.';
