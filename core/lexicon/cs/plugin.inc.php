<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Událost';
$_lang['events'] = 'Události';
$_lang['plugin'] = 'Plugin';
$_lang['plugin_add'] = 'Vytvořit plugin';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Nastavení pluginu';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'Opravdu chcete odstranit tento plugin?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'Opravdu chcete zkopírovat tento plugin?';
$_lang['plugin_err_create'] = 'Nastala chyba při vytváření pluginu.';
$_lang['plugin_err_ae'] = 'Plugin s názvem "[[+name]]" již existuje.';
$_lang['plugin_err_invalid_name'] = 'Název pluginu není platný.';
$_lang['plugin_err_duplicate'] = 'Nastala chyba při pokusu zkopírovat plugin.';
$_lang['plugin_err_nf'] = 'Plugin nenalezen!';
$_lang['plugin_err_ns'] = 'Nespecifikovaný plugin.';
$_lang['plugin_err_ns_name'] = 'Zadejte název pluginu.';
$_lang['plugin_err_remove'] = 'An error occurred while trying to delete the plugin.';
$_lang['plugin_err_save'] = 'Nastala chyba při ukládání pluginu.';
$_lang['plugin_event_err_duplicate'] = 'Nastala chyba při kopírování událostí pluginu';
$_lang['plugin_event_err_nf'] = 'Událost pluginu nenalezena.';
$_lang['plugin_event_err_ns'] = 'Nespecifikovaná událost pluginu.';
$_lang['plugin_event_err_remove'] = 'An error occurred while trying to delete the plugin event.';
$_lang['plugin_event_err_save'] = 'Nastala chyba při ukládání události pluginu.';
$_lang['plugin_event_msg'] = 'Vyberte události, při kterých má být plugin proveden.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Are you sure you want to delete this plugin from this event?';
$_lang['plugin_lock'] = 'Plugin je uzamčen pro úpravy';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'Plugin je uzamčen.';
$_lang['plugin_management_msg'] = 'Vyberte, který plugin chcete upravovat.';
$_lang['plugin_name_desc'] = 'Název pluginu.';
$_lang['plugin_new'] = 'Create Plugin';
$_lang['plugin_priority'] = 'Upravit pořadí spouštěných pluginů v daných událostech';
$_lang['plugin_properties'] = 'Vlastnosti pluginu';
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
