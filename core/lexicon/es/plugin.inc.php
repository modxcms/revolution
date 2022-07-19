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
$_lang['plugin_add'] = 'Añadir Plugin';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Configuración del Plugin';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = '¿Estás seguro de que quieres eliminar este plugin?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = '¿Estás seguro de que quieres duplicar este plugin?';
$_lang['plugin_err_create'] = 'Ocurrió un error mientras se creaba el plugin.';
$_lang['plugin_err_ae'] = 'Ya existe un plugin con el nombre "[[+name]]".';
$_lang['plugin_err_invalid_name'] = 'El nombre del Plugin no es válido.';
$_lang['plugin_err_duplicate'] = 'Se produjo un error al intentar duplicar el plugin.';
$_lang['plugin_err_nf'] = '¡Plugin no encontrado!';
$_lang['plugin_err_ns'] = '¡Plugin no especificado.';
$_lang['plugin_err_ns_name'] = 'Por favor, especifica un nombre para el plugin.';
$_lang['plugin_err_remove'] = 'An error occurred while trying to delete the plugin.';
$_lang['plugin_err_save'] = 'Ocurrió un error mientras se guardaba el plugin.';
$_lang['plugin_event_err_duplicate'] = 'Ocurrió un error cuando se trataban de duplicar los eventos del plugin';
$_lang['plugin_event_err_nf'] = 'Evento del plugin no encontrado.';
$_lang['plugin_event_err_ns'] = 'Evento del plugin no especificado.';
$_lang['plugin_event_err_remove'] = 'An error occurred while trying to delete the plugin event.';
$_lang['plugin_event_err_save'] = 'Ocurrió un error mientras se guardaba el evento del plugin.';
$_lang['plugin_event_msg'] = 'Selecciona los eventos que este plugin escuchará.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Are you sure you want to delete this plugin from this event?';
$_lang['plugin_lock'] = 'Plugin con edición bloqueada';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'Este plugin está bloqueado para su edición.';
$_lang['plugin_management_msg'] = 'Aquí puedes elegir el plugin a editar.';
$_lang['plugin_name_desc'] = 'El nombre de este Plugin.';
$_lang['plugin_new'] = 'Create Plugin';
$_lang['plugin_priority'] = 'Editar el Orden de Ejecución del Plugin por Evento';
$_lang['plugin_properties'] = 'Propiedades del Plugin';
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
