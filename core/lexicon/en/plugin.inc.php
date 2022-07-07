<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Event';
$_lang['events'] = 'Events';
$_lang['plugin'] = 'Plugin';
$_lang['plugin_add'] = 'Add Plugin';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Plugin configuration';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'Are you sure you want to delete this plugin?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'Are you sure you want to duplicate this plugin?';
$_lang['plugin_err_create'] = 'An error occurred while creating the plugin.';
$_lang['plugin_err_ae'] = 'A plugin already exists with the name "[[+name]]".';
$_lang['plugin_err_invalid_name'] = 'Plugin name is invalid.';
$_lang['plugin_err_duplicate'] = 'An error occurred while trying to duplicate the plugin.';
$_lang['plugin_err_nf'] = 'Plugin not found!';
$_lang['plugin_err_ns'] = 'Plugin not specified.';
$_lang['plugin_err_ns_name'] = 'Please specify a name for the plugin.';
$_lang['plugin_err_remove'] = 'An error occurred while trying to delete the plugin.';
$_lang['plugin_err_save'] = 'An error occurred while saving the plugin.';
$_lang['plugin_event_err_duplicate'] = 'An error occurred while trying to duplicate the plugin events';
$_lang['plugin_event_err_nf'] = 'Plugin event not found.';
$_lang['plugin_event_err_ns'] = 'Plugin event not specified.';
$_lang['plugin_event_err_remove'] = 'An error occurred while trying to delete the plugin event.';
$_lang['plugin_event_err_save'] = 'An error occurred while saving the plugin event.';
$_lang['plugin_event_msg'] = 'Select the events that you would like this plugin to listen to.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Are you sure you want to delete this plugin from this event?';
$_lang['plugin_lock'] = 'Plugin locked for editing';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'This plugin is locked.';
$_lang['plugin_management_msg'] = 'Here you can choose which plugin you wish to edit.';
$_lang['plugin_name_desc'] = 'The name of this Plugin.';
$_lang['plugin_new'] = 'Create Plugin';
$_lang['plugin_priority'] = 'Edit Plugin Execution Order by Event';
$_lang['plugin_properties'] = 'Plugin Properties';
$_lang['plugin_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Plugin</em> as well as its content. The content must be PHP, either placed in the <em>Plugin Code</em> field below or in a static external file. The PHP code entered runs in response to one or more MODX System Events that you specify.';
$_lang['plugins'] = 'Plugins';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];
