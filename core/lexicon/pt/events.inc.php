<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Eventos';
$_lang['system_event'] = 'Evento do Sistema';
$_lang['system_events'] = 'Eventos do Sistema';
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'Pesquisar por nome de evento';
$_lang['system_events.name_desc'] = 'O nome do evento. Que você deve usar em uma chamada &dollar;modx->invokeEvent(nome, Propriedades).';
$_lang['system_events.groupname'] = 'Grupo';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'Plugins';
$_lang['system_events.plugins_desc'] = 'Lista de plugins associados ao evento. Escolha os plugins que deveja associar ao evento.';

$_lang['system_events.service'] = 'Serviço';
$_lang['system_events.service_1'] = 'Eventos de Parser de Serviço';
$_lang['system_events.service_2'] = 'Eventos de Acesso do Gerenciador';
$_lang['system_events.service_3'] = 'Eventos do Serviço de Acesso Web';
$_lang['system_events.service_4'] = 'Eventos do Serviço de Cache';
$_lang['system_events.service_5'] = 'Eventos do Serviço de Template';
$_lang['system_events.service_6'] = 'Eventos Definidos pelo Usuário';

$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Nome do Evento do Sistema não especificado.';
$_lang['system_events_err_ae'] = 'Nome do Evento do Sistema já existe.';
$_lang['system_events_err_startint'] = 'Não é permitido começar o nome com um dígito.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
