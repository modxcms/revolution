<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'Доступ';
$_lang['filter_by_category'] = 'Фільтраваць па катэгорыi…';
$_lang['rank'] = 'Ранг';
$_lang['template'] = 'Шаблон';
$_lang['template_assignedtv_tab'] = 'Assigned TVs';
$_lang['template_category_desc'] = 'Use to group Templates within the Elements tree.';
$_lang['template_code'] = 'Template Code (HTML)';
$_lang['template_delete_confirm'] = 'Вы сапраўды жадаеце выдаліць гэты шаблон?';
$_lang['template_description_desc'] = 'Usage information for this Template shown in search results and as a tooltip in the Elements tree.';
$_lang['template_duplicate_confirm'] = 'Вы сапраўды жадаеце дубляваць гэты шаблон?';
$_lang['template_edit_tab'] = 'Рэдагаваць шаблон';
$_lang['template_empty'] = '(пуста)';
$_lang['template_err_default_template'] = 'Гэты шаблон зададзены як шаблон па змаўчанні. Калі ласка, выберыце іншы шаблон па змаўчанні ў канфігурацыі MODX перад выдаленнем гэтага шаблону.<br />';
$_lang['template_err_delete'] = 'An error occurred while trying to delete the template.';
$_lang['template_err_duplicate'] = 'Адбылася памылка пры дубляванні шаблону.';
$_lang['template_err_ae'] = 'Шаблон ужо існуе з назвай "[[+name]]".';
$_lang['template_err_in_use'] = 'Гэты шаблон выкарыстоўваецца. Калі ласка, змяніце шаблон на другі для дакументаў, якія выкарыстоўваюць гэты шаблон. Спіс дакументаў, якія выкарыстоўваюць шаблон: <br />';
$_lang['template_err_invalid_name'] = 'Template name is invalid.';
$_lang['template_err_locked'] = 'Шаблон заблакаваны для рэдагавання.';
$_lang['template_err_nf'] = 'Шаблон не знойдзены!';
$_lang['template_err_ns'] = 'Шаблон не пазначаны.';
$_lang['template_err_ns_name'] = 'Калі ласка, пазначце назву для шаблону.';
$_lang['template_err_remove'] = 'An error occurred while trying to delete the template.';
$_lang['template_err_save'] = 'Адбылася памылка пры захаванні шаблону.';
$_lang['template_icon'] = 'Manager Icon Class';
$_lang['template_icon_desc'] = 'A CSS class to assign an icon (shown in the document trees) for all resources using this template. Font Awesome Free 5 classes such as “fa-home” may be used.';
$_lang['template_lock'] = 'Заблакаваць шаблон для рэдагавання';
$_lang['template_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Template.';
$_lang['template_locked_message'] = 'Гэты шаблон заблакаваны.';
$_lang['template_management_msg'] = 'Тут вы можаце выбраць які шаблон вы жадаеце рэдагаваць.';
$_lang['template_name_desc'] = 'Назва гэтага шаблону.';
$_lang['template_new'] = 'Create Template';
$_lang['template_no_tv'] = 'No TVs have been assigned to this template yet.';
$_lang['template_preview'] = 'Preview Image';
$_lang['template_preview_desc'] = 'Used to preview the layout of this Template when creating a new Resource. (Minimum size: 335 x 236)';
$_lang['template_preview_source'] = 'Preview Image Media Source';
$_lang['template_preview_source_desc'] = 'Sets the basePath for this Template’s Preview Image to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['template_properties'] = 'Параметры па змаўчанні';
$_lang['template_reset_all'] = 'Скінуць усе старонкі да выкарыстання шаблону па змаўчанні';
$_lang['template_reset_specific'] = 'Скінуць только \'%s\' старонак';
$_lang['template_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template</em> as well as its content. The content must be HTML, either placed in the <em>Template Code</em> field below or in a static external file, and may include MODX tags. Note that changed or new templates won’t be visible in your site’s cached pages until the cache is emptied; however, you can use the preview function on a page to see the template in action.';
$_lang['template_tv_edit'] = 'Edit the sort order of the TVs';
$_lang['template_tv_msg'] = 'The <abbr title="Template Variables">TVs</abbr> assigned to this template are listed below.';
$_lang['templates'] = 'Шаблоны';
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
