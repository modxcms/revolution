<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Ereignis';
$_lang['events'] = 'Ereignisse';
$_lang['plugin'] = 'Plugin';
$_lang['plugin_add'] = 'Plugin hinzufügen';
$_lang['plugin_category_desc'] = 'Ermöglicht die Gruppierung von Plugins innerhalb des Elementbaums.';
$_lang['plugin_code'] = 'Plugin-Code (PHP)';
$_lang['plugin_config'] = 'Plugin-Konfiguration';
$_lang['plugin_description_desc'] = 'Benutzungshinweise für dieses Plugin in Suchergebnissen und als Tooltip im Elementbaum anzeigen.';
$_lang['plugin_delete_confirm'] = 'Sind Sie sicher, dass Sie dieses Plugin löschen möchten?';
$_lang['plugin_disabled'] = 'Plugin deaktivieren';
$_lang['plugin_disabled_msg'] = 'Wenn dieses Plugin deaktiviert ist, reagiert es nicht auf Ereignisse.';
$_lang['plugin_duplicate_confirm'] = 'Sind Sie sicher, dass Sie dieses Plugin duplizieren möchten?';
$_lang['plugin_err_create'] = 'Beim Erstellen des Plugins ist ein Fehler aufgetreten.';
$_lang['plugin_err_ae'] = 'Ein Plugin mit dem Namen "[[+name]]" existiert bereits.';
$_lang['plugin_err_invalid_name'] = 'Der Plugin-Name ist ungültig.';
$_lang['plugin_err_duplicate'] = 'Beim Versuch, das Plugin zu duplizieren, ist ein Fehler aufgetreten.';
$_lang['plugin_err_nf'] = 'Plugin nicht gefunden!';
$_lang['plugin_err_ns'] = 'Plugin nicht angegeben.';
$_lang['plugin_err_ns_name'] = 'Bitte geben Sie einen Namen für das Plugin an.';
$_lang['plugin_err_remove'] = 'Beim Versuch, das Plugin zu löschen, ist ein Fehler aufgetreten.';
$_lang['plugin_err_save'] = 'Beim Speichern des Plugins ist ein Fehler aufgetreten.';
$_lang['plugin_event_err_duplicate'] = 'Beim Versuch, das Plugin-Ereignis zu duplizieren, ist ein Fehler aufgetreten.';
$_lang['plugin_event_err_nf'] = 'Plugin-Ereignis nicht gefunden.';
$_lang['plugin_event_err_ns'] = 'Plugin-Ereignis nicht angegeben.';
$_lang['plugin_event_err_remove'] = 'Beim Versuch, das Plugin-Ereignis zu löschen, ist ein Fehler aufgetreten.';
$_lang['plugin_event_err_save'] = 'Beim Speichern des Plugin-Ereignisses ist ein Fehler aufgetreten.';
$_lang['plugin_event_msg'] = 'Wählen Sie die Ereignisse, auf die dieses Plugin reagieren soll.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Sind Sie sicher, dass Sie dieses Plugin aus diesem Ereignis löschen möchten?';
$_lang['plugin_lock'] = 'Plugin für die Bearbeitung gesperrt';
$_lang['plugin_lock_desc'] = 'Nur Benutzer mit „edit_locked“ Zugriffsberechtigung können dieses Plugin bearbeiten.';
$_lang['plugin_locked_message'] = 'Das Plugin ist gesperrt.';
$_lang['plugin_management_msg'] = 'Hier können Sie wählen, welches Plugin Sie bearbeiten möchten.';
$_lang['plugin_name_desc'] = 'Der Name dieses Plugins.';
$_lang['plugin_new'] = 'Plugin erstellen';
$_lang['plugin_priority'] = 'Legen Sie die Plugin-Ausführungsreihenfolge nach Ereignis fest';
$_lang['plugin_properties'] = 'Plugin-Einstellungen';
$_lang['plugin_tab_general_desc'] = 'Hier können Sie die grundlegenden Attribute für dieses <em>Plugin</em> sowie dessen Inhalt eingeben. Der Inhalt muss PHP sein, entweder im Feld <em>Plugin Code</em> oder in einer statischen externen Datei. Der eingegebene PHP-Code wird als Reaktion auf ein oder mehrere von Ihnen angegebene MODX-Systemereignisse ausgeführt.';
$_lang['plugins'] = 'Plugins';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];
