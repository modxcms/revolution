<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Eventi';
$_lang['system_event'] = 'Eventi di Sistema';
$_lang['system_events'] = 'Eventi di Sistema';
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'Ricerca per nome dell\'evento';
$_lang['system_events.create'] = 'Create Event';
$_lang['system_events.name_desc'] = 'Il nome dell\'evento. Che dovrebbe essere usato in una chiamata: &dollar;modx->invokeEvent(name, properties) .';
$_lang['system_events.groupname'] = 'Guppo';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'Plugins';
$_lang['system_events.plugins_desc'] = 'La lista dei plugin collegati all\'evento. Controlla i plugin che dovrebbero essere collegati all\'evento.';

$_lang['system_events.service'] = 'Servizio';
$_lang['system_events.service_1'] = 'Eventi del parser';
$_lang['system_events.service_2'] = 'Eventi di accesso del Manager';
$_lang['system_events.service_3'] = 'Eventi del Servizio Accesso Web';
$_lang['system_events.service_4'] = 'Eventi Servizio Cache';
$_lang['system_events.service_5'] = 'Eventi del Servizio Template';
$_lang['system_events.service_6'] = 'Eventi definiti dall\'utente';

$_lang['system_events.remove'] = 'Delete Event';
$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Nome dell\'evento di sistema non specificato.';
$_lang['system_events_err_ae'] = 'Nome dell\'evento di sistema già presente.';
$_lang['system_events_err_startint'] = 'Non è consentito cominciare il nome con un numero.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
