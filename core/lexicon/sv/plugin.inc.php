<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Händelse';
$_lang['events'] = 'Händelser';
$_lang['plugin'] = 'Plugin';
$_lang['plugin_add'] = 'Lägg till plugin';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Konfigurera plugin';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'Är du säker på att du vill ta bort denna plugin?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'Är du säker på att du vill duplicera denna plugin?';
$_lang['plugin_err_create'] = 'Ett fel inträffade när pluginen skulle skapas.';
$_lang['plugin_err_ae'] = 'Det finns redan en plugin med namnet "[[+name]]".';
$_lang['plugin_err_invalid_name'] = 'Pluginens namn är ogiltigt.';
$_lang['plugin_err_duplicate'] = 'Ett fel inträffade när pluginen skulle dupliceras.';
$_lang['plugin_err_nf'] = 'Pluginen kunde inte hittas!';
$_lang['plugin_err_ns'] = 'Ingen plugin angiven.';
$_lang['plugin_err_ns_name'] = 'Ange ett namn på pluginen.';
$_lang['plugin_err_remove'] = 'Ett fel inträffade när pluginen skulle tas bort.';
$_lang['plugin_err_save'] = 'Ett fel inträffade när pluginen skulle sparas.';
$_lang['plugin_event_err_duplicate'] = 'Ett fel inträffade när pluginens händelser skulle dupliceras.';
$_lang['plugin_event_err_nf'] = 'Pluginhändelsen kunde inte hittas.';
$_lang['plugin_event_err_ns'] = 'Ingen pluginhändelse angiven.';
$_lang['plugin_event_err_remove'] = 'Ett fel inträffade när pluginens händelse skulle tas bort.';
$_lang['plugin_event_err_save'] = 'Ett fel inträffade när pluginhändelsen skulle sparas.';
$_lang['plugin_event_msg'] = 'Välj de händelser som pluginen ska lyssna till.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Är du säker på att du vill ta bort denna plugin från denna händelse?';
$_lang['plugin_lock'] = 'Plugin:en låst för redigering';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'Denna plugin är låst.';
$_lang['plugin_management_msg'] = 'Här kan du skapa en ny plugin eller välja en redan befintlig för redigering.';
$_lang['plugin_name_desc'] = 'Pluginens namn.';
$_lang['plugin_new'] = 'Skapa plugin';
$_lang['plugin_priority'] = 'Redigera körordningen för plugins efter händelse';
$_lang['plugin_properties'] = 'Pluginens egenskaper';
$_lang['plugin_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Plugin</em> as well as its content. The content must be PHP, either placed in the <em>Plugin Code</em> field below or in a static external file. The PHP code entered runs in response to one or more MODX System Events that you specify.';
$_lang['plugin_title'] = 'Skapa/redigera plugin';
$_lang['plugin_untitled'] = 'Namnlös plugin';
$_lang['plugins'] = 'Plugins';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];
