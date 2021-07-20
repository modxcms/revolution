<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Eventos';
$_lang['system_event'] = 'Eventos del Sistema';
$_lang['system_events'] = 'Eventos del Sistema';
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'Buscar por nombre de evento';
$_lang['system_events.name_desc'] = 'El nombre del evento. El cuál debe usarse en una llamada a &dollar;modx->invokeEvent(nombre, propiedades).';
$_lang['system_events.groupname'] = 'Grupo';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'Plugins';
$_lang['system_events.plugins_desc'] = 'La lista de Plugins ligados al evento. Recoje los plugins que se deben ejecutar al suceder el evento.';

$_lang['system_events.service'] = 'Servicio';
$_lang['system_events.service_1'] = 'Eventos del servicio parser';
$_lang['system_events.service_2'] = 'Eventos de acceso al Manager';
$_lang['system_events.service_3'] = 'Eventos del servicio de acceso a la web';
$_lang['system_events.service_4'] = 'Eventos del servicio de caché';
$_lang['system_events.service_5'] = 'Eventos del servicio de plantilla';
$_lang['system_events.service_6'] = 'Eventos definidos por el usuario';

$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Name of the System Event not specified.';
$_lang['system_events_err_ae'] = 'Name of the System Event already exists.';
$_lang['system_events_err_startint'] = 'It\'s not allowed to start the name with a digit.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
