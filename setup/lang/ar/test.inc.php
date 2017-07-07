<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'التحقق من أن <span class="mono">[[+file]]</span> موجود وقابل للكتابة: ';
$_lang['test_config_file_nw'] = 'من أجل تثبيت Linux/Unix، الرجاء إنشاء ملف فارغ باسم<span class="mono">[[+key]].inc.php</span>
في دليل أساس مودكس الخاص بك<span class="mono">config/</span>  مع ضبط الصلاحيات على PHP قابل للقراءة.';
$_lang['test_db_check'] = 'إنشاء اتصال مع قاعدة المعطيات: ';
$_lang['test_db_check_conn'] = 'التحقق من تفاصيل الاتصال والمحاولة مجدداً.';
$_lang['test_db_failed'] = 'فشل الاتصال بقاعدة المعطيات!';
$_lang['test_db_setup_create'] = 'برنامج التنصيب سيحاول إنشاء قاعدة المعطيات.';
$_lang['test_dependencies'] = 'التحقق من PHP من أجل اعتمادية zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'تثبيت PHP الخاص بك لا يمتلك الإضافة "zlib" مثبتة. هذه الإضافة مهمة من أجل مودكس كي يعمل. الرجاء تفعيله للمتابعة.';
$_lang['test_directory_exists'] = 'التأكد من أن الدليل <span class="mono">[[+dir]]</span> موجود: ';
$_lang['test_directory_writable'] = 'التأكد من أن الدليل <span class="mono">[[+dir]]</span> قابل للكتابة: ';
$_lang['test_memory_limit'] = 'التحقق إذا تم تعيين الحد الأقصى للذاكرة إلى مالا يقل عن 24M: ';
$_lang['test_memory_limit_fail'] = 'وجد مودكس الإعداد memory_limit الخاص بك مضبوط على [[+memory]]، في الأسفل يوجد الإعداد الموصى به 24M. مودكس يحاول ضبط memory_limit إلى 24M، لكن العملية فشلت. الرجاء تعيين الإعداد memory_limit في ملف php.ini الخاص بك إلى مالا يقل عن 24 M أو أعلى قبل المتابعة. إذا كنت لا تزال تواجه مشكلة (مثل الحصول على شاشة بيضاء فارغة عند التثبيت)، إضبطه إلى 32 M، 64 M أو أعلى.';
$_lang['test_memory_limit_success'] = 'موافق! ضبط إلى [[+memory]]';
$_lang['test_mysql_version_5051'] = 'مودكس سوف يواجه مشاكل مع إصدار MySQL الخاص بك ([[+version]]) بسبب العديد من الأخطاء التي تتصل ببرامج تشغيل PDO في هذا الإصدار. الرجاء ترقية MySQL لتصحيح هذه المشاكل. حتى إذا اخترت عدم استخدام مودكس، من المستحسن ترقية هذا الإصدار من أدل الأمن والاستقرار لموقع الوب الخاص بك.';
$_lang['test_mysql_version_client_nf'] = 'لم يستطع تحديد نسخة زبون MySQL!';
$_lang['test_mysql_version_client_nf_msg'] = 'لم يستطع مودكس تحديد إصدار MySQL للزبون الخاصة بك عن طريق mysql_get_client_info(). يرجى التأكد يدوياً من أن إصدار MySQL للزبون الخاص بك هو 4.1.20 على الأقل قبل المتابعة.';
$_lang['test_mysql_version_client_old'] = 'مودكس قد يواجه مشاكل لأنك تستخدم نسخة MySQL قديمة جداً ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'مودكس سوف يسمح بالتثبيت باستخدام هذا الإصدار من MySQL للزبون، ولكن لا يمكننا أن نضمن أن كافة الوظائف سوف تكون متاحة أو تعمل بشكل صحيح عند استخدام الإصدارات القديمة من مكتبات MySQL للزبون.';
$_lang['test_mysql_version_client_start'] = 'التحقق من نسخة MySQL للزبون:';
$_lang['test_mysql_version_fail'] = 'أنت تعمل على MySQL[[+version]]، ومودكس الثوري يتطلب MySQL 4.1.20 أو بعد. الرجاء ترقية نسخة MySQLعلى الأقل إلى 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'لم يتم التعرف على نسخة مخدم MySQL!';
$_lang['test_mysql_version_server_nf_msg'] = 'لم يستطع مودكس التعرف على إصدار مخدم MySQL الخاص بك عن طريق mysql_get_server_info(). يرجى التأكد يدوياً من أن إصدار مخدم MySQL الخاص بك هو 4.1.20 على الأقل قبل المتابعة.';
$_lang['test_mysql_version_server_start'] = 'التحقق من إصدار مخدم MySQL:';
$_lang['test_mysql_version_success'] = 'موافق! تشغيل: [[+version]]';
$_lang['test_nocompress'] = 'التأكد فيما اذا كان يجب أن نعطل ضغط CSS/JS: ';
$_lang['test_nocompress_disabled'] = 'موافق! عطل.';
$_lang['test_nocompress_skip'] = 'غير مختار، تجاوز الفحص.';
$_lang['test_php_version_fail'] = 'أنت تعمل على PHP [[+version]]، ومودكس الثوري يتطلب PHP 5.1.1 أو بعد. الرجاء ترقية نسخة PHP على الأقل إلى 5.1.1. مودكس يوصي بالترقية إلى + 5.3.2 على الأقل.';
$_lang['test_php_version_start'] = 'التحقق من إصدار PHP:';
$_lang['test_php_version_success'] = 'موافق! تشغيل: [[+version]]';
$_lang['test_safe_mode_start'] = 'التحقق للتأكد من أن safe_mode مطفي:';
$_lang['test_safe_mode_fail'] = 'مودكس وجد أن  safe_mode يعمل. يجب تعطيل  safe_mode في إعدادات PHP الخاصة بك للإكمال.';
$_lang['test_sessions_start'] = 'التحقق من أن الجلسات مهيئة بشكل مناسب:';
$_lang['test_simplexml'] = 'التحقق من أجل SimpleXML:';
$_lang['test_simplexml_nf'] = 'لم يتم العثور على SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'مودكس لم يستطع العثور على SimpleXML في بيئة PHP الخاصة بك. حزمة الإدارة والوظائف الأخرى لن تعمل دون هذا التثبيت. يمكنك الاستمرار في عملية التثبيت، ولكن مودكس يوصي بتمكين SimpleXML للوظائف والميزات المتقدمة.';
$_lang['test_suhosin'] = 'التحقق من أجل مشاكل suhosin:';
$_lang['test_suhosin_max_length'] = 'القيمة العظمة لـ suhosin Get ضعيفة جداً!';
$_lang['test_suhosin_max_length_err'] = 'حاليا، أنت تستخدم ملحق suhosin PHP، و suhosin.get.max_value_length الخاص بك معين بقيمة منخفضة جداً لمودكس من أجل ضغط ملفات JS بشكل صحيح في المدير. مودكس يوصي برفع تلك القيمة إلى 4096؛ حتى ذلك الحين، مودكس سيعين تلقائياً ضغط JS الخاص بك (إعداد compress_js) إلى 0 لمنع الأخطاء.';
$_lang['test_table_prefix'] = 'التحقق من بادئة الجدول `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'بادئة الجدول موجودة مسبقاً ضمن قاعدة المعطيات هذه!';
$_lang['test_table_prefix_inuse_desc'] = 'تعذر على برنامج الإعداد التثبيت على قاعدة المعطيات المحددة، لأنها تحتوي بالفعل على الجداول مع البادئة التي قمت بتحديدها. الرجاء اختيار table_prefix جديدة، وقم بتشغيل برنامج الإعداد مرة أخرى.';
$_lang['test_table_prefix_nf'] = 'بادئة الجدول غير موجودة في قاعدة المعطيات هذه!';
$_lang['test_table_prefix_nf_desc'] = 'لا يمكن لبرنامج الإعداد التثبيت في قاعدة البيانات المحددة، لأنها لا تحتوي على جداول مع البادئة التي قمت بتحديدها للترقية. الرجاء اختيار table_prefix موجودة، وتشغيل برنامج الإعداد مرة أخرى.';
$_lang['test_zip_memory_limit'] = 'التحقق من إذا تم تعيين الحد الأقصى للذاكرة إلى مالا يقل عن 24M لملحقات الضغط: ';
$_lang['test_zip_memory_limit_fail'] = 'مودكس وجد أن إعداد memory_limit الخاص بك أدنى من الإعداد الموصى به "M24". مودكس حاول تعيين memory_limit إلى 24M، لكنه لم ينجح. الرجاء تعيين إعداد memory_limit في ملف php.ini الخاص بك إلى 24M أو أعلى قبل المتابعة، حتى تعمل الملحقات المضغوطة بشكل صحيح.';