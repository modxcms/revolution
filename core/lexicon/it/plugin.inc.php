<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Evento';
$_lang['events'] = 'Eventi';
$_lang['plugin'] = 'Plugin';
$_lang['plugin_add'] = 'Aggiungi Plugin';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Configurazione Plugin';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'Sei sicuro di voler eliminare questo plugin?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'Sei sicuro di voler duplicare questo plugin?';
$_lang['plugin_err_create'] = 'Si è verificato un errore durante la creazione del plugin.';
$_lang['plugin_err_ae'] = 'Esiste di già un plugin con  nome "[[+name]]".';
$_lang['plugin_err_invalid_name'] = 'Il nome del Plugin non è valido.';
$_lang['plugin_err_duplicate'] = 'Si è verificato un errore durante il tentativo di duplicare il plugin.';
$_lang['plugin_err_nf'] = 'Plugin non trovato!';
$_lang['plugin_err_ns'] = 'Plugin non specificato.';
$_lang['plugin_err_ns_name'] = 'Specifica un nome per il plugin.';
$_lang['plugin_err_remove'] = 'Si è verificato un errore provando a eliminare il plugin.';
$_lang['plugin_err_save'] = 'Si è verificato un errore durante il salvataggio del plugin.';
$_lang['plugin_event_err_duplicate'] = 'Si è verificato un errore durante il tentativo di duplicazione degli eventi del plugin';
$_lang['plugin_event_err_nf'] = 'Evento del Plugin non trovato.';
$_lang['plugin_event_err_ns'] = 'Evento del Plugin non specificato.';
$_lang['plugin_event_err_remove'] = 'Si è verificato un errore provando a eliminare l\'evento del plugin.';
$_lang['plugin_event_err_save'] = 'Si è verificato un errore durante il salvataggio dell\'evento del plugin.';
$_lang['plugin_event_msg'] = 'Seleziona gli eventi che vorresti far "ascoltare" a questo plugin.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Sei sicuro di voler eliminare questo plugin da questo evento?';
$_lang['plugin_lock'] = 'Plugin bloccato per la modifica';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'Questo plugin è bloccato.';
$_lang['plugin_management_msg'] = 'Qui puoi scegliere quale plugin vorresti modificare.';
$_lang['plugin_name_desc'] = 'Il nome di questo Plugin.';
$_lang['plugin_new'] = 'Crea Plugin';
$_lang['plugin_priority'] = 'Modifica Ordine Esecuzione Plugin per evento.';
$_lang['plugin_properties'] = 'Proprieta Plugin';
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
