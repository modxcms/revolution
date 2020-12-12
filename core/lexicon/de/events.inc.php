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
$_lang['system_events'] = 'Systemereignisse';
$_lang['system_events.desc'] = 'Systemereignisse sind die Ereignisse in MODX, auf die Plugins reagieren können. Sie werden im MODX-Code "abgefeuert", sodass Plugins mit MODX-Code interagieren und ihn um eigene Funktionalitäten erweitern können, ohne in den Core-Code einzugreifen. Sie können hier auch eigene Ereignisse für Ihr Projekt erstellen. MODX-eigene Ereignisse können nicht entfernt werden, lediglich Ihre eigenen.';
$_lang['system_events.search_by_name'] = 'Suche nach Ereignis-Name';
$_lang['system_events.create'] = 'Neues Ereignis anlegen';
$_lang['system_events.name_desc'] = 'Der Name des Ereignisses, den Sie in einem Aufruf der Form &dollar;modx->invokeEvent(name, eigenschaften) benutzen können.';
$_lang['system_events.groupname'] = 'Gruppe';
$_lang['system_events.groupname_desc'] = 'Der Name der Gruppe, zu der das neue Ereignis gehört. Wählen Sie einen vorhandenen Gruppennamen oder geben Sie einen neuen ein.';
$_lang['system_events.plugins'] = 'Plugins';
$_lang['system_events.plugins_desc'] = 'Liste der dem Ereignis zugeordneten Plugins. Wählen Sie die Plugins, die dem Ereignis zugeordnet werden sollen.';

$_lang['system_events.service'] = 'Dienst';
$_lang['system_events.service_1'] = 'Parserdienst-Ereignisse';
$_lang['system_events.service_2'] = 'Manager-Zugangs-Ereignisse';
$_lang['system_events.service_3'] = 'Webzugriffsdienst-Ereignisse';
$_lang['system_events.service_4'] = 'Cachedienst-Ereignisse';
$_lang['system_events.service_5'] = 'Templatedienst-Ereignisse';
$_lang['system_events.service_6'] = 'Benutzerdefinierte Ereignisse';

$_lang['system_events.remove'] = 'Ereignis entfernen';
$_lang['system_events.remove_confirm'] = 'Sind Sie sicher, dass Sie das Ereignis <b>[[+name]]</b> entfernen möchten? Dies kann nicht rückgängig gemacht werden!';

$_lang['system_events_err_ns'] = 'Name des Systemereignisses nicht angegeben.';
$_lang['system_events_err_ae'] = 'Der Name des Systemereignisses existiert bereits.';
$_lang['system_events_err_startint'] = 'Der Name darf nicht mit einer Ziffer beginnen.';
$_lang['system_events_err_remove_not_allowed'] = 'Sie sind nicht berechtigt, dieses Systemereignis zu entfernen.';
