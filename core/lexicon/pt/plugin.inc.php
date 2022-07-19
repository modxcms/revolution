<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Evento';
$_lang['events'] = 'Eventos';
$_lang['plugin'] = 'Plugin';
$_lang['plugin_add'] = 'Adicionar Plugin';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Configuração do Plugin';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'Tem certeza de que deseja excluir este plugin?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'Você tem certeza que deseja duplicar este plugin?';
$_lang['plugin_err_create'] = 'Ocorreu um erro durante a criação do plugin.';
$_lang['plugin_err_ae'] = 'Um plugin já existe com o nome de "[[+name]]".';
$_lang['plugin_err_invalid_name'] = 'Nome do Plugin é inválido.';
$_lang['plugin_err_duplicate'] = 'Ocorreu um erro durante a tentativa de duplicar o plugin.';
$_lang['plugin_err_nf'] = 'Plugin não encontrado!';
$_lang['plugin_err_ns'] = 'Plugin não especificado.';
$_lang['plugin_err_ns_name'] = 'Por favor, indique um nome para o plugin.';
$_lang['plugin_err_remove'] = 'An error occurred while trying to delete the plugin.';
$_lang['plugin_err_save'] = 'Ocorreu um erro ao salvar o plugin.';
$_lang['plugin_event_err_duplicate'] = 'Ocorreu um erro ao tentar duplicar os eventos do plugin ';
$_lang['plugin_event_err_nf'] = 'Evento do Plugin não encontrado.';
$_lang['plugin_event_err_ns'] = 'Evento do Plugin não especificado.';
$_lang['plugin_event_err_remove'] = 'An error occurred while trying to delete the plugin event.';
$_lang['plugin_event_err_save'] = 'Ocorreu um erro ao salvar o evento do plugin.';
$_lang['plugin_event_msg'] = 'Selecione os eventos que você gostaria que este plugin considere.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Are you sure you want to delete this plugin from this event?';
$_lang['plugin_lock'] = 'Plugin locked for editing';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'Este plugin está bloqueado.';
$_lang['plugin_management_msg'] = 'Aqui você pode escolher qual o plugin que você deseja editar.';
$_lang['plugin_name_desc'] = 'O nome deste Plugin.';
$_lang['plugin_new'] = 'Create Plugin';
$_lang['plugin_priority'] = 'Editar _ Ordem de Execução do Plugin por Evento';
$_lang['plugin_properties'] = 'Propriedades do Plugin';
$_lang['plugin_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Plugin</em> as well as its content. The content must be PHP, either placed in the <em>Plugin Code</em> field below or in a static external file. The PHP code entered runs in response to one or more MODX System Events that you specify.';
$_lang['plugins'] = 'Plugins';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];
