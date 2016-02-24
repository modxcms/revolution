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
$_lang['system_events.desc'] = 'Systemhändelser är händelser i MODX som plugins registreras till. De "triggas" på olika ställen i MODX kod så att plugins kan interagera med MODX kod och lägga till anpassad funktionalitet utan att ändra kärnkoden. Du kan även skapa dina egna händelser här för dina egna projekt. Du kan inte ta bort kärnhändelser utan bara dina egna.';
$_lang['system_events.search_by_name'] = 'Sök på händelsenamn';
$_lang['system_events.create'] = 'Skapa ny händelse';
$_lang['system_events.name_desc'] = 'Händelsens namn. Du använder det i anrop: &dollar;modx->invokeEvent(namn, attribut).';
$_lang['system_events.groupname'] = 'Grupp';
$_lang['system_events.groupname_desc'] = 'Namnet på den grupp som den nya händelsen hör till. Välj en befintlig eller skriv in ett nytt gruppnamn.';

$_lang['system_events.service'] = 'Tjänst';
$_lang['system_events.service_1'] = 'Händelser för parsertjänsten';
$_lang['system_events.service_2'] = 'Händelser för tillgång till hanteraren';
$_lang['system_events.service_3'] = 'Tjänstehändelser för webbtillgång';
$_lang['system_events.service_4'] = 'Händelser för cachtjänsten';
$_lang['system_events.service_5'] = 'Händelser för malltjänsten';
$_lang['system_events.service_6'] = 'Användardefinierade händelser';

$_lang['system_events.remove'] = 'Ta bort händelse';
$_lang['system_events.remove_confirm'] = 'Är du säker på att du vill ta bort händelsen <b>[[+name]]</b>? Det här går inte att ångra!';

$_lang['system_events_err_ns'] = 'Inget namn angivet på systemhändelsen.';
$_lang['system_events_err_ae'] = 'Namnet på systemhändelsen finns redan.';
$_lang['system_events_err_startint'] = 'Det är inte tillåtet för att börja namnet med en siffra.';
$_lang['system_events_err_remove_not_allowed'] = 'Du får inte ta bort denna systemhändelse.';
