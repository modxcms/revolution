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
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'Suche nach Ereignis-Name';
$_lang['system_events.create'] = 'Create Event';
$_lang['system_events.name_desc'] = 'Der Name des Ereignisses, den Sie in einem Aufruf der Form &dollar;modx->invokeEvent(name, eigenschaften) benutzen können.';
$_lang['system_events.groupname'] = 'Gruppe';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'Plugins';
$_lang['system_events.plugins_desc'] = 'Liste der dem Ereignis zugeordneten Plugins. Wählen Sie die Plugins, die dem Ereignis zugeordnet werden sollen.';

$_lang['system_events.service'] = 'Dienst';
$_lang['system_events.service_1'] = 'Parserdienst-Ereignisse';
$_lang['system_events.service_2'] = 'Manager-Zugangs-Ereignisse';
$_lang['system_events.service_3'] = 'Webzugriffsdienst-Ereignisse';
$_lang['system_events.service_4'] = 'Cachedienst-Ereignisse';
$_lang['system_events.service_5'] = 'Templatedienst-Ereignisse';
$_lang['system_events.service_6'] = 'Benutzerdefinierte Ereignisse';

$_lang['system_events.remove'] = 'Delete Event';
$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Name des Systemereignisses nicht angegeben.';
$_lang['system_events_err_ae'] = 'Der Name des Systemereignisses existiert bereits.';
$_lang['system_events_err_startint'] = 'Der Name darf nicht mit einer Ziffer beginnen.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
