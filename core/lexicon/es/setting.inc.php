<?php
/**
 * Setting Spanish lexicon topic
 *
 * @language es_MX
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Área';
$_lang['area_authentication'] = 'Autentificación y Seguridad';
$_lang['area_caching'] = 'Caching';
$_lang['area_editor'] = 'Editor de Texto-Formateado';
$_lang['area_file'] = 'Sistema de Archivos';
$_lang['area_filter'] = 'Filtrar por área...';
$_lang['area_furls'] = 'URL Amigable';
$_lang['area_gateway'] = 'Portal';
$_lang['area_language'] = 'Léxico e Idioma';
$_lang['area_mail'] = 'Correo';
$_lang['area_manager'] = 'Admin (parte trasera)';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Sesión y Cookie';
$_lang['area_lexicon_string'] = 'Área Ingreso de Léxico';
$_lang['area_lexicon_string_msg'] = 'Ingresa la clave de la entrada de léxico para el área, aquí.  Si no hay entrada de léxico, sólo mostrará la clave del área.<br />Áreas Principales:<ul><li>autentificación</li><li>caching</li><li>archivo</li><li>furls</li><li>portal</li><li>idioma</li><li>administrador</li><li>sesión</li><li>sitio</li><li>sistema</li></ul>';
$_lang['area_site'] = 'Sitio';
$_lang['area_system'] = 'Sistema y Servidor';
$_lang['areas'] = 'Áreas';
$_lang['charset'] = 'Conjunto de Caractéres';
$_lang['country'] = 'País';
$_lang['namespace'] = 'Espacio de nombres';
$_lang['namespace_filter'] = 'Filtrar por espacio de nombres...';
$_lang['search_by_key'] = 'Buscar por clave...';
$_lang['setting_create'] = 'Crear Configuración Nueva';
$_lang['setting_err'] = 'Por favor revisa tus datos en los siguientes campos: ';
$_lang['setting_err_ae'] = 'La configuración con esa clave ya existe.  Por favor especifíca otro nombre de clave.';
$_lang['setting_err_nf'] = 'Configuración no encontrada.';
$_lang['setting_err_ns'] = 'Configuración no especificada';
$_lang['setting_err_remove'] = 'Ocurrió un error mientras se trataba de remover la configuración.';
$_lang['setting_err_save'] = 'Ocurrió un error mientras se trataba de guardar la configuración.';
$_lang['setting_err_startint'] = 'Las configuraciones no pueden comenzar con un entero.';
$_lang['setting_err_invalid_document'] = 'No hay un documento con ID %d.  Por favor especifíca un documento existente.';
$_lang['setting_remove'] = 'Remover Configuración';
$_lang['setting_remove_confirm'] = 'Estás seguro de que quieres remover esta configuración?  Esto puede romper tu instalación de MODX.';
$_lang['setting_update'] = 'Actualizar Configuración';
$_lang['settings_after_install'] = 'Como esta es una instalación nueva,  estás requerido de controlar estas configuraciones y cambiar cualquiera que desees.  Después de que hayas controlado las configuraciones, presiona \'Guardar\' para actualizar la base de datos de las configuraciones.<br /><br />';
$_lang['settings_desc'] = 'Aquí puedes configurar las preferencias generales y las configuraciones para la interfase del administrador de MODX, así como la manera en que tu sitio MODX funciona.  Haz clic dos veces en la columna del valor para la configuración que quieras editar para editarlo dinámicamente via la cuadrícula, o haz clic derecho en una configuración para ver más opciones.  También puedes hacer clic en signo de "+" para ver una descripción de la configuración.';
$_lang['settings_furls'] = 'URLs Amigables';
$_lang['settings_misc'] = 'Miscelaneos';
$_lang['settings_site'] = 'Sitio';
$_lang['settings_ui'] = 'Interfase &amp; Características';
$_lang['settings_users'] = 'Usuario';
$_lang['system_settings'] = 'Configuración del Sistema';
$_lang['usergroup'] = 'Grupo de Usuarios';

// user settings
$_lang['setting_allow_mgr_access'] = 'Acceso a la Interfase del Admin';
$_lang['setting_allow_mgr_access_desc'] = 'Selecciona esta opción para habilitar o deshabilitar el acceso a la interfase del admin. <strong>NOTA: Si esta opción está configurada a "no", entonces el usuario será redirigido a la página de Entrada de Admin o a la página de inicio del sitio.</strong>';

$_lang['setting_failed_login'] = 'Intentos de Entrada Fallidos';
$_lang['setting_failed_login_desc'] = 'Aquí puedes ingresar el número de intentos de entrada fallidos que son permitidos antes de que un usuario sea bloqueado.';

$_lang['setting_login_allowed_days'] = 'Días Permitidos';
$_lang['setting_login_allowed_days_desc'] = 'Selecciona los días que este usuario está permitido a entrar.';

$_lang['setting_login_allowed_ip'] = 'Dirección de IP Permitida';
$_lang['setting_login_allowed_ip_desc'] = 'Ingresa las direcciones de IP desde las cuales el usuario tiene permitido entrar. <strong>NOTA: Separa las direcciones de IP con una coma (,)</strong>';

$_lang['setting_login_homepage'] = 'Página de Inicio de Entrada';
$_lang['setting_login_homepage_desc'] = 'Ingresa el ID del documento al que quieres mandar al usuario después de que ha entrado. <strong>NOTA: asegúrate de que el ID que ingresas pertenece a un documento existente, que ha sido publicado y de que es accesible por este usuario</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Versión del Esquema de la Póliza de Acceso';
$_lang['setting_access_policies_version_desc'] = 'La versión del sistema de Pólizas de Acceso.  NO LO CAMBIES.';

$_lang['setting_allow_forward_across_contexts'] = 'Permitir Pasar Solicitudes a Traves de Contextos';
$_lang['setting_allow_forward_across_contexts_desc'] = 'Cuando es verdadero, los Symlinks y los llamados al API modx::sendForward() pueden pasar solicitudes a Recursos en otros Contextos';

$_lang['setting_allow_tags_in_post'] = 'Permitir Etiquetas HTML Tags en POST';
$_lang['setting_allow_tags_in_post_desc'] = 'Si es falso, todas las acciones POST dentro del admin quitarán cualquier etiqueta.  MODX recomienda dejar esta configuracion en verdadero.';

$_lang['setting_archive_with'] = 'Forzar Arvhivos PCLZip';
$_lang['setting_archive_with_desc'] = 'Si es verdadero, se usará PCLZip en vez de ZipArchive como la extensión zip.  Utiliza esto si estás obteniendo errores de extractTo o estás teniendo problemas descomprimiendo zips en el Administrador de Paquetes.';

$_lang['setting_auto_menuindex'] = 'Indexado de Menú prefijado';
$_lang['setting_auto_menuindex_desc'] = 'Selecciona \'Si\' para incrementar el índice de menú automáticamente de manera prefijada.';

$_lang['setting_auto_check_pkg_updates'] = 'Revisar automáticamente por Actualizaciones de Paquetes';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Si es \'Si\', MODX revisará automáticamente si hay actualizaciones de paquetes en el Administrador de Paquetes.  Esto puede hacer más lenta la carga de la cuadrícula.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Tiempo de Expiración del Cache para la Revisión Automática de Actualizaciónes de Paquetes';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'El número de minutos que el Administrador de Paquetes mantedrá en cahe los resultados de revisar por actualizaciones de paquetes.';

$_lang['setting_allow_multiple_emails'] = 'Permitir Direcciones Electrónicas Duplicadas para Usuarios';
$_lang['setting_allow_multiple_emails_desc'] = 'Si está habilitado, los Usuarios pueden compartir la misma dirección de email.';

$_lang['setting_automatic_alias'] = 'Generar automáticamente el alias';
$_lang['setting_automatic_alias_desc'] = 'Selecciona \'Si\' para que el sistema genere automáticamente el alias basado en el título de la página del Recurso cuando sea guardado.';

$_lang['setting_base_help_url'] = 'URL de Ayuda Base';
$_lang['setting_base_help_url_desc'] = 'El URL base por el cual construir los enlaces de Ayuda en la parte superior derecha de las páginas del Admin.';

$_lang['setting_blocked_minutes'] = 'Minutos Bloqueado';
$_lang['setting_blocked_minutes_desc'] = 'Aquí puedes ingresar el número de minutos que un usuario será bloqueado si alcanza su número permitido máximo de intentos de entrada fallidos.  Por favor ingresa este valor sólamente como un número (sin comas, espacios etc.)';

$_lang['setting_cache_action_map'] = 'Habilitar Cache de Mapa de Acción';
$_lang['setting_cache_action_map_desc'] = 'Cuando esté habilitado, las acciones (o mapas de controlador) serán cacheados para reducir los tiempos de carga de las páginas de administrador.';

$_lang['setting_cache_context_settings'] = 'Habilitar Cache de la Configuración de Contextos';
$_lang['setting_cache_context_settings_desc'] = 'Cuando esté habilitado, las configuraciones de contextos serán cacheadas para reducir los tiempos de carga.';

$_lang['setting_cache_db'] = 'Habilitar Cache de la Base de Datos';
$_lang['setting_cache_db_desc'] = 'Cuando esté habilitado, los objetos y los conjuntos de resultados de queries de SQL son cacheadas para reducir significativamente las cargas de la base de datos.';

$_lang['setting_cache_db_expires'] = 'Tiempo de Expiración del Cache de BdD';
$_lang['setting_cache_db_expires_desc'] = 'Este valor (en segundos) configura la cantidad de tiempo que duran los archivos de cache para el caching de BdD de conjunto de resultados.';

$_lang['setting_cache_default'] = 'Cacheable prefijado';
$_lang['setting_cache_default_desc'] = 'Selecciona \'Si\' para hacer todos los Recursos cacheables.';
$_lang['setting_cache_default_err'] = 'Por favor indica si quieres o no que los documentos sean cacheables.';

$_lang['setting_cache_disabled'] = 'Deshabilitar las Opciones Globales del Cache';
$_lang['setting_cache_disabled_desc'] = 'Selecciona \'Si\' para desahbilitar todas las características de cache de MODX.  MODX no recomienda deshabilitar caching.';
$_lang['setting_cache_disabled_err'] = 'Por favor indica si quieres o no que el cache esté habilitado.';

$_lang['setting_cache_expires'] = 'Tiempo de Expiración del Cache Prefijado';
$_lang['setting_cache_expires_desc'] = 'Este valor (en segundos) configura la cantidad de tiempo que los archivos de cache duran para el Cache prefijado.';

$_lang['setting_cache_format'] = 'Formato de Cache a Usar';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = serialize.  Uno de los formatos.';

$_lang['setting_cache_handler'] = 'Clase de Manejador de Caching';
$_lang['setting_cache_handler_desc'] = 'El nombre de la clase del manejador de tipos que se usará para caching.';

$_lang['setting_cache_lang_js'] = 'Cachear Palabras de JS del Léxico';
$_lang['setting_cache_lang_js_desc'] = 'Si está configurado a verdadero, esto usará los headers del servidor para cachear las palabras del léxico cargadas a Javascript para la interfase del admin.';

$_lang['setting_cache_lexicon_topics'] = 'Cachear Temas del Léxico';
$_lang['setting_cache_lexicon_topics_desc'] = 'Cuando esté habilitado, todos los Temas del Léxico serán cacheados para reducir en gran medida los tiempos de carga para la funcionalidad de Internacionalización.  MODX recomienda altamente dejar esto configurado a \'Si\'.';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Cachear Temas No Principales del Léxico';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Cuando esté deshabilitado, los Temas No Principales del Léxico no serán cacheados.  Esto es bueno deshabilitarlo cuando estés desarrollando tus propios Extras.';

$_lang['setting_cache_resource'] = 'Habilitar Cache Parcial de Recursos';
$_lang['setting_cache_resource_desc'] = 'El Cache Parcial de Recurosos es configurable por recurso cuando esta característica está habilitada.  Deshabilitando esta característica la deshabilitará globalmente.';

$_lang['setting_cache_resource_expires'] = 'Tiempo de Expiración para Cache Parcial de Recursos';
$_lang['setting_cache_resource_expires_desc'] = 'Este valor (en segundos) configura la cantidad de tiempo que los archivos de cache duran para el cache parcial de Recursos.';

$_lang['setting_cache_scripts'] = 'Habilitar Cache de Programas';
$_lang['setting_cache_scripts_desc'] = 'Cuando esté habilitado, MODX chacheará todos los programas (Snippets y Plugins) en un archivo para reducir los tiempos de carga.  MODX recomienda dejar esto configurado a \'Si\'.';

$_lang['setting_cache_system_settings'] = 'Habilitar Cache de Configuración del Sistema';
$_lang['setting_cache_system_settings_desc'] = 'Cuando esté habilitado, la configuración del sistema será cacheada para reducir los tiempos de carga.  MODX recomienda dejar esto activado.';

$_lang['setting_clear_cache_refresh_trees'] = 'Refrescar Árboles al Limpiar el Cache del Sitior';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'Cuando esté habilitado, refrescará los árboles después del limpiar el cache del sitio.';

$_lang['setting_compress_css'] = 'Usar CSS Comprimido';
$_lang['setting_compress_css_desc'] = 'Cuando esto está habilitado, MODX usará una versión comprimida de sus hojas de estilo de css en la interfase del admin.  Esto reduce enormemente los tiempos de carga y de ejecución dentro del admin.  Deshabilitalo sólo si estás modificando elementos principales.';

$_lang['setting_compress_js'] = 'Usar Librerías de Javascript Comprimidas';
$_lang['setting_compress_js_desc'] = 'Cuando esto está habilitado, MODX usara una versión comprimida de sus librerías personalizadas de Javascript en la interfase del admin.  Esto reduce enormemente los tiempos de carga y de ejecución dentro del admin.  Deshabilítalo sólo si estás modificando elementos principales.';

$_lang['setting_concat_js'] = 'Usar Librerías de Javascript Concatenadas';
$_lang['setting_concat_js_desc'] = 'Cuando esto está habilitado, MODX usara una versión concatenada de sus librerías personalizadas de Javascript en la interfase del admin.  Esto reduce enormemente los tiempos de carga y de ejecución dentro del admin.  Deshabilítalo sólo si estás modificando elementos principales.';

$_lang['setting_container_suffix'] = 'Sufijo del Contenedor';
$_lang['setting_container_suffix_desc'] = 'El sufijo para añadir los Recursos configurados como contenedores cuando se usa FURLs.';

$_lang['setting_cultureKey'] = 'Idioma';
$_lang['setting_cultureKey_desc'] = 'Selecciona el idioma para todos los Contextos menos el admin, incluyendo el contexto Web.';

$_lang['setting_custom_resource_classes'] = 'Clases de Recursos Personalizados';
$_lang['setting_custom_resource_classes_desc'] = 'Una lista de clases de Recursos personalizados separadas por comas.  Especifíca con clave_de_léxico_minusculas:className (Ej: recurso_wiki:WikiResource).  Todas las clases de Recursos personalizados deben de extender modResource.  Para especificar el lugar del controlador para cada clase, añade una configuración con [nombreDeClaseMinusculas]_delegate_path con la ruta del directorio de los archivos php de crear/editar.  Ej: wikiresource_delegate_path para la clase WikiResource que extiende modResource.';

$_lang['setting_default_template'] = 'Plantilla Prefijada';
$_lang['setting_default_template_desc'] = 'Selecciona la Plantilla prefijada que deseas usar para los Recursos nuevos.  Puedes seleccionar una plantilla diferente en el editor de Recursos, esta configuración solo pre-selecciona una de tus Plantillas.';

$_lang['setting_default_per_page'] = 'Por Página Prefijado';
$_lang['setting_default_per_page_desc'] = 'El número prefijado de resultados que mostrar en las cuadrículas por todo el admin.';

$_lang['setting_editor_css_path'] = 'Ruta al archivo CSS';
$_lang['setting_editor_css_path_desc'] = 'Ingresa la ruta a tu archivo de CSS que deseas usar dentro del editor de texto formateado.  La mejor manera de ingresar la ruta es desde la raíz de tu servidor, por ejemplo: /assets/site/style.css.  Si no deseas cargar una hoja de estilos en el editor de texto fromateado, deja este campo en blanco.';

$_lang['setting_editor_css_selectors'] = 'Selecctores de CSS para Editor';
$_lang['setting_editor_css_selectors_desc'] = 'Una lista separada por comas de selectores de CSS para el editor de texto formateado.';

$_lang['setting_emailsender'] = 'Dirección Desde del Email de Registro';
$_lang['setting_emailsender_desc'] = 'Aquí puedes especificar la dirección electrónica usada cuando se le envíen a los usuarios sus nombres de usuario y sus contraseñas.';
$_lang['setting_emailsender_err'] = 'Por favor indica la dirección de email de la administración.';

$_lang['setting_emailsubject'] = 'Tema del Email de Registro';
$_lang['setting_emailsubject_desc'] = 'La línea del tema para el email prefijado de registro para cuando un usuario se registre.';
$_lang['setting_emailsubject_err'] = 'Por favor indica la línea del tema para el email de registro.';

$_lang['setting_enable_dragdrop'] = 'Habilitar Arrastrar/Soltar en los árboles de Recursos/Elementos';
$_lang['setting_enable_dragdrop_desc'] = 'Si está desactivado, prevendrá arrastrar y soltar en los árboles de Recursos y Elementos.';

$_lang['setting_error_page'] = 'Página de Error';
$_lang['setting_error_page_desc'] = 'Ingresa el ID del documento al cual quieres mandar a los usuarios si solicitan un documento que no existe. <strong>NOTA: asegúrate que el ID que ingresas pertenece a un documento existente y que ha sido publicado!</strong>';
$_lang['setting_error_page_err'] = 'Por favor especifíca el ID de un documento el cual es la página de error.';

$_lang['setting_extension_packages'] = 'Paquetes de Extensiones';
$_lang['setting_extension_packages_desc'] = 'Un array JSON de paquetes a cargar en la instalación de MODX.  En el formato [{"nombredelpaquete":{ruta":"ruta/al/paquete"},{"otropaquete":{"ruta":"ruta/al/otropaquete"}}]';

$_lang['setting_failed_login_attempts'] = 'Intentos de Entrada Fallidos';
$_lang['setting_failed_login_attempts_desc'] = 'El número de intentos de entrada fallidos que un usuario tiene permitidos antes de ser \'bloqueado\'.';

$_lang['setting_fe_editor_lang'] = 'Idioma del Editor del Frente';
$_lang['setting_fe_editor_lang_desc'] = 'Escoge un idioma para el editor cuando sea usado como editor del frente.';

$_lang['setting_feed_modx_news'] = 'URL del Feed de Noticias de MODX';
$_lang['setting_feed_modx_news_desc'] = 'Configura el URL para el feed de RSS para el panel de noticias de MODX en el administrador.';

$_lang['setting_feed_modx_news_enabled'] = 'Habilitar el Feed de Noticias de MODX';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Si está en \'No\', MODX ocultará el feed de noticias en la sección de bienvenida del administrador.';

$_lang['setting_feed_modx_security'] = 'URL del Feed de Noticias de Seguridad de MODX';
$_lang['setting_feed_modx_security_desc'] = 'Configura el URL para el feed de RSS para el panel de noticias de seguridad de MODX en el administrador.';

$_lang['setting_feed_modx_security_enabled'] = 'Habilitar el Feed de Noticias de Seguridad de MODX';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Si está en \'No\', MODX ocultará el feed de noticias de seguridad en la sección de bienvenida del administrador.';

$_lang['setting_filemanager_path'] = 'Ruta de Admin de Archivos';
$_lang['setting_filemanager_path_desc'] = 'IIS normalmente no llena apropiadamente la configuración de document_root, la cual es usada por el admin de archivos para determinar lo que puedes ver.  Si estás teniendo problemas usando el admin de archivos, asegúrate de que esta ruta apunta a la raíz de tu instalación de MODX.';

$_lang['setting_filemanager_path_relative'] = 'Es la Ruta del Admin de Archivos Relativa?';
$_lang['setting_filemanager_path_relative_desc'] = 'Si tu configuración de filemanager_path es relativa a la ruta base_path de MODX, entonces por favor configura esto a Si.  Si tu filemanager_path está fuera del docroot, configura esto a No.';

$_lang['setting_filemanager_url'] = 'URL del Admin de Archivos';
$_lang['setting_filemanager_url_desc'] = 'Opcional.  Configura esto si quieres configurar una URL explícita para acceder a los archivos en el admin de archivos de MODX (es útil si has cambiado filemanager_path a una ruta fuera de la raíz web de MODX).  Asegúurate que esta sea una URL accesible en la web del valor configurado en filemanager_path.  Si dejas esto vacío, MODX tratará de calcularlo automáticamente.';

$_lang['setting_filemanager_url_relative'] = 'Es la URL del Admin de Archivos Relativa?';
$_lang['setting_filemanager_url_relative_desc'] = 'Si tu configuración de filemanager_url es relativa al base_url de MODX, entonces pr favor configura esto a Si.  Si tu filemanager_url está fuera de la raíz web principal, configur esto a No.';

$_lang['setting_forgot_login_email'] = 'Email de Olvidé Info de Entrada';
$_lang['setting_forgot_login_email_desc'] = 'La plantilla para el email que es enviado cuando un usuario ha olvidado su nombre de usuario y/o contraseña de MODX.';

$_lang['setting_forward_merge_excludes'] = 'Excluir Campos al Fusionar por SymLink';
$_lang['setting_forward_merge_excludes_desc'] = 'Un SymLink fusiona valores no vacíos de un campo sobre los valores en el Recurso objetivo; usando esta lista separada por comas de exclusiones, previene que los campos especificados sean sobre-escritos por el SymLink.';

$_lang['setting_friendly_alias_lowercase_only'] = 'Aliases de FURL en Minúsculas';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Determina si se permiten sólo caractéres en minúsculas en el alias de un Recurso.';

$_lang['setting_friendly_alias_max_length'] = 'Longitud Máxima de Alias de FURL';
$_lang['setting_friendly_alias_max_length_desc'] = 'Si es mayor que cero, es el número máximo de caractéres permitidos en el alias de un Recurso.  Cero significa un valor ilimitado.';

$_lang['setting_friendly_alias_restrict_chars'] = 'Método de Restricción de Caractéres del Alias de FURL';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'El método usado para restringir caractéres usados en el alias de un Recurso. "patrón" permite que un patrón de RegEx sea usado, "legal" permite cualquier caracter legal en URLs, "alfa" sólo permite letras de alfabeto y "alfanumérico" sólo permite letras y números.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'Patrón de Restricción de Caractéres del Alias de FURL';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Un patrón de RegEx válido para restringir los caractéres usados en el alias de un Recurso.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'Quitar Etiquetas de Elemento en el Alias de FURL';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Determina is las etiquetas de Elementos deben de ser removidas del alias de un Recurso.';

$_lang['setting_friendly_alias_translit'] = 'Transliteración del Alias de FURL';
$_lang['setting_friendly_alias_translit_desc'] = 'El método de transliteración a usar en el alias especificado para un Recurso.  Si está en vacío o "ninguno" es el valor prefijado, entonces la transliteración es saltada.  Otros valores posibles son "iconv" (si está disponible) o el nombre de una tabla de transliteración  proveída por una clase de servicio personalizada de transliteración.';

$_lang['setting_friendly_alias_translit_class'] = 'Clase de Servisio de Transliteración del Alias de FURL';
$_lang['setting_friendly_alias_translit_class_desc'] = 'Una clase de servicio opcional para proveer servicios de transliteración para la generación/filtrado de Alias de FURL.';

$_lang['setting_friendly_alias_translit_class_path'] = 'Ruta de la Clase de Servicio de Transliteración del Alias de FURL';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'El lugar del modelo de donde el paquete donde la Clase de Servicio de Transliteración del Alias de FURL será cargado.';

$_lang['setting_friendly_alias_trim_chars'] = 'Caractéres a Cortar del Alias de FURL';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Los caractéres a cortar de los lados del alias de un Recurso.';

$_lang['setting_friendly_alias_word_delimiter'] = 'Delimitador de Palabras del Alias de FURL';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'El delimitador de palabras preferido para el alias en URLs amigables.';

$_lang['setting_friendly_alias_word_delimiters'] = 'Delimitadores de Palabras en Alias de FURL';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Los caractéres que representan los delimitadores de palabras cuando se procesan loa alias de URLs amigables.  Estos caractéres serán convertidos y consolidados al delimitador de palabras preferido para alias de FURLs.';

$_lang['setting_friendly_urls'] = 'Usar URLs Amigables';
$_lang['setting_friendly_urls_desc'] = 'Esto te permite usar URLs amigables para los buscadores con MODX.  Por favor nota, esto sólo funciona para instalaciones de MODX que corren en Apache y necesitarás escribir un archivo .htaccess para que esto funcione.  Ve el archivo .htaccess incluido en la distribución para más información.';
$_lang['setting_friendly_urls_err'] = 'Por favor indica si quieres o no usar URLs amigables.';

$_lang['setting_global_duplicate_uri_check'] = 'Revisar por URIs Duplicados a Través de Todos los Contextos';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Selecciona \'Si\' para que las revisiones de URIs duplicados incluyan todos los Contextos en la búsqueda.  De otra manera, sólo el Contexto en el que se esté guardando el Recurso es revisado.';

$_lang['setting_hidemenu_default'] = 'Ocultar de los Menús';
$_lang['setting_hidemenu_default_desc'] = 'Selecciona \'Si\' para ocultar todos los recursos nuevos de los menús.';

$_lang['setting_link_tag_scheme'] = 'Esquema de Generación de URLs';
$_lang['setting_link_tag_scheme_desc'] = 'Esquema de generación de URLs para la etiqueta [[~id]].  Opciones disponibles:  <a href="http://api.modxcms.com/modx/modX.html#makeUrl">http://api.modxcms.com/modx/modX.html#makeUrl</a>';

$_lang['setting_mail_charset'] = 'Conjunto de Caractéres del Correo';
$_lang['setting_mail_charset_desc'] = 'El conjunto de caractéres prefijado para los emails, p.e. \'iso-8859-1\' o \'utf-8\'';

$_lang['setting_mail_encoding'] = 'Codificación del Correo';
$_lang['setting_mail_encoding_desc'] = 'Configura la Codificación del mensaje.  Las opciones para esto son: "8-bit", "7bit", "binary", "base64", y "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Usar SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Si es verdadero, MODX intentará usar SMTP en las funciones de correo.';

$_lang['setting_mail_smtp_auth'] = 'Autentificación SMTP';
$_lang['setting_mail_smtp_auth_desc'] = 'Configura la autentificación de SMTP.  Utiliza las configuraciones mail_smtp_user y mail_smtp_pass.';

$_lang['setting_mail_smtp_helo'] = 'Mensaje HELO de SMTP';
$_lang['setting_mail_smtp_helo_desc'] = 'Configura el HELO SMTP del mensaje (Se prefija a el nombre del host).';

$_lang['setting_mail_smtp_hosts'] = 'Hosts de SMTP';
$_lang['setting_mail_smtp_hosts_desc'] = 'Configura los hosts de SMTP.   Todos los hosts deben de estar separados por un punto y coma.  También puedes especificar un puerto diferente para cada host usando el siguiente formato: [nombredelhost:puerto] (p.e. "smtp1.ejemplo.com:25;smtp2.ejemplo.com").  El uso de los hosts será intentado en el orden en que aparecen.';

$_lang['setting_mail_smtp_keepalive'] = 'Mantener-Vivo SMTP';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Previene que la conexión de SMTP sea cerrada después de enviar cada email.  No recomendable.';

$_lang['setting_mail_smtp_pass'] = 'Contraseña de SMTP';
$_lang['setting_mail_smtp_pass_desc'] = 'La contraseña para autentificar SMTP.';

$_lang['setting_mail_smtp_port'] = 'Puerto de SMTP';
$_lang['setting_mail_smtp_port_desc'] = 'Configura el puerto prefijado del servidor de SMTP.';

$_lang['setting_mail_smtp_prefix'] = 'Prefijo de Conexión de SMTP';
$_lang['setting_mail_smtp_prefix_desc'] = 'Configura el prefijo de conexión.  Las opciones son: "", "ssl" or "tls"';

$_lang['setting_mail_smtp_single_to'] = 'Un Solo Receptor de SMTP';
$_lang['setting_mail_smtp_single_to_desc'] = 'Provee la habilidad de que el campo A procese emails individuales, en vez de enviar todas las direcciones.';

$_lang['setting_mail_smtp_timeout'] = 'Tiempo de Expiración de SMTP';
$_lang['setting_mail_smtp_timeout_desc'] = 'Configura el tiempo de expiración del servidor de SMTP en segundos.  Esta función no funcionará en servidores win32.';

$_lang['setting_mail_smtp_user'] = 'Usuario de SMTP';
$_lang['setting_mail_smtp_user_desc'] = 'El usuario a autentificar en SMTP.';

$_lang['setting_manager_direction'] = 'Dirección del Texto en el Admin';
$_lang['setting_manager_direction_desc'] = 'Elige la dirección en la cual será mostrado el texto en el Admin, izquierda a derecha o derecha a izquierda.';

$_lang['setting_manager_date_format'] = 'Formato de Fechas del Admin';
$_lang['setting_manager_date_format_desc'] = 'La palabra de formato, en formato de date() de PHP, para las fechas representadas en el admin.';

$_lang['setting_manager_favicon_url'] = 'URL del Favicon del Admin';
$_lang['setting_manager_favicon_url_desc'] = 'Si está configurado, cargará este URL como e favicon para el Admin de MODX.  Debe de ser un URL relativo al directorio  manager/, o un URL absoluto.';

$_lang['setting_manager_lang_attribute'] = 'Atributo de Idioma de HTML y XML del Admin';
$_lang['setting_manager_lang_attribute_desc'] = 'Ingresa el código de idioma que mejor encaja con el idioma que eligiste para el admin,  esto asegurará que el navegador pueda presentar contenido en el mejor formato para tí.';

$_lang['setting_manager_language'] = 'Idioma del Admin';
$_lang['setting_manager_language_desc'] = 'Selecciona el idioma para el Admin de Contenido de MODX.';

$_lang['setting_manager_login_start'] = 'Entrada al Admin';
$_lang['setting_manager_login_start_desc'] = 'Ingresa el ID del documento al que quieren enviar al usuario después de que ha entrado en el Admin.  <strong>NOTA: asegúrate de que el ID que ingresaste pertenece a un documento existente, de que ha sido publicado y de que es accesible al usuario!</strong>';

$_lang['setting_manager_theme'] = 'Tema del Admin';
$_lang['setting_manager_theme_desc'] = 'Selecciona el Tema para el Admin de Contenido.';

$_lang['setting_manager_time_format'] = 'Formato del Tiempo en el Admin';
$_lang['setting_manager_time_format_desc'] = 'La palabra de formato, en formato de date() de PHP, para las configuraciones del tiempo representadas en el Admin.';

$_lang['setting_manager_use_tabs'] = 'Usar Pestañas en el Diseño del Admin';
$_lang['setting_manager_use_tabs_desc'] = 'Si es verdadero, el Admin usará pestañas para mostrar los páneles de contenido.  De otra manera, usará portales.';

$_lang['setting_modRequest.class'] = 'Clase Manejadora de Solicitudes';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_charset'] = 'Codificación de Caractéres';
$_lang['setting_modx_charset_desc'] = 'Por favor selecciona que codificación de caractéres deseas usar.  Por favor nota que MODX ha sido probado con un buen número de estas codificaciones, pero no con todas ellas.  Para la mayoría de los idiomas, la configuración prefijada de UTF-8 es la preferida.';

$_lang['setting_new_file_permissions'] = 'Permisos de Archivo Nuevo';
$_lang['setting_new_file_permissions_desc'] = 'Cuando se carga un archivo nuevo en el Admin de Archivos, el Admin de Archivos intentará cambiar los permisos del archivo a aquellos ingresados en esta configuración.  Esto puede no funcionar en algunas instalaciones, tales com IIS, en cuyo caso necesitarás cambiar los permisos manualmente.';

$_lang['setting_new_folder_permissions'] = 'Permisos de Carpeta Nueva';
$_lang['setting_new_folder_permissions_desc'] = 'Cuando se crea una carpeta nueva en el Admin de Archivos, el Admin de Archivos intentará cambiar los permisos de la carpeta aquellos ingresados en esta configuración.  Esto puede no funcionar en algunas instalaciones, tales com IIS, en cuyo caso necesitarás cambiar los permisos manualmente.';

$_lang['setting_password_generated_length'] = 'Longitud de Contraseña Auto-Generada';
$_lang['setting_password_generated_length_desc'] = 'La longitud de la contraseña auto-generada para un Usuario.';

$_lang['setting_password_min_length'] = 'Longitud Mínima de la Contraseña';
$_lang['setting_password_min_length_desc'] = 'La longitud mínima de una contraseña para un Usuario.';

$_lang['setting_principal_targets'] = 'Objetivos de ACL a Cargar';
$_lang['setting_principal_targets_desc'] = 'Personalizar los objetivos de ACL a cargar por los Usuarios de MODX.';

$_lang['setting_proxy_auth_type'] = 'Tipo de Autentificación de Proxy';
$_lang['setting_proxy_auth_type_desc'] = 'Soporta BASIC o NTLM.';

$_lang['setting_proxy_host'] = 'Host del Proxy';
$_lang['setting_proxy_host_desc'] = 'Si tu servidor está usando un proxy, configura el nombre del host para habilitar las características de MODX que puedan ser necesarias para usar el proxy, tales como el Admin de Paquetes.';

$_lang['setting_proxy_password'] = 'Contraseña del Proxy';
$_lang['setting_proxy_password_desc'] = 'La contraseña requerida para autentificarte a tu servidor de proxy.';

$_lang['setting_proxy_port'] = 'Puerto de Proxy';
$_lang['setting_proxy_port_desc'] = 'El puerto para tu servidor de proxy.';

$_lang['setting_proxy_username'] = 'Nombre de usaurio del Proxy';
$_lang['setting_proxy_username_desc'] = 'El nombre de usuario para autentificarte con tu servidor de proxy.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb Permitir src Arriba de la Raíz de Documentos';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Indica si la ruta de src es permitida afuera de la raíz de documentos.  Esto es útil para instalaciones multi-contextos con múltiples hosts virtuales.';

$_lang['setting_phpthumb_cache_maxage'] = 'phpThumb Edad Máxima del Cache';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Borrar imagenes miniatura en el cache que no han sido accesadas en mas de X días.';

$_lang['setting_phpthumb_cache_maxsize'] = 'phpThumb Tamaño Máximo del Cache';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Borrar las imágenes miniatura menos accesadas recientemente cuando el cache crezca más grande que X megabytes de tamaño.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'phpThumb Archivos Máximos del Cache';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Borrar las imágenes miniatura menos accesadas recientemente cuando el cache tenga más de X archivos.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'phpThumb Archivos Fuente del Cache';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Si o no poner en el cache los archivos fuente conforme se van cargando. Recomendable desactivar.';

$_lang['setting_phpthumb_document_root'] = 'Raíz de Documento de PHPThumb';
$_lang['setting_phpthumb_document_root_desc'] = 'Configura esto si estás experimentando problemas con la variable de servidor DOCUMENT_ROOT, u obteniendo errores con OutputThumbnail o !is_resource.  Configúralo a la ruta absoluta de la raíz del documento que quieres usar.  Si está vacío, MODX usará la variable de servidor DOCUMENT_ROOT.';

$_lang['setting_phpthumb_error_bgcolor'] = 'phpThumb Color del Fondo de los Errores';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'Un valor hexadecimal, sin el símbolo #, el cual indica el color de fondo para los errores de phpThumb.';

$_lang['setting_phpthumb_error_fontsize'] = 'phpThumb Tamaño de Tipografía de los Errores';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'Un valor em indicando un tamaño de tipografía a usar para el texto que aparece en los errores de phpThumb.';

$_lang['setting_phpthumb_error_textcolor'] = 'phpThumb Color de la Tipografía de los Errores';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'Un valor hexadecimal, sin el símbolo #, el cual indica el color de la tipografía para el texto que aparece en los errores de phpThumb.';

$_lang['setting_phpthumb_far'] = 'phpThumb Forzar la Relación de Aspecto';
$_lang['setting_phpthumb_far_desc'] = 'La configuracion prefijada de FRA para phpThumb cuando es usado en MODX.  Se configura prefijadamente a C para forzar la relación de aspecto hacia el centro.';

$_lang['setting_phpthumb_imagemagick_path'] = 'phpThumb Ruta de ImageMagick';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Opcional.  Configurar una ruta alternativa a ImageMagick para generar imágenes miniatura con phpThumb, si es que no está en la configuración prefijada de PHP.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb Deshabilitar Hotlinking';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Servidores remotos están permitidos en el parámetro src a menos de que deshabilites hotlinking en phpThumb.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb Borrar Imagen para Hotlinking';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Indica si una imagen generada desde un servidor remoto deberá ser borrada cuando no esté permitida.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb Mensaje de No Permitido para Hotlinking';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'Un mensaje que es mostrado en lugar de la imágen miniatura cuando un intento de hotlinkins es rechazado.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb Dominios Válidos para Hotlinking';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'Una lista de nombres de hosts separada por comas que son válidos en URLs src.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb Deshabilitar Enlaces Fuera del Sitio';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Deshabilita la habilidad de otros de usar phpThumb para mostrar imágenes en sus sitios.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb Borrar Imagen en Enlaces Fuera del Sitio';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Indica si una imagen enlazada desde un servidor remoto deberá ser borrada cuando no esté permitida.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb Requerir Referidor en Enlaces Fuera del Sitio';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'Si está habilitado, cualquier intento de enlace fuera del sitio sin un header referidor válido será rechazado.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb Mensaje de No Permitido para Enlaces de Fuera del Sitio';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'Un mensaje que es mostrado en lugar de la imágen miniatura cuando un intento de enlace de fuera del sitio es rechazado.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb Dominios Válidos para Enlaces Fuera del Sitio';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'Una lista separada por comas de los nombres de host que son referidores válidos para enlaces de fuera del sitio.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb Fuente de la Marca de Agua de Enlaces Fuera del Sitio';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Opcional.  Una ruta válida del sistema de archivos a usar como la fuente de la marca de agua cuando tus imágenes sean mostradas fuera del sitio por phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb Zoom-Recortar';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'EL ZC prefijado para phpThumb cuando es usado en MODX.  El valor prefijado es 0 para prevenir zoom y recortar.';

$_lang['setting_publish_default'] = 'Valor Prefijado de Publicar';
$_lang['setting_publish_default_desc'] = 'Selecciona \'Si\' para hacer todos los recursos nuevos publicados.';
$_lang['setting_publish_default_err'] = 'Por favor indica si quieres o no que los documentos sean publicados desde su creación.';

$_lang['setting_rb_base_dir'] = 'Ruta de Recursos';
$_lang['setting_rb_base_dir_desc'] = 'Ingresa la ruta física al directorio de los recursos.  Esta configuración es normalmente generada automáticamente.  Si estás usando IIS, sin embargo, MODX tal vez no pueda decifrar la ruta, causando que el Navegador de Recursos muestre un error.  En ese caso, puedes ingresar la ruta al directorio de imágenes aquí (la ruta como la ves en el Explorador de Windows). <strong>NOTA:</strong> El directorio de recursos con tiene las sub-carpetas de imágenes, archivos, flash y media de manera que el navegador de recursos funcione correctamente.';
$_lang['setting_rb_base_dir_err'] = 'Por favor indica el directorio base del navegador de recursos.';
$_lang['setting_rb_base_dir_err_invalid'] = 'Este recurso no existe o no puede ser accesado.  Por favor indica un directorio válido o ajusta los permisos de este directorio.';

$_lang['setting_rb_base_url'] = 'URL de Recursos';
$_lang['setting_rb_base_url_desc'] = 'Ingresa la ruta virtual del directorio de recursos.  Esta configuración es normalmente generada automáticamente.  Si estás usando IIS, sin embargo, MODX podría no poder decifrar el URL, causando que el Navegador de Recursos muestre un error.  En este caso,  puedes ingresarel URL del directorio de imágenes aquí (el URL como lo ingresarías en el Explorador de Internet.';
$_lang['setting_rb_base_url_err'] = 'Por favor indica el URL base del navegador de recursos.';

$_lang['setting_request_controller'] = 'Nombre de Archivo del Controlador de Solicitudes';
$_lang['setting_request_controller_desc'] = 'El nombre de archivo del controlador de solicitudes principal desde el cual MODX es cargado.  La mayoría de los usuarios pueden dejar esto como index.php.';

$_lang['setting_request_param_alias'] = 'Parámetro de Alias de Solicitud';
$_lang['setting_request_param_alias_desc'] = 'El nombre del parámetro GET para identificar los aliases del Recurso cuando se direccione con FURLs.';

$_lang['setting_request_param_id'] = 'Parámetro de ID de Solicitud';
$_lang['setting_request_param_id_desc'] = 'El nombre del parámetro GET para identificar los IDs del Recurso cuando no se estén usando FURLs.';

$_lang['setting_resolve_hostnames'] = 'Resolver Nombres de Host';
$_lang['setting_resolve_hostnames_desc'] = 'Quieres que MODX trate de resolver los nombres de hosts de tus visitantes cuando ellos visiten tu sitio?  El resolver los nombres de hosts puede crear una carga extra para el servidor, aunque tus visitantes no lo notarán en ninguna manera.';

$_lang['setting_resource_tree_node_name'] = 'Campo de Nodo del Árbol de Recursos';
$_lang['setting_resource_tree_node_name_desc'] = 'Especifica el campo del Recurso a usar cuando se generen los nodos en el Árbol de Recursos.  El valor prefijado es pagetitle, aunque cualquier campo del Recurso puede ser usado, tal como menutitle, alias, longtitle, etc.';

$_lang['setting_resource_tree_node_tooltip'] = 'Campo de Tips del Árbol de Recursos';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Especifíca el campo de Recurso a usar cuando se muestran los nodos en el Árbol de Recursos.  Cualquier campo de Recursos puede ser usado, tal como menutitle, alias, longtitle, etc.  Si está en blanco, será usado el longtitle con una descripción abajo.';

$_lang['setting_richtext_default'] = 'Texto Formateado Prefijado';
$_lang['setting_richtext_default_desc'] = 'Selecciona \'Si\' para hacer que todos los recursos nuevos utilicen el Editor de Texto Formateado.';

$_lang['setting_search_default'] = 'Buscable Prefijado';
$_lang['setting_search_default_desc'] = 'Selecciona \'Si\' para hacer que todos los recursos nuevos sean buscables.';
$_lang['setting_search_default_err'] = 'Por favor especifica si quieres o no que los documentos sean buscables.';

$_lang['setting_server_offset_time'] = 'Tiempo de Diferencia del Servidor';
$_lang['setting_server_offset_time_desc'] = 'Selecciona el número de horas de diferencia que existe entre donde tú te encuentras y donde se encuentra el servidor.';

$_lang['setting_server_protocol'] = 'Tipo de Servidor';
$_lang['setting_server_protocol_desc'] = 'Si tu sitio está en una conexión https, por favor especifícalo aquí.';
$_lang['setting_server_protocol_err'] = 'Por favor especifica si tu sitio es un sitio seguro.';
$_lang['setting_server_protocol_http'] = 'http';
$_lang['setting_server_protocol_https'] = 'https';

$_lang['setting_session_cookie_domain'] = 'Dominio de Cookie de Sesión';
$_lang['setting_session_cookie_domain_desc'] = 'Usa esta configuración para personalizar el dominio de la cookie de la sesión.  Déjalo en blanco para usar el dominio actual.';

$_lang['setting_session_cookie_lifetime'] = 'Tiempo de Vida de la Cookie de Sesión';
$_lang['setting_session_cookie_lifetime_desc'] = 'Usa esta configuración para personalizar el tiempo de vida de la cookie de sesión en segundos.  Esto es usado para configurar el tiempo de vida de una cookie de sesión de un cliente cuando ellos eligen la opción de \'recuérdame\' en la entrada.';

$_lang['setting_session_cookie_path'] = 'Ruta de la Cookie de Sesión';
$_lang['setting_session_cookie_path_desc'] = 'Usa esta configuración para personalizar la ruta de cookies para identificar las cookies de sesión específicas del sitio.  Déjalo en blanco para usar MODX_BASE_URL.';

$_lang['setting_session_cookie_secure'] = 'Cookies de Sesión Seguras';
$_lang['setting_session_cookie_secure_desc'] = 'Habilitar esta configuración para usar cookies de sesión seguras.';

$_lang['setting_session_handler_class'] = 'Nombre de Clase del Manejador de Sesión';
$_lang['setting_session_handler_class_desc'] = 'Para sesiones administradas en base de datos, usa \'modSessionHandler\'.  Deja esto en blanco para usar administración de sesiones estándar de PHP.';

$_lang['setting_session_name'] = 'Nombre de Sesión';
$_lang['setting_session_name_desc'] = 'Usa esta configuración para personalizar el nombre de sesión usado para las sesiones en MODX.  Déjalo en blanco para usar el valor de nombre de sesión prefijado de PHP.';

$_lang['setting_settings_version'] = 'Versión de Configuraciónes';
$_lang['setting_settings_version_desc'] = 'La versión de MODX actualmente instalada.';

$_lang['setting_settings_distro'] = 'Distribución de Configuraciones';
$_lang['setting_settings_distro_desc'] = 'La distribución de MODX actualmente instalada.';

$_lang['setting_set_header'] = 'Configurar Headers de HTTP';
$_lang['setting_set_header_desc'] = 'Cuando está habilitado, MODX intentará configurar los headers de HTTP para Recursos.';

$_lang['setting_signupemail_message'] = 'Email de Registro';
$_lang['setting_signupemail_message_desc'] = 'Aquí puedes configurar el mensaje que será enviado a tus usuarios cuando creas una cuenta para ellos y dejas que MODX les envíe un email conteniendo sus nombres de usuario y su contraseña.  <br /><strong>NOTA:</strong> Las siguientes variables son reemplazadas por el Admin de Contenido cuando el mensaje es enviado:  <br /><br />[[+sname]] - Nombre de tu sitio web, <br />[[+saddr]] - La dirección electrónica de tu sitio web, <br />[[+surl]] - El URL de tu sitio, <br />[[+uid]] - El nombre de entrada o ID del usuario, <br />[[+pwd]] - La contraseña del usuario, <br />[[+ufn]] - El nombre completo del usuario. <br /><br /><strong>Deja el [[+uid]] y el [[+pwd]] en el email, o el nombre de usuario y la contraseña no serán enviados en el email y tus usuarios no sabrán su nombre de usuario o contraseña!</strong>';
$_lang['setting_signupemail_message_default'] = 'Hola [[+uid]] \n\nAquí están tus detalle de entrada para el Admin de Contenido de [[+sname]]:\n\nNombre de Usuario: [[+uid]]\nContraseña: [[+pwd]]\n\nUna vez que entres en el Admin de Contenido ([[+surl]]), puedes cambiar tu contraseña.\n\nSaludos,\nEl Administrador del Sitio';

$_lang['setting_site_name'] = 'Nombre del Sitio';
$_lang['setting_site_name_desc'] = 'Ingresa aquí el nombre de tu sitio.';
$_lang['setting_site_name_err']  = 'Por favor ingresa un nombre del sitio.';

$_lang['setting_site_start'] = 'Inicio del Sitio';
$_lang['setting_site_start_desc'] = 'Ingresa aquí el ID del Recurso que tu quieres usar como la página de inicio.  <strong>NOTA: asegúrate de que este ID que ingresaste pertenece a un Recurso existente, y de que ha sido publicado!</strong>';
$_lang['setting_site_start_err'] = 'Por favor especifíca un ID de Recurso que es el inicio del sitio.';

$_lang['setting_site_status'] = 'Estado del Sitio';
$_lang['setting_site_status_desc'] = 'Selecciona \'Si\' para publicar tu sitio en la web.  Si seleccionas \'No\', tus visitantes verán el \'Mensaje de Sitio No Disponible\', y no podrán navegar por el sitio.';
$_lang['setting_site_status_err'] = 'Por favor selecciona si que sitio está en línea (Si) o fuera de línea (No).';

$_lang['setting_site_unavailable_message'] = 'Mensaje de Sitio No Disponible';
$_lang['setting_site_unavailable_message_desc'] = 'El mensaje que se mostrará cuando el sitio esté fuera de línea o si ocurre un error. <strong>NOTA: Este mensaje sólo será mostrado si la opción de página de Sitio No Disponible no está configurada.</strong>';

$_lang['setting_site_unavailable_page'] = 'Página de Sition No Disponible';
$_lang['setting_site_unavailable_page_desc'] = 'Ingresa aquí el ID del Recurso que quieres usar como una página fuera de línea.  <strong>NOTA: asegúrate de que este ID que ingresaste pertenece a un Recurso existente y de que ha sido publicado!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Por favor especifíca el ID del documento para la págin de Sitio No Disponible.';

$_lang['setting_strip_image_paths'] = 'Re-escribir rutas del navegador?';
$_lang['setting_strip_image_paths_desc'] = 'Si esto está configurado a \'No\', MODX escribirá las fuentes de recursos (imágenes, archivos, flash, etc.) del navegador de recursos como URLs absolutas.  Las URLs relativas son útiles si quisieras mover tu instalación de MODX, p.e., de un sitio en desarrollo a un sitio en producción.  Si no tienes idea de lo que esto significa, es mejor dejarlo configurado a \'Si\'.';

$_lang['setting_symlink_merge_fields'] = 'Combinar Campos de Recurso en SymLinks';
$_lang['setting_symlink_merge_fields_desc'] = 'Si está configurado a Si, combinará automáticamente campos no vacíos con el recurso objetivo cuando se use forwarding usando SymLinks.';

$_lang['setting_topmenu_show_descriptions'] = 'Mostrar Descripciones en el Menú Superior';
$_lang['setting_topmenu_show_descriptions_desc'] = 'Si se configura a \'No\', MODX ocultará las descripciones de los artículos del menú superior en el Admin.';

$_lang['setting_tree_default_sort'] = 'Campo de Ordenamiento Prefijado del Árbol de Recursos';
$_lang['setting_tree_default_sort_desc'] = 'El campo de ordenamiento prefijado para el árbol de recursos cuando se carga el Administrador.';

$_lang['setting_tree_root_id'] = 'ID de la Raíz de Árbol';
$_lang['setting_tree_root_id_desc'] = 'Configura esto a un ID válido de un Recurso para iniciar el árbol de Recursos de la izquierda tomando este nodo como la raíz.  El usuario sólo podra ver Recursos que son hijos del Recurso especificado.';

$_lang['setting_udperms_allowroot'] = 'Permitir Raíz';
$_lang['setting_udperms_allowroot_desc'] = 'Quieres permitir a tus usuarios que creen Recursos nuevos en la raíz de tu sitio? ';

$_lang['setting_unauthorized_page'] = 'Página No Autorizado';
$_lang['setting_unauthorized_page_desc'] = 'Ingresa el ID del Recurso al cual tu quieres enviar a los usuarios si han solicitado un Recurso seguro o no autorizado.  <strong>NOTA: asegúrate de que el ID que ingresas pertenece a un Recurso existente,de que ha sido publicado y es públicamente accesible!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Por favor especifíca un ID de Recurso para la página de No Autorizado.';

$_lang['setting_upload_files'] = 'Tipo de Archivos Cargables';
$_lang['setting_upload_files_desc'] = 'Aquí puedes ingresar una lista de archivos que pueden ser cargados en \'assets/files/\' usando el Admin de Recursos.  Por favor ingresa las extensiones para los tipos de archivos, separadas por una coma.';

$_lang['setting_upload_flash'] = 'Tipos de Flash Cargables';
$_lang['setting_upload_flash_desc'] = 'Aquí puedes ingresar una lista de archivos que pueden ser cargados en \'assets/flash/\' usando el Admin de Recursos.  Por favor ingresa las extensiones para los tipos de flash, separadas por una coma.';

$_lang['setting_upload_images'] = 'Tipos de Imágenes Cargables';
$_lang['setting_upload_images_desc'] = 'Aquí puedes ingresar una lista de archivos que pueden ser cargados en \'assets/images/\' usando el Admin de Recursos.  Por favor ingresa las extensiones para los tipos de imágenes, separadas por una coma.';

$_lang['setting_upload_maxsize'] = 'Tamaño Máximo de Carga';
$_lang['setting_upload_maxsize_desc'] = 'Ingresa el tamaño máximo de archivo que puede ser cargado via el admin de archivos.  El tamaño de archivo debe de ser ingresado en bytes.  <strong>NOTA: Los archivos grandes pueden tomar un tiempo muy largo en ser cargados!</strong>';

$_lang['setting_upload_media'] = 'Tipos de Medios Cargables';
$_lang['setting_upload_media_desc'] = 'Aquí puedes ingresar una lista de archivos que pueden ser cargados en \'assets/media/\' usando el Admin de Recursos.  Por favor ingresa las extensiones para los tipos de medios, separadas por una coma.';

$_lang['setting_use_alias_path'] = 'Usar la Ruta de Alias Amigable';
$_lang['setting_use_alias_path_desc'] = 'Configurando esta opción a \'Si\' mostrará la ruta completa al Recurso si el Recurso tiene un alias.  Por ejemplo, si un Recurso con un alias de \'hijo\' se encuentra dentro de un Recurso contenedor con un alias de \'padre\', entonces la ruta completa del alias al Recurso será mostrado como \'/padre/hijo.html\'.<br /><strong>NOTA: Cuando se configure esta opción a \'Si\' (activando las rutas de alias), los artículos de referencia (como imágenes, css, javascript, etc.) usan la ruta absoluta: p.e., \'/assets/images\' en ves de \'assets/images\'.  Al hacer esto, prevendrás que el navegador (o servidor web) añadan la ruta relativa a la ruta del alias.</strong>';

$_lang['setting_use_browser'] = 'Habilitar Navegador de Recursos';
$_lang['setting_use_browser_desc'] = 'Selecciona Si para habilitar el navegador de recursos.  Esto permitirá a tus usuarios a navegar y cargar recursos tales como imágenes, archivos flash y de medios en el servidor.';
$_lang['setting_use_browser_err'] = 'Por favor indica si quieres o no que se use el navegador de recursos.';

$_lang['setting_use_editor'] = 'Habilitar Editor de Texto Formateado';
$_lang['setting_use_editor_desc'] = 'Quieres habilitar el editor de texto formateado?  Si estás más cómodo escribiendo HTML, entonces puedes desactivar el editor usando esta configuración.  Nota que esta configuración se aplica a todos los documentos y a todos los usuarios!';
$_lang['setting_use_editor_err'] = 'Por favor indica si quieres o no que se use el editor de texto formateado.';

$_lang['setting_use_multibyte'] = 'Usar Extensión Multibyte';
$_lang['setting_use_multibyte_desc'] = 'Configurar a verdadero si quieres usar la extensión mbstring para caractéres multibyte en tu instalación de MODX.  Sólo configúralo a verdadero si tienes la extension mbstring de PHP instalada.';

$_lang['setting_webpwdreminder_message'] = 'Email de Recordatorio';
$_lang['setting_webpwdreminder_message_desc'] = 'Ingresa un mensaje que será enviado a tus usuarios cuando soliciten una contraseña nueva via email.  El Admin de Contenido enviará un email conteniendo su contraseña nueva y la información para activarla.  <br /><strong>NOTA:</strong> Las siguientes variables son reemplazadas por el Admin de Contenido cuando el mensaje es enviado:  <br /><br />[[+sname]] - Nombre de tu sitio web, <br />[[+saddr]] - La dirección electrónica de tu sitio web, <br />[[+surl]] - La URL de tu sitio, <br />[[+uid]] - El nombre de entrada o ID del usuario, <br />[[+pwd]] - La contraseña del usuario, <br />[[+ufn]] - El nombre completo de lusuario. <br /><br /><strong>Deja el [[+uid]] y [[+pwd]] en el email, o de otra manera el nombre de usuario y la contraseña no serán enviadas en el email y los usuarios no sabrán su nombre de usuario y su contraseña!</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'Hola [[+uid]]\n\nPara activar tu contrseña nueva haz clic en el enlace siguiente:\n\n[[+surl]]\n\nSi lo haces exitosamente puedes usar la siguiente contraseña para entrar:\n\nContraseña:[[+pwd]]\n\nSi no solicitaste este email entonces por favor ignóralo.\n\nSaludos,\nEl Administrador del Sitio';

$_lang['setting_websignupemail_message'] = 'Email de Registro';
$_lang['setting_websignupemail_message_desc'] = 'Aquí puedes configurar el mensaje que se envía a tus usuarios cuando creas una cuenta para ellos y dejas que el Admin de Contenido les envíe un email que contiene su nombre de usuario y su contraseña.  <br /><strong>NOTA:</strong> Las siguientes variables son reemplazadas por el Admin de Contenido cuando el mensaje es enviado: <br /><br />[[+sname]] - Nombre de tu sitio web, <br />[[+saddr]] - La dirección electrónica de tu sitio web, <br />[[+surl]] - La URL de tu sitio, <br />[[+uid]] - El nombre de entrada o ID del usuario, <br />[[+pwd]] - La contraseña del usuario, <br />[[+ufn]] - El nombre completo del usuario. <br /><br /><strong>Deja el [[+uid]] y [[+pwd]] en el email, o de otra manera el nombre de usuario y la contraseña no serán enviadas en el email y tus usuarios no sabrán su nombre de usuario o su contraseña!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Hola [[+uid]] \n\nAquí están tus detalles de ingreso para [[+sname]]:\n\nNombre de Usuario: [[+uid]]\nContraseña: [[+pwd]]\n\nUna vez que ingreses a [[+sname]] ([[+surl]]), puedes cambiar tu contraseña.\n\nSaludos,\nEl Administrador del Sitio';

$_lang['setting_welcome_screen'] = 'Mostrar la Pantalla de Bienvenida';
$_lang['setting_welcome_screen_desc'] = 'Si se configura a verdadero, la pantalla de bienvenida será mostrada en el próxima carga de la página de bienvenida y después no será mostrada después.';

$_lang['setting_welcome_screen_url'] = 'URL de la Pantalla de Bienvenida';
$_lang['setting_welcome_screen_url_desc'] = 'La URL para la pantalla de bienvenida que se carga en la primera aparición de MODX Revolution.';

$_lang['setting_which_editor'] = 'Editor a usar';
$_lang['setting_which_editor_desc'] = 'Aquí puedes seleccionar que Editor de Texto Formateado quieres usar.  Puedes bajar e instalar Editores de Texto Formateado adicionales del Admin de Paquetes.';

$_lang['setting_which_element_editor'] = 'Editor a usar para Elementos';
$_lang['setting_which_element_editor_desc'] = 'Aquí puedes seleccionar que Editor de Texto Formateado quieres usar para editar Elementos.  Puedes bajar e instalar Editores de Texto Formateado adicionales del Admin de Paquetes.';

$_lang['setting_xhtml_urls'] = 'URLs de XHTML';
$_lang['setting_xhtml_urls_desc'] = 'Si se configura a verdadero,  todos los URLs generados por MODX serán conforme a XHTML, incluyendo la codificación del caracter del símbolo &.';
