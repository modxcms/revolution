<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Събитие';
$_lang['events'] = 'Събития';
$_lang['plugin'] = 'Плъгин';
$_lang['plugin_add'] = 'Добави плъгин';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Плъгин конфигурация';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'Сигурни ли сте, че искате да изтриете този плъгин?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'Сигурни ли сте, че искате да дублирате този плъгин?';
$_lang['plugin_err_create'] = 'Възникна грешка при създаването на плъгина.';
$_lang['plugin_err_ae'] = 'Вече съществува плъгин с името "[[+name]]".';
$_lang['plugin_err_invalid_name'] = 'Името на плъгина не е валидно.';
$_lang['plugin_err_duplicate'] = 'An error occurred while trying to duplicate the plugin.';
$_lang['plugin_err_nf'] = 'Не е намерен плъгин!';
$_lang['plugin_err_ns'] = 'Не е зададен плъгин.';
$_lang['plugin_err_ns_name'] = 'Моля задайте име на плъгина.';
$_lang['plugin_err_remove'] = 'An error occurred while trying to delete the plugin.';
$_lang['plugin_err_save'] = 'Възникна грешка при опита за записване на плъгина.';
$_lang['plugin_event_err_duplicate'] = 'Възникна грешка при опита за дублиране на плъгин събитията';
$_lang['plugin_event_err_nf'] = 'Не е намерено плъгин събитие.';
$_lang['plugin_event_err_ns'] = 'Не е зададено плъгин събитие.';
$_lang['plugin_event_err_remove'] = 'An error occurred while trying to delete the plugin event.';
$_lang['plugin_event_err_save'] = 'Възникна грешка при опита за записване на плъгин събитието.';
$_lang['plugin_event_msg'] = 'Изберете събитията, на които искате да реагира този плъгин.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Are you sure you want to delete this plugin from this event?';
$_lang['plugin_lock'] = 'Plugin locked for editing';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'Плъгина е заключен.';
$_lang['plugin_management_msg'] = 'Тук можете да изберете кой плъгин искате да редактирате.';
$_lang['plugin_name_desc'] = 'Името на този плъгин.';
$_lang['plugin_new'] = 'Create Plugin';
$_lang['plugin_priority'] = 'Редактирай реда на изпълнението на плъгина по събития';
$_lang['plugin_properties'] = 'Свойства на плъгина';
$_lang['plugin_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Plugin</em> as well as its content. The content must be PHP, either placed in the <em>Plugin Code</em> field below or in a static external file. The PHP code entered runs in response to one or more MODX System Events that you specify.';
$_lang['plugins'] = 'Плъгини';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];
