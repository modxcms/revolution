<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Zdarzenie';
$_lang['events'] = 'Zdarzenia';
$_lang['plugin'] = 'Wtyczka';
$_lang['plugin_add'] = 'Dodaj Plugin';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Konfiguracja wtyczki';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'Czy na pewno chcesz usunąć wtyczkę?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'Are you sure you want to duplicate this plugin?';
$_lang['plugin_err_create'] = 'An error occurred while creating the plugin.';
$_lang['plugin_err_ae'] = 'Wtyczka o nazwie: "[[+name]]", już istnieje.';
$_lang['plugin_err_invalid_name'] = 'Niepoprawna nazwa wtyczki.';
$_lang['plugin_err_duplicate'] = 'An error occurred while trying to duplicate the plugin.';
$_lang['plugin_err_nf'] = 'Nie znaleziono wtyczki!';
$_lang['plugin_err_ns'] = 'Pluginu nie określono.';
$_lang['plugin_err_ns_name'] = 'Proszę podać nazwę wtyczki.';
$_lang['plugin_err_remove'] = 'An error occurred while trying to delete the plugin.';
$_lang['plugin_err_save'] = 'An error occurred while saving the plugin.';
$_lang['plugin_event_err_duplicate'] = 'Wystąpił błąd podczas próby powielenia pluginy wydarzeń';
$_lang['plugin_event_err_nf'] = 'Plugin event not found.';
$_lang['plugin_event_err_ns'] = 'Plugin event not specified.';
$_lang['plugin_event_err_remove'] = 'An error occurred while trying to delete the plugin event.';
$_lang['plugin_event_err_save'] = 'An error occurred while saving the plugin event.';
$_lang['plugin_event_msg'] = 'Select the events that you would like this plugin to listen to.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Are you sure you want to delete this plugin from this event?';
$_lang['plugin_lock'] = 'Plugin locked for editing';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'Ta wtyczka jest zablokowana.';
$_lang['plugin_management_msg'] = 'Tutaj możesz wybrać, którą wtyczkę chcesz edytować.';
$_lang['plugin_name_desc'] = 'Nazwa wtyczki.';
$_lang['plugin_new'] = 'Create Plugin';
$_lang['plugin_priority'] = 'Edit Plugin Execution Order by Event';
$_lang['plugin_properties'] = 'Właściwości wtyczki';
$_lang['plugin_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Plugin</em> as well as its content. The content must be PHP, either placed in the <em>Plugin Code</em> field below or in a static external file. The PHP code entered runs in response to one or more MODX System Events that you specify.';
$_lang['plugins'] = 'Pluginy';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];
