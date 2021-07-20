<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Události';
$_lang['system_event'] = 'Systémová událost';
$_lang['system_events'] = 'Systémové události';
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'Hledat podle názvu události';
$_lang['system_events.name_desc'] = 'Název události. Který byste měli použít v rámci volání &dollar;modx->invokeEvent(název, vlastnosti).';
$_lang['system_events.groupname'] = 'Skupina';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'Pluginy';
$_lang['system_events.plugins_desc'] = 'Seznam pluginů připojených k události. Vyzvedněte pluginy, které by měly být připojeny k události.';

$_lang['system_events.service'] = 'Služba';
$_lang['system_events.service_1'] = 'Události služby Parser';
$_lang['system_events.service_2'] = 'Události přístupu do manageru';
$_lang['system_events.service_3'] = 'Události přístupu na web';
$_lang['system_events.service_4'] = 'Události cache';
$_lang['system_events.service_5'] = 'Události šablon';
$_lang['system_events.service_6'] = 'Uživatelem definované události';

$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Název systémové události není zadán.';
$_lang['system_events_err_ae'] = 'Název systémové události již existuje.';
$_lang['system_events_err_startint'] = 'Název nesmí začínat číslicí.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
