<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'رجاءاً اتصل بمسؤول النظام حذرّه من حول هذه الرسالة!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'allow_tags_in_post إعدادات السياق ممكنة خارج `mgr`';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'إعدادات سياق allow_tags_in_post مفعلة ضمن التركيب الخاص بك خارج سياق mgr. مودكس يوصي أن تكون هذه الإعدادات غير مفعلة إلا في حالة كنت تريد أن تسمح للمستخدمين بشكل صريح أن يقوم بإرسال سياقات مودكس، الكيانات الرقمية، أو سياقات سكريبت HTML عن طريق POST إلى نموضج داخل موقعك. بشكل عام يجب أن يكون هذا غير مفعل ما عدا سياق mgr.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'إعدادات النظام allow_tags_in_post مفعلة';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'إعداد النظام allow_tags_in_post مفعل ضمن التركيب لديك. مودكس يوصي بأن يكون هذا الإعداد غير مفعل إلا إن كنت تريد صراحة أن تسمح للمستخدمين بإرسال وسوم مودكس، الكيانات الرقمية، أو وسوم سكريبت HTML عن طريقة POST إلى نموذج ضمن مقعك. الأفضل تفعيل هذه عن طريق إعدادات السياق من أجل سياقات محددة.';
$_lang['configcheck_cache'] = 'دليل الذاكرة المخبئية غير قابل للكتابة';
$_lang['configcheck_cache_msg'] = 'MODX cannot write to the cache directory. MODX will still function as expected, but no caching will take place. To solve this, make the /cache/ directory writable.';
$_lang['configcheck_configinc'] = 'ملف التهيئة مازال قابل للكتابة!';
$_lang['configcheck_configinc_msg'] = 'موقع الويب الخاص بك عرضه للقرصنة التي يمكن أن تفعل الكثير من الضرر للموقع. الرجاء جعل ملف التهيئة الخاص بك للقراءة فقط! إذا لم تكن مسؤول الموقع، الرجاء التواصل مع مسؤول الأنظمة وتحذيره من هذه الرسالة! إنها موجودة [[path+]]';
$_lang['configcheck_default_msg'] = 'تم العثور على تحذير غير محدد. هذا شيء غريب.';
$_lang['configcheck_errorpage_unavailable'] = 'صفحة الخطأ الخاصة بموعقك غير متاحة.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'هذا يعني أن صفحة الخطأ الخاصة بك غير قابلة لمتصفحي الويب العاديين أو غير موجودة. وهذا يمكن أن يؤدي إلى حلقات شرطية عودية والعديد من الأخطاء في سجلات الموقع الخاص بك. تأكد من أنه لا يوجد أية مجموعات webuser مسندة إلى الصفحة.';
$_lang['configcheck_errorpage_unpublished'] = 'صفحة الخطأ الخاصة بموقعك غير منشورة أو غير موجودة.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'هذا يعني أن صفحة الخطأ الخاص بك غير قابلة للوصول إلى الجمهور العام. انشر الصفحة أو تأكد من أن يتم إسنادها إلى مستند موجود في شجرة الموقع الخاص بك في النظام &gt; قائمة إعدادات النظام.';
$_lang['configcheck_htaccess'] = 'المجلد الأساسي يمكن الوصول إليه عن طريق شبكة الإنترنت';
$_lang['configcheck_htaccess_msg'] = 'MODX detected that your core folder is (partially) accessible to the public.
<strong>This is not recommended and a security risk.</strong>
If your MODX installation is running on a Apache webserver
you should at least set up the .htaccess file inside the core folder <em>[[+fileLocation]]</em>.
This can be easily done by renaming the existing ht.access example file there to .htaccess.
<p>There are other methods and webservers you may use, please read the <a href="https://docs.modx.com/3.x/en/getting-started/maintenance/securing-modx">Hardening MODX Guide</a>
for further information about securing your site.</p>
If you setup everything correctly, browsing e.g. to the <a href="[[+checkUrl]]" target="_blank">Changelog</a>
should give you a 403 (permission denied) or better a 404 (not found). If you can see the changelog
there in the browser, something is still wrong and you need to reconfigure or call an expert to solve this.';
$_lang['configcheck_images'] = 'دليل الصور غير قابل للكتابة';
$_lang['configcheck_images_msg'] = 'دليل الصور غير قابل للكتابة، أوغير موجود. هذا يعني أن وظائف الصور في المدير لن تعمل!';
$_lang['configcheck_installer'] = 'التركيب مازال حاضر';
$_lang['configcheck_installer_msg'] = 'The setup/ directory contains the installer for MODX. Just imagine what might happen if an evil person finds this folder and runs the installer! They probably won\'t get too far, because they\'ll need to enter some user information for the database, but it\'s still best to delete this folder from your server. It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = 'عدد غير صحيح من المدخلات ضمن ملف اللغة';
$_lang['configcheck_lang_difference_msg'] = 'اللغة المحددة حاليا تحتوي على عدد مختلف من الإدخالات عن اللغة الافتراضية. إنها ليست بالضرورة مشكلة، قد يعني هذا أن ملف اللغة بحاجة إلى ترقية.';
$_lang['configcheck_notok'] = 'يوجد واحد أوكثر من تفاصيل التهيئة لم يتم فحصهن بشكل جيد: ';
$_lang['configcheck_phpversion'] = 'إصدارPHP قديم';
$_lang['configcheck_phpversion_msg'] = 'الخاص بك PHP الإصدار [[+phpversion]] لم تعد تحت المحافظة لمطوري بي إتش بي، مما يعني عدم توفر تحديثات أمنية. من المرجح أيضا أن MODX أو حزمة إضافية الآن أو في المستقبل القريب لم يعد يدعم هذا الإصدار. الرجاء تحديث البيئة الخاصة بك على الأقل إلى PHP [[+phpversion]] وقت ممكن لتأمين موقع الويب الخاص بك.';
$_lang['configcheck_register_globals'] = 'register_globals لم يتم ضبطها إلى ON ضمن ملف التهيئة php.ini';
$_lang['configcheck_register_globals_msg'] = 'هذه التهيئة تجعل موقع الويب الخاص بك أكثر عرضه للهجمات عبر موقع البرمجة النصية (XSS). وينبغي أن تتحدث إلى المضيف الخاص بك حول ما يمكنك القيام به لتعطيل هذا الإعداد.';
$_lang['configcheck_title'] = 'فحص التهئية';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'الصفحة الغير مصرح بها الخاصة بالموقع غير منشورة أو غير موجودة.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'هذا يعني أن الصفحة الغير مصرح بها الخاصة بك غير قابلة لمتصفحي الويب العاديين أو غير موجودة. وهذا يمكن أن يؤدي إلى حلقات شرطية عودية والعديد من الأخطاء في سجلات الموقع الخاص بك. تأكد من أنه لا يوجد أية مجموعات webuser مسندة إلى الصفحة.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'الصفحة الغير مصرح بها المعرفة ضمن إعدادات الموقع غير منشورة.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'هذا يعني أن الصفحة الغير مصرح بها غير قابلة للوصول إلى الجمهور العام. انشر الصفحة أو تأكد من أن يتم إسنادها إلى مستند موجود في شجرة الموقع الخاص بك في النظام &gt; قائمة إعدادات النظام.';
$_lang['configcheck_warning'] = 'تحذير التهيئة:';
$_lang['configcheck_what'] = 'ماذا يعني هذا؟';
