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
$_lang['system_events.desc'] = 'Eventos do sistema são os eventos no MODX que são registrado para Plugins. Eles são "rodados" em todo o código MODX, permitindo Plugins para interagir com código MODX e adicionar funcionalidade personalizada sem pirataria código do núcleo. Você pode criar seus próprios eventos para seu projeto personalizado aqui também. Você não pode remover eventos principais, apenas seu próprio.';
$_lang['system_events.search_by_name'] = 'Pesquisar por nome de evento';
$_lang['system_events.create'] = 'Criar Novo Evento';
$_lang['system_events.name_desc'] = 'O nome do evento. Que você deve usar em uma chamada &dollar;modx->invokeEvent(nome, Propriedades).';
$_lang['system_events.groupname'] = 'Grupo';
$_lang['system_events.groupname_desc'] = 'O nome do grupo onde pertence o novo evento. Selecione um existente ou digite um novo nome de grupo.';

$_lang['system_events.service'] = 'Serviço';
$_lang['system_events.service_1'] = 'Eventos de Parser de Serviço';
$_lang['system_events.service_2'] = 'Eventos de Acesso do Gerenciador';
$_lang['system_events.service_3'] = 'Eventos do Serviço de Acesso Web';
$_lang['system_events.service_4'] = 'Eventos do Serviço de Cache';
$_lang['system_events.service_5'] = 'Eventos do Serviço de Template';
$_lang['system_events.service_6'] = 'Eventos Definidos pelo Usuário';

$_lang['system_events.remove'] = 'Remover o Evento';
$_lang['system_events.remove_confirm'] = 'Tem certeza que deseja remover o evento <b>[[+name]]</b>? Isso é irreversível!';

$_lang['system_events_err_ns'] = 'Nome do Evento do Sistema não especificado.';
$_lang['system_events_err_ae'] = 'Nome do Evento do Sistema já existe.';
$_lang['system_events_err_startint'] = 'Não é permitido começar o nome com um dígito.';
$_lang['system_events_err_remove_not_allowed'] = 'Não pode remover este Evento do Sistema.';
