<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Събития';
$_lang['system_event'] = 'System Event';
$_lang['system_events'] = 'Системни събития';
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'Search by event name';
$_lang['system_events.name_desc'] = 'The name of the event. Which you should use in a &dollar;modx->invokeEvent(name, properties) call.';
$_lang['system_events.groupname'] = 'Група';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'Плъгини';
$_lang['system_events.plugins_desc'] = 'The list of plugins attached to the event. Pick up plugins that should be attached to event.';

$_lang['system_events.service'] = 'Service';
$_lang['system_events.service_1'] = 'Parser Service Events';
$_lang['system_events.service_2'] = 'Manager Access Events';
$_lang['system_events.service_3'] = 'Web Access Service Events';
$_lang['system_events.service_4'] = 'Cache Service Events';
$_lang['system_events.service_5'] = 'Template Service Events';
$_lang['system_events.service_6'] = 'User Defined Events';

$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Name of the System Event not specified.';
$_lang['system_events_err_ae'] = 'Name of the System Event already exists.';
$_lang['system_events_err_startint'] = 'It\'s not allowed to start the name with a digit.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
