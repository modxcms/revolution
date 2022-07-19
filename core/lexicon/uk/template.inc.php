<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'Доступ';
$_lang['filter_by_category'] = 'Фільтр за категорією...';
$_lang['rank'] = 'Порядок';
$_lang['template'] = 'Шаблон';
$_lang['template_assignedtv_tab'] = 'Призначені TV';
$_lang['template_category_desc'] = 'Use to group Templates within the Elements tree.';
$_lang['template_code'] = 'Template Code (HTML)';
$_lang['template_delete_confirm'] = 'Ви впевнені, що хочете видалити цей шаблон?';
$_lang['template_description_desc'] = 'Usage information for this Template shown in search results and as a tooltip in the Elements tree.';
$_lang['template_duplicate_confirm'] = 'Ви впевнені, що хочете дублювати цей шаблон?';
$_lang['template_edit_tab'] = 'Редагувати шаблон';
$_lang['template_empty'] = '(порожній шаблон)';
$_lang['template_err_default_template'] = 'Цей шаблон встановлений в якості шаблона за замовчуванням. Будь ласка, встановіть інший за замовчуванням у конфігурації MODX перед тим як видаляти даний шаблон.<br />';
$_lang['template_err_delete'] = 'Сталася помилка при спробі видалення шаблону.';
$_lang['template_err_duplicate'] = 'Сталася помилка при дублюванні шаблону.';
$_lang['template_err_ae'] = 'Шаблон з іменем "[[+name]]" вже існує.';
$_lang['template_err_in_use'] = 'Даний шаблон використовується. Будь ласка, призначте документам, що використовують даний шаблон, інший шаблон. Документи, що використовують даний шаблон:<br />';
$_lang['template_err_invalid_name'] = 'Неприпустима назва шаблону';
$_lang['template_err_locked'] = 'Шаблон заблокований для редагування.';
$_lang['template_err_nf'] = 'Шаблон не знайдений!';
$_lang['template_err_ns'] = 'Шаблон не вказаний.';
$_lang['template_err_ns_name'] = 'Будь ласка, вкажіть ім\'я шаблону.';
$_lang['template_err_remove'] = 'Сталася помилка при спробі видалення шаблону.';
$_lang['template_err_save'] = 'Сталася помилка при збереженні шаблону.';
$_lang['template_icon'] = 'Manager Icon Class';
$_lang['template_icon_desc'] = 'A CSS class to assign an icon (shown in the document trees) for all resources using this template. Font Awesome Free 5 classes such as “fa-home” may be used.';
$_lang['template_lock'] = 'Заблокувати шаблон для редагування';
$_lang['template_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Template.';
$_lang['template_locked_message'] = 'Цей шаблон заблоковано.';
$_lang['template_management_msg'] = 'Тут Ви можете вибрати шаблон, який бажаєте відредагувати.';
$_lang['template_name_desc'] = 'Ім\'я даного шаблону.';
$_lang['template_new'] = 'Створити шаблон';
$_lang['template_no_tv'] = 'Поки що TV не призначено цьому шаблону.';
$_lang['template_preview'] = 'Preview Image';
$_lang['template_preview_desc'] = 'Used to preview the layout of this Template when creating a new Resource. (Minimum size: 335 x 236)';
$_lang['template_preview_source'] = 'Preview Image Media Source';
$_lang['template_preview_source_desc'] = 'Sets the basePath for this Template’s Preview Image to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['template_properties'] = 'Параметри за замовчуванням';
$_lang['template_reset_all'] = 'Скинути шаблон для всіх сторінок, встановити шаблон за замовчуванням';
$_lang['template_reset_specific'] = 'Скинути лише \'%s\' сторінок';
$_lang['template_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template</em> as well as its content. The content must be HTML, either placed in the <em>Template Code</em> field below or in a static external file, and may include MODX tags. Note that changed or new templates won’t be visible in your site’s cached pages until the cache is emptied; however, you can use the preview function on a page to see the template in action.';
$_lang['template_tv_edit'] = 'Редагувати порядок TV ';
$_lang['template_tv_msg'] = ' <abbr title="Template Variables">TV</abbr> призначені для цього шаблону, перераховані нижче.';
$_lang['templates'] = 'Шаблони';
$_lang['tvt_err_nf'] = 'У TV немає доступу до вказаного шаблону.';
$_lang['tvt_err_remove'] = 'Сталася помилка при спробі видалення TV з шаблону.';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['template_desc_category'] = $_lang['template_category_desc'];
$_lang['template_desc_description'] = $_lang['template_description_desc'];
$_lang['template_desc_name'] = $_lang['template_name_desc'];
$_lang['template_lock_msg'] = $_lang['template_lock_desc'];

// --tabs
$_lang['template_msg'] = $_lang['template_tab_general_desc'];
