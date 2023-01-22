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
$_lang['plugin_add'] = 'Tilføj Plugin';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Plugin-konfiguration';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'Er du sikker på du vil slette dette plugin?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'Er du sikker på du vil kopiere dette plugin?';
$_lang['plugin_err_create'] = 'Der opstod en fejl under oprettelse af plugin\'et.';
$_lang['plugin_err_ae'] = 'Der findes allerede et plugin med navnet "[[+name]]".';
$_lang['plugin_err_invalid_name'] = 'Pluginnavnet er ugyldigt.';
$_lang['plugin_err_duplicate'] = 'An error occurred while trying to duplicate the plugin.';
$_lang['plugin_err_nf'] = 'Plugin blev ikke fundet!';
$_lang['plugin_err_ns'] = 'Plugin er ikke angivet.';
$_lang['plugin_err_ns_name'] = 'Angiv et navn for dette plugin.';
$_lang['plugin_err_remove'] = 'An error occurred while trying to delete the plugin.';
$_lang['plugin_err_save'] = 'Der opstod en fejl da plugin\'et blev forsøgt gemt.';
$_lang['plugin_event_err_duplicate'] = 'Der opstod en fejl under kopieringen af plugin-events';
$_lang['plugin_event_err_nf'] = 'Plugin-event blev ikke fundet.';
$_lang['plugin_event_err_ns'] = 'Plugin-event ikke angivet.';
$_lang['plugin_event_err_remove'] = 'An error occurred while trying to delete the plugin event.';
$_lang['plugin_event_err_save'] = 'Der opstod en fejl da plugin-event blev forsøgt gemt.';
$_lang['plugin_event_msg'] = 'Vælg de events, som du gerne vil have dette plugin til at reagere på.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Are you sure you want to delete this plugin from this event?';
$_lang['plugin_lock'] = 'Plugin locked for editing';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'Dette plugin er låst.';
$_lang['plugin_management_msg'] = 'Her kan du vælge hvilket plugin du ønsker at redigere.';
$_lang['plugin_name_desc'] = 'Navnet på dette plugin.';
$_lang['plugin_new'] = 'Create Plugin';
$_lang['plugin_priority'] = 'Rediger kørselsrækkefølge af plugins efter events';
$_lang['plugin_properties'] = 'Pluginegenskaber';
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
