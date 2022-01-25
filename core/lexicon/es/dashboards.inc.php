<?php
/**
 * English language strings for Dashboards
 *
 * @package modx
 * @subpackage lexicon
 * @language en
 */
$_lang['dashboard'] = 'Resumen';
$_lang['dashboard_desc_name'] = 'Nombre del Tablero.';
$_lang['dashboard_desc_description'] = 'Breve descripción del Tablero.';
$_lang['dashboard_desc_hide_trees'] = 'Marcando esta casilla se ocultarán los árboles de la izquierda cuando el tablero se despliegue en la página de bienvenida.';
$_lang['dashboard_hide_trees'] = 'Ocultar árboles del la izquierda';
$_lang['dashboard_desc_customizable'] = 'Allow users to customize this dashboard for their accounts: create, delete and change position or size of widgets.';
$_lang['dashboard_customizable'] = 'Customizable';
$_lang['dashboard_remove_confirm'] = 'Are you sure you want to delete this Dashboard?';
$_lang['dashboard_remove_multiple_confirm'] = 'Are you sure you want to delete the selected Dashboards?';
$_lang['dashboard_err_ae_name'] = '¡Ya existe un tablero con el nombre "[[+name]]"! Por favor, prueba otro nombre.';
$_lang['dashboard_err_duplicate'] = 'Ha ocurrido un error mientras se trataba de duplicar el tablero.';
$_lang['dashboard_err_nf'] = 'Tablero no encontrado.';
$_lang['dashboard_err_ns'] = 'Tablero no especificado.';
$_lang['dashboard_err_ns_name'] = 'Por favor, especifica un nombre para el widget.';
$_lang['dashboard_err_remove'] = 'An error occurred while trying to delete the Dashboard.';
$_lang['dashboard_err_remove_default'] = 'You cannot delete the default Dashboard!';
$_lang['dashboard_err_save'] = 'Ha ocurrido un error mientras se trataba de guardar el Tablero.';
$_lang['dashboard_usergroup_add'] = 'Asignar Tablero a un Grupo de Usuarios';
$_lang['dashboard_usergroup_remove'] = 'Delete Dashboard from User Group';
$_lang['dashboard_usergroup_remove_confirm'] = '¿Estás seguro de que quieres restaurar este Grupo de Usuarios para que utilicen el Tablero por defecto?';
$_lang['dashboard_usergroups.intro_msg'] = 'Aquí una lista completa de todos los Grupos de Usuarios utilizando este Tablero.';
$_lang['dashboard_widget_err_placed'] = '¡Este widget ya está asignado a este Tablero!';
$_lang['dashboard_widgets.intro_msg'] = 'Manage widgets in this dashboard. You can also drag and drop rows in the grid to rearrange them.<br><br>Please note: if a dashboard is "customizable", this settings will be applied only for the first load for every user. From here they will be able to create, delete and change the position or size of their widgets. User access to widgets can be limited by applying permissions.';
$_lang['dashboards'] = 'Tableros';
$_lang['dashboards.intro_msg'] = 'Aquí puedes gestionar todos los Tableros para este gestor de MODX.';
$_lang['rank'] = 'Posición';
$_lang['user_group_filter'] = 'Por Grupo de Usuarios';
$_lang['widget'] = 'Widget';
$_lang['widget_content'] = 'Contenido del Widget';
$_lang['widget_err_ae_name'] = '¡Ya existe un widget con el nombre "[[+name]]"! Por favor, prueba otro nombre.';
$_lang['widget_err_nf'] = '¡Widget no encontrado!';
$_lang['widget_err_ns'] = '¡Widget no especificado!';
$_lang['widget_err_ns_name'] = 'Por favor, especifica un nombre para el widget.';
$_lang['widget_err_remove'] = 'An error occurred while trying to delete the Widget.';
$_lang['widget_err_save'] = 'Ha ocurrido un error mientras se trataba de guardar el widget.';
$_lang['widget_file'] = 'Archivo';
$_lang['widget_dashboards.intro_msg'] = 'Debajo se encuentra una lista completa con todos los Tableros en los que se utiliza este Widget.';
$_lang['widget_dashboard_remove'] = 'Delete Widget From Dashboard';
$_lang['widget_description_desc'] = 'Una descripción, o clave de entrada del archivo de idioma, del widget y de lo que hace.';
$_lang['widget_html'] = 'HTML';
$_lang['widget_lexicon_desc'] = 'El tópico del archivo de idioma a cargar con este Widget. Útil para permitir la traducción del nombre y la descripción, así como de los textos del widget.';
$_lang['widget_permission_desc'] = 'This permission will be required to add this widget to a user dashboard.';
$_lang['widget_permission'] = 'Permisos';
$_lang['widget_name_desc'] = 'El nombre, o clave de entrada del archivo de idioma, del Widget.';
$_lang['widget_add'] = 'Add Widget';
$_lang['widget_add_desc'] = 'Please select a Widget to add to your Dashboard.';
$_lang['widget_add_success'] = 'The widget was successfully added to your Dashboard. It will be loaded after closing this window.';
$_lang['widget_remove_confirm'] = 'Are you sure you want to delete this Dashboard Widget? This is permanent, and will delete the Widget from all Dashboards.';
$_lang['widget_remove_multiple_confirm'] = 'Are you sure you want to delete these Dashboard Widgets? This is permanent, and will delete the Widgets from all their assigned Dashboards.';
$_lang['widget_namespace'] = 'Espacio de Nombres';
$_lang['widget_namespace_desc'] = 'El Espacio de nombres en el que se cargará éste widget. Útil para rutas personalizadas.';
$_lang['widget_php'] = 'Widget de PHP en línea';
$_lang['widget_place'] = 'Colocar Widget';
$_lang['widget_size'] = 'Tamaño';
$_lang['widget_size_desc'] = 'The size of the widget. Can either be a from "quarter" to "double".';
$_lang['widget_size_double'] = 'Double Size';
$_lang['widget_size_full'] = 'Full Size';
$_lang['widget_size_three_quarters'] = 'Three Quarters';
$_lang['widget_size_two_third'] = 'Two Third';
$_lang['widget_size_half'] = 'Media';
$_lang['widget_size_one_third'] = 'One Third';
$_lang['widget_size_quarter'] = 'Quarter';
$_lang['widget_snippet'] = 'Snippet';
$_lang['widget_type'] = 'Tipo de Widget';
$_lang['widget_type_desc'] = 'El tipo del widget. Los widgets tipo "Snippet" son Snippets de MODX que se interpretan y devuelven su salida. Los widgets tipo "HTML" son simplemente código HTML. Los widgets tipo "File" se cargan directamente desde un archivo, y pueden devolver su propia salida o el nombre de una clase a cargar que extienda a la clase modDashboardWidgetClass. Los widgets de "Inline PHP" son código PHP en el contenido del widget, similar a un Snippet.';
$_lang['widget_unplace'] = 'Delete Widget from Dashboard';
$_lang['widgets'] = 'Widgets';
$_lang['widgets.intro_msg'] = 'Debajo se encuentra una lista completa con todos los Widgets instalados.';

$_lang['action_new_resource'] = 'New page';
$_lang['action_new_resource_desc'] = 'Create a new page for your website.';
$_lang['action_view_website'] = 'View website';
$_lang['action_view_website_desc'] = 'Open your website in a new window.';
$_lang['action_advanced_search'] = 'Advanced search';
$_lang['action_advanced_search_desc'] = 'Advanced search through your website.';
$_lang['action_manage_users'] = 'Manage users';
$_lang['action_manage_users_desc'] = 'Manage all your website and manager users.';

$_lang['w_buttons'] = 'Buttons';
$_lang['w_buttons_desc'] = 'Displays a set of buttons from array specified in properties.';
$_lang['w_updates'] = 'Updates';
$_lang['w_updates_desc'] = 'Checks for available updates for core and extras.';
$_lang['w_configcheck'] = 'Revisión de Configuración';
$_lang['w_configcheck_desc'] = 'Desplegar una revisión la configuración para asegurarse de que la instalación de MODX es segura.';
$_lang['w_newsfeed'] = 'Feed de Noticias de MODX';
$_lang['w_newsfeed_desc'] = 'Mostrar el Feed de Noticias de MODX';
$_lang['w_recentlyeditedresources'] = 'Recursos Editados Recientemente';
$_lang['w_recentlyeditedresources_desc'] = 'Muestra una lista con los recursos editados más recientemente por el usuario.';
$_lang['w_securityfeed'] = 'Feed de Seguridad de MODX';
$_lang['w_securityfeed_desc'] = 'Mostrar el Feed de Seguridad de MODX';
$_lang['w_whosonline'] = '¿Quién está conectado?';
$_lang['w_whosonline_desc'] = 'Muestra una lista de usuarios conectados.';
$_lang['w_view_all'] = 'View all';
$_lang['w_no_data'] = 'Sin datos para mostrar';