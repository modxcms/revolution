<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'وصول';
$_lang['filter_by_category'] = 'فلترة حسب الصنف...';
$_lang['rank'] = 'ترتيب';
$_lang['template'] = 'قالب';
$_lang['template_assignedtv_tab'] = 'Assigned TVs';
$_lang['template_category_desc'] = 'Use to group Templates within the Elements tree.';
$_lang['template_code'] = 'Template Code (HTML)';
$_lang['template_delete_confirm'] = 'هل أنت متأكد من أنك تريد حذف هذا القالب؟';
$_lang['template_description_desc'] = 'Usage information for this Template shown in search results and as a tooltip in the Elements tree.';
$_lang['template_duplicate_confirm'] = 'هل أنت متأكد من أنك تريد تكرار هذا القالب؟';
$_lang['template_edit_tab'] = 'تعديل قالب';
$_lang['template_empty'] = '(فارغ)';
$_lang['template_err_default_template'] = 'هذا القالب معين كقالب افتراضي. الرجاء اختيار قالب افتراضي آخر في إعدادات مودكس قبل حذف هذا القالب.<br />';
$_lang['template_err_delete'] = 'An error occurred while trying to delete the template.';
$_lang['template_err_duplicate'] = 'حصل خطأ أثناء تكرار القالب.';
$_lang['template_err_ae'] = 'يوجد مسبقا قالب يحمل الاسم  "[[name+]]".';
$_lang['template_err_in_use'] = 'هذا القالب مستخدم حاليا. يرجى ضبط المستندات التي تستخدم القالب إلى قالب أخر. المستندات التي تستخدم القالب:<br />';
$_lang['template_err_invalid_name'] = 'Template name is invalid.';
$_lang['template_err_locked'] = 'القالب مقفل عن التعديل.';
$_lang['template_err_nf'] = 'لم يتم العثور على القالب!';
$_lang['template_err_ns'] = 'القالب غير محدد.';
$_lang['template_err_ns_name'] = 'يرجى تحديد اسم للقالب.';
$_lang['template_err_remove'] = 'An error occurred while trying to delete the template.';
$_lang['template_err_save'] = 'حصل خطأ أثناء حفظ القالب.';
$_lang['template_icon'] = 'Manager Icon Class';
$_lang['template_icon_desc'] = 'A CSS class to assign an icon (shown in the document trees) for all resources using this template. Font Awesome Free 5 classes such as “fa-home” may be used.';
$_lang['template_lock'] = 'قفل القالب عن التعديل';
$_lang['template_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Template.';
$_lang['template_locked_message'] = 'هذا القالب مقفل.';
$_lang['template_management_msg'] = 'هنا يمكنك اختيار أي قالب ترغب في تعديله.';
$_lang['template_name_desc'] = 'اسم هذا القالب.';
$_lang['template_new'] = 'Create Template';
$_lang['template_no_tv'] = 'No TVs have been assigned to this template yet.';
$_lang['template_preview'] = 'Preview Image';
$_lang['template_preview_desc'] = 'Used to preview the layout of this Template when creating a new Resource. (Minimum size: 335 x 236)';
$_lang['template_preview_source'] = 'Preview Image Media Source';
$_lang['template_preview_source_desc'] = 'Sets the basePath for this Template’s Preview Image to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['template_properties'] = 'الخصائص الافتراضية';
$_lang['template_reset_all'] = 'إعادة ضبط كل الصفحات لتستخدم القالب الافتراضي';
$_lang['template_reset_specific'] = 'إعادة ضبط الصفحات \'%s\' فقط';
$_lang['template_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template</em> as well as its content. The content must be HTML, either placed in the <em>Template Code</em> field below or in a static external file, and may include MODX tags. Note that changed or new templates won’t be visible in your site’s cached pages until the cache is emptied; however, you can use the preview function on a page to see the template in action.';
$_lang['template_tv_edit'] = 'Edit the sort order of the TVs';
$_lang['template_tv_msg'] = 'The <abbr title="Template Variables">TVs</abbr> assigned to this template are listed below.';
$_lang['templates'] = 'قوالب';
$_lang['tvt_err_nf'] = 'TV does not have access to the specified Template.';
$_lang['tvt_err_remove'] = 'An error occurred while trying to delete the TV from the template.';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['template_desc_category'] = $_lang['template_category_desc'];
$_lang['template_desc_description'] = $_lang['template_description_desc'];
$_lang['template_desc_name'] = $_lang['template_name_desc'];
$_lang['template_lock_msg'] = $_lang['template_lock_desc'];

// --tabs
$_lang['template_msg'] = $_lang['template_tab_general_desc'];
