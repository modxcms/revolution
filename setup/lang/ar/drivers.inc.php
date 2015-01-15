<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'مودكس يتطلب لاحقة MySQL من أجل PHP ولا يبدو أنها محملة.';
$_lang['mysql_err_pdo'] = 'مودكس يتطلب برنامج التشغيل pdo_mysql عند استخدام PDO الأصلي ولا يبدو أنه محمل.';
$_lang['mysql_version_5051'] = 'مودكس سوف يواجه مشاكل مع إصدار MySQL الخاص بك ([[+version]]) بسبب العديد من الأخطاء التي تتصل ببرامج تشغيل PDO في هذا الإصدار. الرجاء ترقية MySQL لتصحيح هذه المشاكل. حتى إذا اخترت عدم استخدام مودكس، من المستحسن ترقية هذا الإصدار من أدل الأمن والاستقرار لموقع الوب الخاص بك.';
$_lang['mysql_version_client_nf'] = 'لم يستطع مودكس تحديد إصدار MySQL للزبون الخاصة بك عن طريق mysql_get_client_info(). يرجى التأكد يدوياً من أن إصدار MySQL للزبون الخاص بك هو 4.1.20 على الأقل قبل المتابعة.';
$_lang['mysql_version_client_start'] = 'التحقق من نسخة MySQL للزبون:';
$_lang['mysql_version_client_old'] = 'مودكس قد يواجه مشاكل لأنك تستخدم نسخة MySQL للزبون قديمة جداً ([[+version]]). مودكس سوف يسمح بالتثبيت باستخدام هذا الإصدار من MySQL للزبون، ولكن لا يمكننا أن نضمن أن كافة الوظائف سوف تكون متاحة أو تعمل بشكل صحيح عند استخدام الإصدارات القديمة من مكتبات MySQL للزبون.';
$_lang['mysql_version_fail'] = 'أنت تعمل على MySQL[[+version]]، ومودكس الثوري يتطلب MySQL 4.1.20 أو بعد. الرجاء ترقية نسخة MySQLعلى الأقل إلى 4.1.20.';
$_lang['mysql_version_server_nf'] = 'لم يستطع مودكس التعرف على إصدار مخدم MySQL الخاص بك عن طريق mysql_get_server_info(). يرجى التأكد يدوياً من أن إصدار مخدم MySQL الخاص بك هو 4.1.20 على الأقل قبل المتابعة.';
$_lang['mysql_version_server_start'] = 'التحقق من إصدار مخدم MySQL:';
$_lang['mysql_version_success'] = 'موافق! تشغيل: [[+version]]';

$_lang['sqlsrv_version_success'] = 'موافق!';
$_lang['sqlsrv_version_client_success'] = 'موافق!';