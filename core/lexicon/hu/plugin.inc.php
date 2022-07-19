<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Esemény';
$_lang['events'] = 'Események';
$_lang['plugin'] = 'Beépülő';
$_lang['plugin_add'] = 'Beépülő hozzáadása';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Beépülő beállításai';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'Biztosan törölni szeretné ezt a beépülőt?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'Biztosan kettőzni szeretné ezt a beépülőt?';
$_lang['plugin_err_create'] = 'Hiba történt a beépülő létrehozásakor.';
$_lang['plugin_err_ae'] = 'Már létezik beépülő "[[+name]]" néven.';
$_lang['plugin_err_invalid_name'] = 'A beépülő neve érvénytelen.';
$_lang['plugin_err_duplicate'] = 'Hiba történt a beépülő kettőzésekor.';
$_lang['plugin_err_nf'] = 'A beépülő nem található!';
$_lang['plugin_err_ns'] = 'A beépülő nincs megadva.';
$_lang['plugin_err_ns_name'] = 'Kérjük, adja meg a beépülő nevét.';
$_lang['plugin_err_remove'] = 'Hiba történt a beépülő törlésekor.';
$_lang['plugin_err_save'] = 'Hiba történt a beépülő mentésekor.';
$_lang['plugin_event_err_duplicate'] = 'Hiba történt a beépülő kettőzésekor.';
$_lang['plugin_event_err_nf'] = 'A beépülő eseménye nem található.';
$_lang['plugin_event_err_ns'] = 'A beépülő eseménye nincs megadva.';
$_lang['plugin_event_err_remove'] = 'Hiba történt a beépülő esemény törlésekor.';
$_lang['plugin_event_err_save'] = 'Hiba történt a beépülő eseményének mentésekor.';
$_lang['plugin_event_msg'] = 'Válassza ki a beépülő által figyelendő eseményeket.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Biztosan törli ezt a beépülőt ebből az eseményből?';
$_lang['plugin_lock'] = 'A beépülő szerkesztésre zárolva';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'Ez a beépülő zárolt.';
$_lang['plugin_management_msg'] = 'Itt választhatja ki a módosítani kívánt beépülőt.';
$_lang['plugin_name_desc'] = 'Beépülő neve.';
$_lang['plugin_new'] = 'Beépülő létrehozása';
$_lang['plugin_priority'] = 'Beépülő végrehajtási sorának szerkesztése esemény alapján';
$_lang['plugin_properties'] = 'Beépülő tulajdonságai';
$_lang['plugin_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Plugin</em> as well as its content. The content must be PHP, either placed in the <em>Plugin Code</em> field below or in a static external file. The PHP code entered runs in response to one or more MODX System Events that you specify.';
$_lang['plugins'] = 'Beépülők';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];
