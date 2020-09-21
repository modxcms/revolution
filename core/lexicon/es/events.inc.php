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
$_lang['system_events.desc'] = 'Los eventos del sistema son los eventos en MODX en los que los Plugins están registrados. Se "disparan" en cualquier parte del código de MODX, lo que permite que los Plugins interactúen con el código de MODX y agreguen funciones personalizadas sin hackear el código del core. Aquí también puedes crear tus propios eventos para tu proyecto personalizado. No puedes eliminar eventos principales, solo los tuyos.';
$_lang['system_events.search_by_name'] = 'Buscar por nombre de evento';
$_lang['system_events.create'] = 'Crear nuevo evento';
$_lang['system_events.name_desc'] = 'El nombre del evento. El cuál debe usarse en una llamada a &dollar;modx->invokeEvent(nombre, propiedades).';
$_lang['system_events.groupname'] = 'Grupo';
$_lang['system_events.groupname_desc'] = 'El nombre del grupo al que pertenece el nuevo evento. Selecciona uno existente o escribe un nuevo nombre de grupo.';
$_lang['system_events.plugins'] = 'Plugins';
$_lang['system_events.plugins_desc'] = 'La lista de Plugins ligados al evento. Recoje los plugins que se deben ejecutar al suceder el evento.';

$_lang['system_events.service'] = 'Servicio';
$_lang['system_events.service_1'] = 'Eventos del servicio parser';
$_lang['system_events.service_2'] = 'Eventos de acceso al Manager';
$_lang['system_events.service_3'] = 'Eventos del servicio de acceso a la web';
$_lang['system_events.service_4'] = 'Eventos del servicio de caché';
$_lang['system_events.service_5'] = 'Eventos del servicio de plantilla';
$_lang['system_events.service_6'] = 'Eventos definidos por el usuario';

$_lang['system_events.remove'] = 'Eliminar Evento';
$_lang['system_events.remove_confirm'] = 'Are you sure you want to remove the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Name of the System Event not specified.';
$_lang['system_events_err_ae'] = 'Name of the System Event already exists.';
$_lang['system_events_err_startint'] = 'It\'s not allowed to start the name with a digit.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to remove this System Event.';
