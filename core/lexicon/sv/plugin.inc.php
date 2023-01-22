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
$_lang['plugin_category_desc'] = 'Använd för att gruppera plugins i elementträdet.';
$_lang['plugin_code'] = 'Plugin-kod (php)';
$_lang['plugin_config'] = 'Konfigurera plugin';
$_lang['plugin_description_desc'] = 'Användningsinformation för denna plugin som visas i sökresultat och som ett verktygstips i elementträdet.';
$_lang['plugin_delete_confirm'] = 'Är du säker på att du vill ta bort denna plugin?';
$_lang['plugin_disabled'] = 'Inaktivera plugin';
$_lang['plugin_disabled_msg'] = 'När denna plugin inaktiveras kommer den inte att svara på händelser.';
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
$_lang['plugin_lock_desc'] = 'Endast användare med “edit_locked”-behörighet kan redigera denna plugin.';
$_lang['plugin_locked_message'] = 'Denna plugin är låst.';
$_lang['plugin_management_msg'] = 'Här kan du skapa en ny plugin eller välja en redan befintlig för redigering.';
$_lang['plugin_name_desc'] = 'Pluginens namn.';
$_lang['plugin_new'] = 'Skapa plugin';
$_lang['plugin_priority'] = 'Redigera körordningen för plugins efter händelse';
$_lang['plugin_properties'] = 'Pluginens egenskaper';
$_lang['plugin_tab_general_desc'] = 'Här kan du ange grundläggande attribut för denna <em>plugin</em> samt dess innehåll. Innehållet måste vara PHP, antingen placerat i fältet <em>Plugin-kod</em> nedan eller i en statisk extern fil. PHP-koden som anges körs som svar på en eller flera MODX-systemhändelser som du anger.';
$_lang['plugins'] = 'Plugins';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];
