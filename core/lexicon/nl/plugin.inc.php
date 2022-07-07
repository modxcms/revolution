<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Gebeurtenis';
$_lang['events'] = 'Gebeurtenissen';
$_lang['plugin'] = 'Plugin';
$_lang['plugin_add'] = 'Nieuwe Plugin';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Plugin configuratie';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'Weet je zeker dat je deze plugin wilt verwijderen?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'Weet je zeker dat je deze plugin wilt dupliceren?';
$_lang['plugin_err_create'] = 'Er is een fout opgetreden bij het aanmaken van deze plugin.';
$_lang['plugin_err_ae'] = 'Er bestaat al een plugin met de naam "[[+name]]".';
$_lang['plugin_err_invalid_name'] = 'Naam van deze plugin is ongeldig.';
$_lang['plugin_err_duplicate'] = 'Er is een fout opgetreden bij het dupliceren van de plugin.';
$_lang['plugin_err_nf'] = 'Plugin niet gevonden!';
$_lang['plugin_err_ns'] = 'Plugin niet omschreven.';
$_lang['plugin_err_ns_name'] = 'Geef aub een omschrijving voor deze plugin.';
$_lang['plugin_err_remove'] = 'An error occurred while trying to delete the plugin.';
$_lang['plugin_err_save'] = 'Er is een fout opgetreden tijdens het opslaan van deze plugin.';
$_lang['plugin_event_err_duplicate'] = 'Er is een fout opgetreden bij een poging de gebeurtenissen van deze plugin te dupliceren';
$_lang['plugin_event_err_nf'] = 'Plugin gebeurtenis niet gevonden.';
$_lang['plugin_event_err_ns'] = 'Plugin gebeurtenis niet omschreven.';
$_lang['plugin_event_err_remove'] = 'An error occurred while trying to delete the plugin event.';
$_lang['plugin_event_err_save'] = 'Er is een fout opgetreden tijdens het opslaan van een plugin gebeurtenis.';
$_lang['plugin_event_msg'] = 'Selecteer de gebeurtenissen waarvan je wilt dat deze plugin naar luistert.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Are you sure you want to delete this plugin from this event?';
$_lang['plugin_lock'] = 'Plugin locked for editing';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'Deze plugin is geblokkeerd.';
$_lang['plugin_management_msg'] = 'Hier kun je selecteren welke plugin je wilt bewerken.';
$_lang['plugin_name_desc'] = 'De naam van de Plugin.';
$_lang['plugin_new'] = 'Create Plugin';
$_lang['plugin_priority'] = 'Wijzig uitvoer volgorde van Plugin voor event';
$_lang['plugin_properties'] = 'Plugin eigenschappen';
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
