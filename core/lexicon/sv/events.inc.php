<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Händelser';
$_lang['system_event'] = 'Systemhändelse';
$_lang['system_events'] = 'Systemhändelser';
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'Sök på händelsenamn';
$_lang['system_events.create'] = 'Create Event';
$_lang['system_events.name_desc'] = 'Händelsens namn. Du använder det i anrop: &dollar;modx->invokeEvent(namn, attribut).';
$_lang['system_events.groupname'] = 'Grupp';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'Plugins';
$_lang['system_events.plugins_desc'] = 'Listan över plugins som är kopplade till händelsen. Plocka upp plugins som ska kopplas till händelsen.';

$_lang['system_events.service'] = 'Tjänst';
$_lang['system_events.service_1'] = 'Händelser för parsertjänsten';
$_lang['system_events.service_2'] = 'Händelser för tillgång till hanteraren';
$_lang['system_events.service_3'] = 'Tjänstehändelser för webbtillgång';
$_lang['system_events.service_4'] = 'Händelser för cachtjänsten';
$_lang['system_events.service_5'] = 'Händelser för malltjänsten';
$_lang['system_events.service_6'] = 'Användardefinierade händelser';

$_lang['system_events.remove'] = 'Delete Event';
$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Inget namn angivet på systemhändelsen.';
$_lang['system_events_err_ae'] = 'Namnet på systemhändelsen finns redan.';
$_lang['system_events_err_startint'] = 'Det är inte tillåtet för att börja namnet med en siffra.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
