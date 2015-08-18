<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Ereignisse';
$_lang['system_event'] = 'Systemereignis';
$_lang['system_events'] = 'System-Ereignisse';
$_lang['system_events.desc'] = 'Systemereignisse sind die Ereignisse in MODX, die Plugins registrieren können. Sie werden im MODX-Code "abgefeuert", so dass Plugins mit MODX-Code interagieren und ihn erweitern können, ohne in den Core Code einzugreifen. Sie können Ihre eigenen Ereignisse für Ihre benutzerdefinierten Projekt hier erstellen. MODX-eigene Ereignisse können nicht entfernt werden, lediglich Ihre eigenen.';
$_lang['system_events.search_by_name'] = 'Suche nach Ereignis-Name';
$_lang['system_events.create'] = 'Neues Ereignis anlegen';
$_lang['system_events.name_desc'] = 'Name des Ereignis, welchen Sie in einem &dollar;modx->invoiceEvent(name, eigenschaften) Aufruf benutzen.';
$_lang['system_events.groupname'] = 'Gruppe';
$_lang['system_events.groupname_desc'] = 'Name der Gruppe, zu der das neue Ereignis gehört. Wählen Sie einen vorhandenen Namen oder geben Sie einen neuen ein.';

$_lang['system_events.service'] = 'Dienst';
$_lang['system_events.service_1'] = 'Parserdienst Ereignisse';
$_lang['system_events.service_2'] = 'Managerzugangs Ereignisse';
$_lang['system_events.service_3'] = 'Webzugriff Dienst Ereignisse';
$_lang['system_events.service_4'] = 'Cachedienst Ereignisse';
$_lang['system_events.service_5'] = 'Templatedienst Ereignisse';
$_lang['system_events.service_6'] = 'Benutzerdefinierte Ereignisse';

$_lang['system_events.remove'] = 'Ereignis entfernen';
$_lang['system_events.remove_confirm'] = 'Sind Sie sicher, dass Sie das Ereignis <b>[[+ Name]]</b> entfernen möchten? Dies ist nicht umkehrbar!';

$_lang['system_events_err_ns'] = 'Name des Systemereignis nicht angegeben.';
$_lang['system_events_err_ae'] = 'Name des Systemereignis ist bereits vorhanden.';
$_lang['system_events_err_startint'] = 'Es ist nicht erlaubt, den Namen mit einer Zahl zu beginnen.';
$_lang['system_events_err_remove_not_allowed'] = 'Sie sind nicht berechtigt, dieses Systemereignis zu entfernen.';
