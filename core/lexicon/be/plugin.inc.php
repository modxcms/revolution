<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Падзея';
$_lang['events'] = 'Падзеі';
$_lang['plugin'] = 'Плагiн';
$_lang['plugin_add'] = 'Дадаць плагiн';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Канфігурацыя плагіна';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'Вы сапраўды жадаеце выдаліць гэты плагін?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'Вы сапраўды жадаеце дубляваць гэты плагін?';
$_lang['plugin_err_create'] = 'Адбылася памылка пры стварэнні плагіна.';
$_lang['plugin_err_ae'] = 'Плагін ужо існуе з назвай "[[+name]]".';
$_lang['plugin_err_invalid_name'] = 'Такое імя плагіна недапушчальна.';
$_lang['plugin_err_duplicate'] = 'Адбылася памылка пры спробе дубляваць плагін.';
$_lang['plugin_err_nf'] = 'Плагін не знойдзены!';
$_lang['plugin_err_ns'] = 'Плагін не пазначаны.';
$_lang['plugin_err_ns_name'] = 'Калi ласка, пазначце назву для плагіна.';
$_lang['plugin_err_remove'] = 'An error occurred while trying to delete the plugin.';
$_lang['plugin_err_save'] = 'Адбылася памылка пры захаванні плагіна.';
$_lang['plugin_event_err_duplicate'] = 'Адбылася памылка пры спробе дубляваць падзеі плагіна';
$_lang['plugin_event_err_nf'] = 'Падзея плагіна не знойдзена.';
$_lang['plugin_event_err_ns'] = 'Падзея плагіна не пазначана.';
$_lang['plugin_event_err_remove'] = 'An error occurred while trying to delete the plugin event.';
$_lang['plugin_event_err_save'] = 'Адбылася памылка пры захаванні падзеі плагіна.';
$_lang['plugin_event_msg'] = 'Выберыце падзеі, якія вы жадаеце, каб гэты плагін праслухоўваў.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Are you sure you want to delete this plugin from this event?';
$_lang['plugin_lock'] = 'Плагін заблакаваны для рэдагавання';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'Гэты плагін заблакаваны.';
$_lang['plugin_management_msg'] = 'Тут вы можаце выбраць які плагін вы жадаеце рэдагаваць.';
$_lang['plugin_name_desc'] = 'Імя гэтага плагіна.';
$_lang['plugin_new'] = 'Create Plugin';
$_lang['plugin_priority'] = 'Змяніць парадак выканання плагінаў (сартаваны па падзеі)';
$_lang['plugin_properties'] = 'Налады плагіна';
$_lang['plugin_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Plugin</em> as well as its content. The content must be PHP, either placed in the <em>Plugin Code</em> field below or in a static external file. The PHP code entered runs in response to one or more MODX System Events that you specify.';
$_lang['plugins'] = 'Плагiны';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];
