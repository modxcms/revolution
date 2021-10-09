<?php
/**
 * Resource English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Acceso';
$_lang['cache_output'] = 'Salida del Cache';
$_lang['changes'] = 'Cambios';
$_lang['class_key'] = 'Clave de Clase';
$_lang['context'] = 'Contexto';
$_lang['document'] = 'Documento';
$_lang['document_create'] = 'Crear Documento';
$_lang['document_create_here'] = 'Documento';
$_lang['document_new'] = 'Documento Nuevo';
$_lang['documents'] = 'Documentos';
$_lang['duplicate_uri_found'] = 'El Recurso [[+id]] ya está usando la URI [[+uri]]. Por favor, introduce un alias único o usa "Congelar URI" para anularlo manualmente.';
$_lang['empty_template'] = '(vacío)';
$_lang['general'] = 'General';
$_lang['markup'] = 'Markup/Estructura';
$_lang['none'] = 'Ningún';
$_lang['page_settings'] = 'Configuraciones';
$_lang['preview'] = 'Vista Previa';
$_lang['resource_access_message'] = 'Aquí puedes seleccionar los Grupos de Recursos a los que pertenece este Recurso.';
$_lang['resource_add_children_access_denied'] = 'No tienes permiso para crear un Recurso aquí.';
$_lang['resource_alias'] = 'Alias del Recurso';
$_lang['resource_alias_help'] = 'An alias for this resource. This will make the resource accessible using:<br /><br />https://yourserver/alias<br /><br /><strong>Note</strong>This only works if you\'re using friendly URLs.';
$_lang['resource_alias_visible'] = 'Usar alias actual en la ruta del alias';
$_lang['resource_alias_visible_help'] = 'El alias de este recurso se ha insertado en la ruta de alias de URL amigable';
$_lang['resource_change_template_confirm'] = '¿Estás seguro de que quieres cambiar la plantilla? <br /><br />ADVERTENCIA: Esto <b>sólo guardará temporalmente</b> los cambios hechos previamente y cargará la página nuevamente; asegúrate de estar listo para hacerlo antes de continuar. Después que la página sea recargada recuerda guardar el cambio de Plantilla cuando estés listo.';
$_lang['resource_cacheable'] = 'Almacenable en Caché';
$_lang['resource_cacheable_help'] = 'Cuando está habilitado, el recurso será guardado en la caché.';
$_lang['resource_cancel_dirty_confirm'] = 'No se han guardado los cambios; ¿Estás seguro de que quieres cancelar?';
$_lang['resource_class_key_help'] = 'Esta es la clave de la clase del recurso, correspondiente a su tipo de MODX.';
$_lang['resource_content'] = 'Contenido';
$_lang['resource_contentdispo'] = 'Disposición del Contenido';
$_lang['resource_contentdispo_help'] = 'Usa el campo de disposición de contenido para especificar como será manejado este recurso por el navegador. Para descargar archivos selecciona la opción de "Adjunto".';
$_lang['resource_content_type'] = 'Tipo de Contenido';
$_lang['resource_content_type_help'] = 'El tipo de contenido para este recurso. Si no estás seguro de que tipo de contenido debería de tener este recurso, simplemente déjalo como text/html.';
$_lang['resource_create_access_denied'] = 'No tienes permiso para crear un Recurso.';
$_lang['resource_create_here'] = 'Recurso';
$_lang['resource_createdby'] = 'Creado Por';
$_lang['resource_createdon'] = 'Creado El';
$_lang['resource_delete'] = 'Borrar';
$_lang['resource_delete_confirm'] = '¿Estás seguro de que quieres borrar este recurso?<br />NOTA: ¡Si existen recursos hijos también serán borrados!';
$_lang['resource_description'] = 'Descripción';
$_lang['resource_description_help'] = 'Descripción opcional del recurso.';
$_lang['resource_duplicate'] = 'Duplicar';
$_lang['resource_duplicate_confirm'] = '¿Estás seguro de que quieres duplicar este recurso? Cualquier artículo que contenga también será duplicado.';
$_lang['resource_edit'] = 'Editar';
$_lang['resource_editedby'] = 'Editado Por';
$_lang['resource_editedon'] = 'Editado El';
$_lang['resource_err_change_parent_to_folder'] = 'Ocurrió un error mientras se trataba de cambiar el padre del recurso a una carpeta.';
$_lang['resource_err_class'] = 'El recurso no es una [[+class]] válida.';
$_lang['resource_err_create'] = 'Ocurrió un error mientras se trataba de crear el recurso.';
$_lang['resource_err_delete'] = 'Ocurrió un error mientras se trataba de borrar el recurso.';
$_lang['resource_err_delete_children'] = 'Ocurrió un error mientras se trataba de borrar el hijo del recurso.';
$_lang['resource_err_delete_container_sitestart'] = 'El recurso que estás tratando de borrar es un contenedor que contiene el recurso [[+id]]. Este recurso está registrado la "Página de Inicio" y no puede ser borrado. Por favor, configura otro recurso como la "Página de Inicio" e inténtalo de nuevo.';
$_lang['resource_err_delete_container_siteunavailable'] = 'El recurso que estás tratando de borrar es una carpeta que contiene el recurso [[+id]]. Este recurso está registrado como la "Página de Sitio no Disponible" y no puede ser borrado. Por favor, configura otro recurso como la "Página de Sitio no Disponible" e inténtalo de nuevo.';
$_lang['resource_err_delete_sitestart'] = '¡El recurso es usado como la "Página de Inicio" y no puede ser borrado!';
$_lang['resource_err_delete_siteunavailable'] = '¡El recurso es usado como la "Página de Sitio no Disponible" y no puede ser borrada!';
$_lang['resource_err_duplicate'] = 'Ocurrió un error mientras se trataba de duplicar el recurso.';
$_lang['resource_err_move_to_child'] = 'No puedes mover un Recurso por debajo de uno de sus propios hijos.';
$_lang['resource_err_move_sitestart'] = '¡El recurso está asignado a la variable site_start y no puede ser movido a otro contexto!';
$_lang['resource_err_nf'] = 'Recurso no encontrado.';
$_lang['resource_err_nfs'] = 'Recurso con ID [[+id]] no encontrado';
$_lang['resource_err_ns'] = 'Recurso no especificado.';
$_lang['resource_err_own_parent'] = 'El recurso no puede ser su propio padre.';
$_lang['resource_err_publish']  = 'Ocurrió un error mientras se trataba de publicar el recurso.';
$_lang['resource_err_new_parent_nf'] = 'Recurso padre nuevo con ID [[+id]] no encontrado.';
$_lang['resource_err_remove'] = 'Ocurrió un error mientras se trataba de eliminar el recurso.';
$_lang['resource_err_save'] = 'Ocurrió un error mientras se trataba de guardar el recurso.';
$_lang['resource_err_select_parent'] = 'Por favor selecciona un recurso padre.';
$_lang['resource_err_symlink_target_invalid'] = 'El objetivo del Symlink (enlace simbólico) no contiene un valor entero.';
$_lang['resource_err_symlink_target_nf'] = 'No puedes enlazar simbólicamente a un recurso que no existe.';
$_lang['resource_err_symlink_target_self'] = 'You cannot symlink to itself.';
$_lang['resource_err_undelete'] = 'Ocurrió un error mientras se trataba de restaurar un recurso.';
$_lang['resource_err_undelete_children'] = 'Ocurrió un error mientras se trataban de restaurar los hijos del recurso.';
$_lang['resource_err_unpublish'] = 'Ocurrió un error mientras se trataba de despublicar el recurso.';
$_lang['resource_err_unpublish_sitestart'] = '¡El recurso está asignado a la variable site_start y no puede ser despublicado!';
$_lang['resource_err_unpublish_sitestart_dates'] = '¡El recurso está asignado a la variable site_start y no puede tener fechas de publicación o despublicación!';
$_lang['resource_err_weblink_target_nf'] = 'No se puede establecer un weblink a un recurso que no existe.';
$_lang['resource_err_weblink_target_self'] = 'No se puede establecer un weblink a sí mismo.';
$_lang['resource_folder'] = 'Contenedor';
$_lang['resource_folder_help'] = 'Selecciona esto para hacer que este Recurso también actúe como un Contenedor para otros Recursos. Un "Contenedor" es como una carpeta, pero también puede tener contenido.';
$_lang['resource_group_resource_err_ae'] = 'El recurso ya es parte de ese grupo de recursos.';
$_lang['resource_group_resource_err_nf'] = 'El recurso no es parte de ese grupo de recursos.';
$_lang['resource_hide_from_menus'] = 'Ocultar de los Menús';
$_lang['resource_hide_from_menus_help'] = 'Cuando está habilitado, el recurso <b>no</b> aparecerá en los menús de la web. Por favor, ten en cuenta que algunos Constructores de Menús podrían ignorar esta opción.';
$_lang['resource_link_attributes'] = 'Atributos de Enlace';
$_lang['resource_link_attributes_help'] = 'Atributos para el enlace de este recurso, tales como target="" o rel="".';
$_lang['resource_locked_by'] = 'Bloqueado por [[+user]]';
$_lang['resource_longtitle'] = 'Título Largo';
$_lang['resource_longtitle_help'] = 'Título largo para el recurso. Es bueno para los buscadores y puede ser más descriptivo para el recurso.';
$_lang['resource_menuindex'] = 'Índice de Menú';
$_lang['resource_menuindex_help'] = 'Orden del recurso en el árbol. Por lo general se utiliza para ordenar los recursos mostrados dinámicamente. Algunos componentes podrían ignorar esta configuración.';
$_lang['resource_menutitle'] = 'Título del Menú';
$_lang['resource_menutitle_help'] = 'El título del menú es un campo que puedes usar para mostrar un título corto para el recurso dentro de llamadas de snippets de menú.';
$_lang['resource_new'] = 'Recurso Nuevo';
$_lang['resource_notcached'] = 'Este recurso no ha sido copiado en caché (todavía).';
$_lang['resource_pagetitle'] = 'Título';
$_lang['resource_pagetitle_help'] = 'El nombre/título del recurso. ¡Evita usar barras ("/" o "\") en el nombre!';
$_lang['resource_parent'] = 'Recurso Padre';
$_lang['resource_parent_help'] = 'El ID del recurso padre.';
$_lang['resource_parent_select_node'] = 'Por favor, selecciona un nodo en el árbol de la izquierda.';
$_lang['resource_publish'] = 'Publicar';
$_lang['resource_publish_confirm'] = 'Al publicar este recurso ahora, eliminarás cualquier fecha de (des)publicación que haya sido configurada. Si deseas configurar o mantener las fechas de publicación o despublicación, en lugar de publicar escoge editar el recurso.<br /><br />¿Continuar?';
$_lang['resource_publishdate'] = 'Fecha de Publicación';
$_lang['resource_publishdate_help'] = 'Si configuras una fecha de publicación, el recurso será publicado cuando llegue la fecha de publicación. Haciendo clic en el icono del calendario se puede seleccionar una fecha o dejarlo en blanco de manera que el recurso nunca sea publicado automáticamente.';
$_lang['resource_published'] = 'Publicado';
$_lang['resource_published_help'] = 'Cuando se publique, el recurso está disponible al público inmediatamente después de ser guardado.';
$_lang['resource_publishedby'] = 'Publicado Por';
$_lang['resource_publishedon'] = 'Publicado En';
$_lang['resource_publishedon_help'] = 'La fecha en la cual el recurso fue publicado.';
$_lang['resource_refresh'] = 'Actualizar';
$_lang['resource_richtext'] = 'Texto Enriquecido';
$_lang['resource_richtext_help'] = 'Cuando se habilite, MODX usará el editor de texto enriquecido para editar recursos. Si tus recursos contienen Javascript y formularios, es recomendable no activarlo para que el editor no deshaga los recursos.';
$_lang['resource_searchable'] = 'Se puede buscar';
$_lang['resource_searchable_help'] = 'Cuando se habilite, el recurso podrá ser buscado. Esta configuración también puede ser usada para otros propósitos en los snippets.';
$_lang['resource_settings'] = 'Configuración de Recurso';
$_lang['resource_status'] = 'Estado';
$_lang['resource_status_help'] = 'Si se publica, el recurso está disponible al público inmediatemente después de ser guardado. De otra manera, estará oculto al público.';
$_lang['resource_summary'] = 'Resúmen (introducción)';
$_lang['resource_summary_help'] = 'Breve resúmen del recurso.';
$_lang['resource_syncsite'] = 'Vaciar Caché al Guardar';
$_lang['resource_syncsite_help'] = 'Cuando se habilite, MODX vaciará la caché después de guardar el recurso. De esta manera, los visitantes verán la versión actualizada del recurso.';
$_lang['resource_template'] = 'Plantilla';
$_lang['resource_template_help'] = 'La plantilla usada por el recurso.';
$_lang['resource_undelete'] = 'Recuperar';
$_lang['resource_unpublish'] = 'Despublicar';
$_lang['resource_unpublish_confirm'] = 'Al despublicar este recurso ahora, eliminarás cualquier fecha de (des)publicación que haya sido configurada. Si deseas configurar o mantener las fechas de publicación o despublicación, en lugar de publicar escoge editar el recurso.<br /><br />¿Continuar?';
$_lang['resource_unpublishdate'] = 'Fecha de Despublicación';
$_lang['resource_unpublishdate_help'] = 'Si configuras una fecha de despublicación, el recurso será despublicado cuando llegue la fecha de despublicación. Haciendo clic en el icono del calendario se puede seleccionar una fecha, dejándo el campo en blanco el recurso nunca será despublicado automáticamente.';
$_lang['resource_unpublished'] = 'Despublicado';
$_lang['resource_untitled'] = 'Recurso sin Título';
$_lang['resource_uri'] = 'URI';
$_lang['resource_uri_help'] = 'La URL relativa completa para este Recurso.';
$_lang['resource_uri_override'] = 'Congelar URI';
$_lang['resource_uri_override_help'] = 'Si se activa, se congelará la URI para este recurso con el valor del cuadro de texto presente debajo.';
$_lang['resource_with_id_not_found'] = '¡No se encontró el recurso con ID %s!';
$_lang['resource_view'] = 'Vista';
$_lang['show_sort_options'] = 'Mostrar Opciones de Ordenamiento';
$_lang['site_schedule'] = 'Horario';
$_lang['site_schedule_desc'] = 'Esto muestra los recursos que actualmente están programados para publicarse o despublicarse en fechas específicas. Se puede alternar la vista actual haciendo en el botón de la barra de herramientas.';
$_lang['source'] = 'Fuente';
$_lang['static_resource'] = 'Recurso Estático';
$_lang['static_resource_create_here'] = 'Recurso Estático';
$_lang['static_resource_new'] = 'Recurso Estático Nuevo';
$_lang['status'] = 'Estado';
$_lang['symlink'] = 'Symlink';
$_lang['symlink_create'] = 'Crear Symlink';
$_lang['symlink_create_here'] = 'Symlink';
$_lang['symlink_help'] = 'La dirección del objeto que se quiere referenciar con este Symlink. Si se quiere apuntar a un Recurso de MODX, introducir el ID aquí.';
$_lang['symlink_message'] = 'Un symlink es un enlace simbólico a otro recurso en el sitio al cual se referencia sin cambiar la URL.';
$_lang['symlink_new'] = 'Nuevo Symlink';
$_lang['template_variables'] = 'Variables de Plantilla';
$_lang['untitled_resource'] = 'Recurso sin Título';
$_lang['weblink'] = 'Weblink';
$_lang['weblink_create'] = 'Crear Weblink';
$_lang['weblink_create_here'] = 'Weblink';
$_lang['weblink_help'] = 'La dirección del objeto que se quiere referenciar con este Weblink. Si se quiere apuntar a un Recurso de MODX, introducir el ID aquí.';
$_lang['weblink_message'] = 'Un weblink es una referencia a un objeto en Internet. Esto puede ser un documento dentro de MODX, una página en otro sitio o una imagen u otro archivo de Internet.<p>';
$_lang['weblink_new'] = 'Nuevo Weblink';
$_lang['weblink_response_code'] = 'Código de Respuesta';
$_lang['weblink_response_code_help'] = 'El código de respuesta de HTTP que debería enviarse al weblink.';
