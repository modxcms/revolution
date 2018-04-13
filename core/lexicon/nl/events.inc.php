<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Gebeurtenissen';
$_lang['system_event'] = 'Systeem Gebeurtenissen';
$_lang['system_events'] = 'Systeem gebeurtenissen';
$_lang['system_events.desc'] = 'Systeemgebeurtenissen zijn de gebeurtenissen in MODX waar Plugins op geregistreerd worden. Deze zijn door de hele core verspreid en worden daar uitgevoerd zodat Plugins hierop kunnen reageren om nieuwe functionaliteiten aan MODX toe te voegen. Het is ook mogelijk om je eigen gebeurtenissen te maken. Enkel gebeurtenissen die door gebruikers zijn toegevoegd kunnen worden verwijderd.';
$_lang['system_events.search_by_name'] = 'Zoek op naam van een gebeurtenis';
$_lang['system_events.create'] = 'Maak nieuwe gebeurtenis';
$_lang['system_events.name_desc'] = 'Naam van de gebeurtenis. Deze wordt gebruikt in de &dollar;modx->invokeEvent(name, properties) aanroep.';
$_lang['system_events.groupname'] = 'Groep';
$_lang['system_events.groupname_desc'] = 'De naam van de groep waar de gebeurtenis aan toebehoort. Selecteer een bestaande groep, of type een nieuwe naam in.';
$_lang['system_events.plugins'] = 'Plugins';
$_lang['system_events.plugins_desc'] = 'De lijst met plugins die gebruik maken van de gebeurtenis. Selecteer de plugins die aan de gebeurtenis moeten worden gekoppeld.';

$_lang['system_events.service'] = 'Service';
$_lang['system_events.service_1'] = 'Parser Service Gebeurtenissen';
$_lang['system_events.service_2'] = 'Manager Toegang Gebeurtenissen';
$_lang['system_events.service_3'] = 'Web Toegang Service Gebeurtenissen';
$_lang['system_events.service_4'] = 'Cache Service Gebeurtenissen';
$_lang['system_events.service_5'] = 'Template Service Gebeurtenissen';
$_lang['system_events.service_6'] = 'Gebruiker gedefinieerde gebeurtenissen';

$_lang['system_events.remove'] = 'Gebeurtenis verwijderen';
$_lang['system_events.remove_confirm'] = 'Are you sure you want to remove the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Name van de gebeurtenis niet opgegeven.';
$_lang['system_events_err_ae'] = 'Naam van de gebeurtenis is al in gebruik.';
$_lang['system_events_err_startint'] = 'Het is niet toegestaan om de naam met een cijfer te beginnen.';
$_lang['system_events_err_remove_not_allowed'] = 'Het is niet toegestaan om deze systeemgebeurtenis te verwijderen.';
