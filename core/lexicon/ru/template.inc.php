<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'Доступ';
$_lang['filter_by_category'] = 'Фильтр по категории...';
$_lang['rank'] = 'Сортировка';
$_lang['template'] = 'Шаблон';
$_lang['template_assignedtv_tab'] = 'Назначенные TV';
$_lang['template_category_desc'] = 'Use to group Templates within the Elements tree.';
$_lang['template_code'] = 'Template Code (HTML)';
$_lang['template_delete_confirm'] = 'Вы уверены, что хотите удалить этот шаблон?';
$_lang['template_description_desc'] = 'Usage information for this Template shown in search results and as a tooltip in the Elements tree.';
$_lang['template_duplicate_confirm'] = 'Вы уверены, что хотите сделать копию этого шаблона?';
$_lang['template_edit_tab'] = 'Редактировать шаблон';
$_lang['template_empty'] = '(пустой шаблон)';
$_lang['template_err_default_template'] = 'Этот шаблон выбран в качестве шаблона по умолчанию. Пожалуйста, выберите другой шаблон по умолчанию в настройках MODX перед удалением этого шаблона.<br />';
$_lang['template_err_delete'] = 'Произошла ошибка при попытке удалить шаблон.';
$_lang['template_err_duplicate'] = 'Произошла ошибка при копировании шаблона.';
$_lang['template_err_ae'] = 'Шаблон с названием «[[+name]]» уже существует.';
$_lang['template_err_in_use'] = 'Этот шаблон используется. Пожалуйста, установите ресурсам, использующим этот шаблон, другой шаблон. Ресурсы, использующие этот шаблон:<br />';
$_lang['template_err_invalid_name'] = 'Название шаблона недопустимо.';
$_lang['template_err_locked'] = 'Шаблон заблокирован для редактирования.';
$_lang['template_err_nf'] = 'Шаблон не найден!';
$_lang['template_err_ns'] = 'Шаблон не указан.';
$_lang['template_err_ns_name'] = 'Пожалуйста, укажите название шаблона.';
$_lang['template_err_remove'] = 'Произошла ошибка при попытке удалить шаблон.';
$_lang['template_err_save'] = 'Произошла ошибка при сохранении шаблона.';
$_lang['template_icon'] = 'Manager Icon Class';
$_lang['template_icon_desc'] = 'A CSS class to assign an icon (shown in the document trees) for all resources using this template. Font Awesome Free 5 classes such as “fa-home” may be used.';
$_lang['template_lock'] = 'Блокировка шаблона для редактирования';
$_lang['template_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Template.';
$_lang['template_locked_message'] = 'Этот шаблон заблокирован.';
$_lang['template_management_msg'] = 'Здесь вы можете выбрать шаблон, который вы хотели бы редактировать.';
$_lang['template_name_desc'] = 'Название шаблона.';
$_lang['template_new'] = 'Создать шаблон';
$_lang['template_no_tv'] = 'Для этого шаблона ещё не назначены TV.';
$_lang['template_preview'] = 'Preview Image';
$_lang['template_preview_desc'] = 'Used to preview the layout of this Template when creating a new Resource. (Minimum size: 335 x 236)';
$_lang['template_preview_source'] = 'Preview Image Media Source';
$_lang['template_preview_source_desc'] = 'Sets the basePath for this Template’s Preview Image to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['template_properties'] = 'Параметры по умолчанию';
$_lang['template_reset_all'] = 'Сброс шаблона всех страниц, установить шаблон по умолчанию';
$_lang['template_reset_specific'] = 'Сбросить только \'%s\' страниц';
$_lang['template_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template</em> as well as its content. The content must be HTML, either placed in the <em>Template Code</em> field below or in a static external file, and may include MODX tags. Note that changed or new templates won’t be visible in your site’s cached pages until the cache is emptied; however, you can use the preview function on a page to see the template in action.';
$_lang['template_title'] = 'Создать/редактировать шаблон';
$_lang['template_tv_edit'] = 'Редактировать порядок TV';
$_lang['template_tv_msg'] = ' <abbr title="Дополнительные поля">TV</abbr>, назначенные этому шаблону.';
$_lang['template_untitled'] = 'Безымянный шаблон';
$_lang['templates'] = 'Шаблоны';
$_lang['tvt_err_nf'] = 'TV не имеет доступа к указанному шаблону.';
$_lang['tvt_err_remove'] = 'Произошла ошибка при попытке удалить TV из шаблона.';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['template_desc_category'] = $_lang['template_category_desc'];
$_lang['template_desc_description'] = $_lang['template_description_desc'];
$_lang['template_desc_name'] = $_lang['template_name_desc'];
$_lang['template_lock_msg'] = $_lang['template_lock_desc'];

// --tabs
$_lang['template_msg'] = $_lang['template_tab_general_desc'];
