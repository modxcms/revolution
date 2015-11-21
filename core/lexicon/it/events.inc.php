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
$_lang['system_events.desc'] = 'Gli Eventi di sistema sono gli eventi di MODX a cui possono essere agganciati i Plugins. Essi sono "attivati" attraverso tutto il codice di MODX, permettendo ai plugin di interagire con il codice stesso MODX e di aggiungere funzionalità personalizzate senza dover modificare il codice di base. Qui puoi anche creare i tuoi eventi personalizzati per il tuo progetto personale. Non è possibile rimuovere gli eventi di base, solo i tuoi.';
$_lang['system_events.search_by_name'] = 'Ricerca per nome dell\'evento';
$_lang['system_events.create'] = 'Crea nuovo Evento';
$_lang['system_events.name_desc'] = 'Il nome dell\'evento. Che dovrebbe essere usato in una chiamata: &dollar;modx->invokeEvent(name, properties) .';
$_lang['system_events.groupname'] = 'Guppo';
$_lang['system_events.groupname_desc'] = 'Il nome del gruppo cui appartiene il nuovo evento. Selezionarne uno esistente o scrivere un nuovo nome di gruppo.';

$_lang['system_events.service'] = 'Servizio';
$_lang['system_events.service_1'] = 'Parser Service Events';
$_lang['system_events.service_2'] = 'Manager Access Events';
$_lang['system_events.service_3'] = 'Web Access Service Events';
$_lang['system_events.service_4'] = 'Cache Service Events';
$_lang['system_events.service_5'] = 'Template Service Events';
$_lang['system_events.service_6'] = 'Eventi definiti dall\'utente';

$_lang['system_events.remove'] = 'Rimuovi evento';
$_lang['system_events.remove_confirm'] = 'Sei sicuro di voler rimuovere l\'evento <b>[[+ name]]</b>? Questo è irreversibile!';

$_lang['system_events_err_ns'] = 'Nome dell\'evento di sistema non specificato.';
$_lang['system_events_err_ae'] = 'Nome dell\'evento di sistema già presente.';
$_lang['system_events_err_startint'] = 'Non è consentito cominciare il nome con un numero.';
$_lang['system_events_err_remove_not_allowed'] = 'Non puoi rimuovere questo evento di sistema.';
