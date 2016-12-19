<?php
/**
 * Setting English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Área';
$_lang['area_authentication'] = 'Autenticación y Seguridad';
$_lang['area_caching'] = 'Caché';
$_lang['area_core'] = 'Código del Área';
$_lang['area_editor'] = 'Editor de Texto Enriquecido';
$_lang['area_file'] = 'Sistema de Archivos';
$_lang['area_filter'] = 'Filtrar por área...';
$_lang['area_furls'] = 'URL Amigable';
$_lang['area_gateway'] = 'Portal';
$_lang['area_language'] = 'Idioma';
$_lang['area_mail'] = 'Correo';
$_lang['area_manager'] = 'Panel de Administración (Back-end)';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Sesión y Cookie';
$_lang['area_lexicon_string'] = 'Clave de Entrada del Área en el Archivo de Idioma';
$_lang['area_lexicon_string_msg'] = 'Introduce la clave de la entrada del archivo de idioma para el área. Si la entrada no existe en el archivo de idioma, sólo mostrará la clave del área.<br />Áreas Principales:<ul><li>Autenticación</li><li>Caché</li><li>Archivo</li><li>URLs amigables</li><li>Portal</li><li>Idioma</li><li>Administrador</li><li>Sesión</li><li>Sitio</li><li>Sistema</li></ul>';
$_lang['area_site'] = 'Sitio';
$_lang['area_system'] = 'Sistema y Servidor';
$_lang['areas'] = 'Áreas';
$_lang['charset'] = 'Conjunto de Caractéres';
$_lang['country'] = 'País';
$_lang['description_desc'] = 'Breve descripción de la Configuración. Puede ser una entrada en el archivo de idioma basada en la clave, siguiendo el siguiente formato: "setting_" + clave + "_desc".';
$_lang['key_desc'] = 'La clave para la Configuración. Estará disponible en tu contenido a través del placeholder [[++clave]].';
$_lang['name_desc'] = 'Un nombre para la Configuración. Puede ser una entrada en el archivo de idioma basada en la clave, siguiendo el siguiente formato: "setting_" + clave.';
$_lang['namespace'] = 'Espacio de Nombres';
$_lang['namespace_desc'] = 'El Espacio de Nombres al que esta configuración estará asociado. Se cargará el Tópico por defecto del Archivo de Idioma para este Espacio de Nombres al tomar la configuración.';
$_lang['namespace_filter'] = 'Filtrar por espacio de nombres...';
$_lang['search_by_key'] = 'Buscar por clave...';
$_lang['setting_create'] = 'Crear Configuración Nueva';
$_lang['setting_err'] = 'Por favor, revisa los siguientes campos: ';
$_lang['setting_err_ae'] = 'La configuración con esa clave ya existe. Por favor, especifíca otro nombre de clave.';
$_lang['setting_err_nf'] = 'Configuración no encontrada.';
$_lang['setting_err_ns'] = 'Configuración no especificada';
$_lang['setting_err_remove'] = 'Ocurrió un error mientras se trataba de eliminar la configuración.';
$_lang['setting_err_save'] = 'Ocurrió un error mientras se trataba de guardar la configuración.';
$_lang['setting_err_startint'] = 'Las configuraciones no pueden comenzar con un número.';
$_lang['setting_err_invalid_document'] = 'No existe ningún documento con ID %d. Por favor especifíca un documento válido.';
$_lang['setting_remove'] = 'Eliminar Configuración';
$_lang['setting_remove_confirm'] = '¿Estás seguro de que quieres eliminar esta configuración? Esto puede romper tu instalación de MODX.';
$_lang['setting_update'] = 'Actualizar Configuración';
$_lang['settings_after_install'] = 'Como esta es una instalación nueva, deberás revisar la configuración y modificar las que creas convenientes. Después de modificar la configuración, pulsa "Guardar" para actualizar la base de datos con la nueva configuración.<br /><br />';
$_lang['settings_desc'] = 'Aquí puedes configurar las preferencias generales y la configuración para la interfaz de administración de MODX, así como la manera en que funciona el sitio. Haz click dos veces en un valor para modificarlo, o haz click derecho para ver más opciones. También puedes hacer click en el símbolo "+" para ver una descripción de la configuración.';
$_lang['settings_furls'] = 'URLs Amigables';
$_lang['settings_misc'] = 'Misceláneas';
$_lang['settings_site'] = 'Sitio';
$_lang['settings_ui'] = 'Interfaz &amp; Características';
$_lang['settings_users'] = 'Usuario';
$_lang['system_settings'] = 'Configuración del Sistema';
$_lang['usergroup'] = 'Grupos de Usuario';

// user settings
$_lang['setting_access_category_enabled'] = 'Comprobar Acceso a Categorías';
$_lang['setting_access_category_enabled_desc'] = 'Utiliza esto para activar o desactivar las revisiones de ACL al acceder a las diferentes Categorías. <strong>NOTA: ¡Si esta opción se configura como "No" todos los Permisos de Acceso a Categorías serán ignorados!</strong>';

$_lang['setting_access_context_enabled'] = 'Comprobar Acceso a Contextos';
$_lang['setting_access_context_enabled_desc'] = 'Utiliza esto para activar o desactivar las revisiones de ACL al acceder a los diferentes Contextos. <strong>NOTA: ¡Si esta opción se configura como "No" todos los Permisos de Acceso a Contextos serán ignorados! No desactivar esta opción por completo ni para el contexto "mgr" o no podrás acceder al panel de Administración.</strong>';

$_lang['setting_access_resource_group_enabled'] = 'Comprobar Acceso a Grupos de Recursos';
$_lang['setting_access_resource_group_enabled_desc'] = 'Utiliza esto para activar o desactivar las revisiones de ACL al acceder a los diferentes Grupos de Recursos (por Contexto). <strong>NOTA: ¡Si esta opción se configura como "No" todos los Permisos de Acceso a Grupos de Recursos serán ignorados!</strong>';

$_lang['setting_allow_mgr_access'] = 'Acceso a la Interfaz de Administración';
$_lang['setting_allow_mgr_access_desc'] = 'Selecciona esta opción para habilitar o deshabilitar el acceso a la interfaz de administración. <strong>NOTA: Si esta opción está configurada a "no", entonces el usuario será redirigido a la página de Inicio del Administrador o a la página principal del sitio.</strong>';

$_lang['setting_failed_login'] = 'Intentos de Inicio de Sesión Fallidos';
$_lang['setting_failed_login_desc'] = 'Aquí puedes determinar el número de intentos fallidos permitidos antes de que un usuario sea bloqueado.';

$_lang['setting_login_allowed_days'] = 'Días Permitidos';
$_lang['setting_login_allowed_days_desc'] = 'Selecciona los días en los que este usuario tiene permisos de acceso.';

$_lang['setting_login_allowed_ip'] = 'Dirección de IP Permitida';
$_lang['setting_login_allowed_ip_desc'] = 'Ingresa las direcciones de IP desde las cuales el usuario tiene permitido entrar. <strong>NOTA: Separa las direcciones de IP con una coma (,)</strong>';

$_lang['setting_login_homepage'] = 'Página de Inicio de Entrada';
$_lang['setting_login_homepage_desc'] = 'Ingresa el ID del documento al que quieres mandar al usuario después de iniciar sesión. <strong>NOTA: asegúrate de que el ID introducido pertenece a un documento existente, que ha sido publicado y de que es accesible por este usuario</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Versión del Esquema de la Política de Acceso';
$_lang['setting_access_policies_version_desc'] = 'La versión del sistema de Políticas de Acceso. NO CAMBIAR.';

$_lang['setting_allow_forward_across_contexts'] = 'Permitir Enviar Solicitudes entre Contextos';
$_lang['setting_allow_forward_across_contexts_desc'] = 'Cuando está activado, los Symlinks y las llamadas al API MODX::sendForward() pueden trabajar con Recursos de otros Contextos';

$_lang['setting_allow_manager_login_forgot_password'] = 'Permitir Recuperar la Contraseña al Iniciar Sesión en el Panel de Administración';
$_lang['setting_allow_manager_login_forgot_password_desc'] = 'Configurar esto como "No" deshabilitará la característica de recuperación de contraseñas desde la pantalla de inicio de sesión del panel de administración.';

$_lang['setting_allow_tags_in_post'] = 'Permitir Etiquetas HTML Tags en POST';
$_lang['setting_allow_tags_in_post_desc'] = 'Si se desactiva, todas las acciones POST dentro del admin quitarán cualquier etiqueta HTML, entidades numéricas, y etiquetas de MODX. MODX recomienda activar esta configuración.';

$_lang['setting_anonymous_sessions'] = 'Anonymous Sessions';
$_lang['setting_anonymous_sessions_desc'] = 'If disabled, only authenticated users will have access to a PHP session. This can reduce overhead for anonymous users and the load they impose on a MODX site if they do not need access to a unique session. If session_enabled is false, this setting has no effect as sessions would never be available.';

$_lang['setting_archive_with'] = 'Forzar Archivos PCLZip';
$_lang['setting_archive_with_desc'] = 'Si es verdadero, se usará PCLZip en vez de ZipArchive como la extensión zip. Utiliza esto si estás experimentando errores de extracción o descomprimiendo zips en el Administrador de Paquetes.';

$_lang['setting_auto_menuindex'] = 'Indexado de Menú por Defecto';
$_lang['setting_auto_menuindex_desc'] = 'Selecciona "Sí" para incrementar el índice de menú automáticamente de manera predeterminada.';

$_lang['setting_auto_check_pkg_updates'] = 'Comprobar automáticamente Actualizaciones de Paquetes';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Si está activado, MODX comprobará automáticamente si hay actualizaciones de paquetes en el Administrador de Paquetes. Esto puede hacer más lenta la carga de la cuadrícula.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Tiempo de Caducidad de la Caché para la Revisión Automática de Actualizaciónes de Paquetes';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'El número de minutos que el Administrador de Paquetes mantedrá en caché los resultados de buscar actualizaciones de paquetes.';

$_lang['setting_allow_multiple_emails'] = 'Permitir Direcciones de Correo Electrónico Duplicadas para Usuarios';
$_lang['setting_allow_multiple_emails_desc'] = 'Si se activa, los Usuarios pueden compartir la misma dirección de email.';

$_lang['setting_automatic_alias'] = 'Generar automáticamente el alias';
$_lang['setting_automatic_alias_desc'] = 'Selecciona "Sí" para que el sistema genere automáticamente el alias basado en el título del Recurso cuando sea guardado.';

$_lang['setting_base_help_url'] = 'URL de Base de Ayuda';
$_lang['setting_base_help_url_desc'] = 'La URL base con la cual construir los enlaces de Ayuda en la parte superior derecha de las páginas del Panel de Administración.';

$_lang['setting_blocked_minutes'] = 'Minutos Bloqueado';
$_lang['setting_blocked_minutes_desc'] = 'Aquí puedes determinar el número de minutos que un usuario será bloqueado si supera el máximo de intentos de inicio de sesión. Por favor, introduce un valor numérico (sin comas, espacios etc.)';

$_lang['setting_cache_action_map'] = 'Habilitar Cache de Mapa de Acciones';
$_lang['setting_cache_action_map_desc'] = 'Cuando esté activado, las acciones (o mapas de controlador) serán almacenados en caché para reducir los tiempos de carga de las páginas del panel de administración.';

$_lang['setting_cache_alias_map'] = 'Activar Caché de Alias';
$_lang['setting_cache_alias_map_desc'] = 'Al activar, las URIs de los recursos se almacenan en la caché del Contexto. Activar para sitios pequeños y desactivar para sitios más grandes para un mejor rendimiento.';

$_lang['setting_cache_context_settings'] = 'Habilitar Cache de la Configuración de Contextos';
$_lang['setting_cache_context_settings_desc'] = 'Cuando esté activado, las configuraciones de contextos serán almacenadas en caché para reducir los tiempos de carga.';

$_lang['setting_cache_db'] = 'Habilitar Cache de la Base de Datos';
$_lang['setting_cache_db_desc'] = 'Cuando esté activado, los objetos y los resultados de consultas SQL son almacenados en caché para reducir significativamente las cargas de la base de datos.';

$_lang['setting_cache_db_expires'] = 'Tiempo de Caducidad de la Caché de Base de Datos';
$_lang['setting_cache_db_expires_desc'] = 'Este valor (en segundos) configura la cantidad de tiempo que duran los archivos de caché para la caché de Base de Datos.';

$_lang['setting_cache_db_session'] = 'Activar Caché de Sesiones de Base de Datos';
$_lang['setting_cache_db_session_desc'] = 'Al activar, si cache_db está activado, las sesiones de base de datos se almacenarán en la caché de resultados de la base de datos.';

$_lang['setting_cache_db_session_lifetime'] = 'Tiempo de Caducidad de la Caché de Sesiones de Base de Datos';
$_lang['setting_cache_db_session_lifetime_desc'] = 'Este valor (en segundos) configura la cantidad de tiempo que los archivos tienen validez en la caché de resultados de la base de datos.';

$_lang['setting_cache_default'] = 'En Caché por Defecto';
$_lang['setting_cache_default_desc'] = 'Selecciona "Sí" para hacer que todos los recursos sean almacenenados en caché por defecto.';
$_lang['setting_cache_default_err'] = 'Por favor, indica si quieres o no que los recursos sean almacenados en caché.';

$_lang['setting_cache_disabled'] = 'Deshabilitar las Opciones Globales de la Caché';
$_lang['setting_cache_disabled_desc'] = 'Selecciona "Sí" para deshabilitar todas las características de caché de MODX. MODX no recomienda deshabilitar la caché.';
$_lang['setting_cache_disabled_err'] = 'Por favor indica si quieres o no que la caché esté habilitada.';

$_lang['setting_cache_expires'] = 'Tiempo de Caducidad de la Caché por Defecto';
$_lang['setting_cache_expires_desc'] = 'Este valor (en segundos) determina la cantidad de tiempo que los archivos de la caché duran en la caché predeterminada.';

$_lang['setting_cache_format'] = 'Formato de Caché a Usar';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = serializar. Escoge uno de los formatos.';

$_lang['setting_cache_handler'] = 'Clase de Manejador de Caché';
$_lang['setting_cache_handler_desc'] = 'El nombre de la clase del manejador de tipos que se usará para la caché.';

$_lang['setting_cache_lang_js'] = 'Almacenar en Caché Palabras de JavaScript del Archivo de Idioma';
$_lang['setting_cache_lang_js_desc'] = 'Si está activado, se usarán las cabeceras del servidor para almacenar en caché las palabras del archivo de idioma cargadas a Javascript para la interfaz de administración.';

$_lang['setting_cache_lexicon_topics'] = 'Almacenar en Caché Tópicos del Archivo de Idioma';
$_lang['setting_cache_lexicon_topics_desc'] = 'Cuando esté habilitado, todos los Tópicos del Archivo de Idioma serán almacenados en caché para reducir en gran medida los tiempos de carga para la funcionalidad de Internacionalización. MODX recomienda encarecidamente activar esta característica.';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Almacenar en Caché Tópicos No Principales del Archivo de Idioma';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Cuando esté deshabilitado, los Tópicos No Principales del Archivo de Idioma no serán almacenados en caché. Es recomendable deshabilitarlo durante el desarrollo de Extras.';

$_lang['setting_cache_resource'] = 'Habilitar Caché Parcial de Recursos';
$_lang['setting_cache_resource_desc'] = 'La Caché Parcial de Recursos es configurable por recurso cuando esta característica está habilitada. Deshabilitando esta característica la deshabilitará globalmente.';

$_lang['setting_cache_resource_expires'] = 'Tiempo de Caducidad para Cache Parcial de Recursos';
$_lang['setting_cache_resource_expires_desc'] = 'Este valor (en segundos) determina el tiempo de vida de los archivos de la caché parcial de Recursos.';

$_lang['setting_cache_scripts'] = 'Habilitar Caché de Programas';
$_lang['setting_cache_scripts_desc'] = 'Cuando esté habilitado, MODX almacenará en caché todos los los Scripts (Snippets y Plugins) en un archivo para reducir los tiempos de carga. MODX recomienda configurarlo a "Sí".';

$_lang['setting_cache_system_settings'] = 'Habilitar Caché de Configuración del Sistema';
$_lang['setting_cache_system_settings_desc'] = 'Cuando esté habilitado, la configuración del sistema será almacenada en caché para reducir los tiempos de carga. MODX recomienda activar esta característica.';

$_lang['setting_clear_cache_refresh_trees'] = 'Recargar Árboles al Limpiar la Caché del Sitio';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'Cuando esté habilitado, recargará los árboles después del limpiar la caché del sitio.';

$_lang['setting_compress_css'] = 'Usar CSS Comprimido';
$_lang['setting_compress_css_desc'] = 'Cuando esto está habilitado, MODX usará una versión comprimida de las hojas de estilo de css en la interfaz de administración. Esto reduce enormemente los tiempos de carga y de ejecución dentro del panel de administración. Deshabilítalo sólo si estás modificando elementos principales.';

$_lang['setting_compress_js'] = 'Usar Librerías de Javascript Comprimidas';
$_lang['setting_compress_js_desc'] = 'Cuando esto está habilitado, MODX usara una versión comprimida de sus librerías personalizadas de Javascript en la interfaz de administración. Esto reduce enormemente los tiempos de carga y de ejecución dentro del panel de administración. Deshabilítalo sólo si estás modificando elementos principales.';

$_lang['setting_compress_js_groups'] = 'Usar Agrupación al Comprimir Javascript';
$_lang['setting_compress_js_groups_desc'] = 'Agrupar los archivos JavaScript del núcleo de MODX utilizando la configuración "groupsConfig" de Minify. Activar si se está utilizando suhosin u otros factores limitantes.';

$_lang['setting_compress_js_max_files'] = 'Límite de Archivos JavaScript a Comprimir Simultáneamente';
$_lang['setting_compress_js_max_files_desc'] = 'El máximo número de archivos JavaScript que MODX tratará de comprimir a la vez cuando la configuración "compress_js" está activada. Configurar un número menor si ocurren problemas con Minify.';

$_lang['setting_concat_js'] = 'Usar Librerías de Javascript Unificadas';
$_lang['setting_concat_js_desc'] = 'Cuando esto está habilitado, MODX usara una versión unificada de sus librerías personalizadas de Javascript en la interfaz de administración. Esto reduce enormemente los tiempos de carga y de ejecución dentro del panel de administración. Deshabilítalo sólo si estás modificando elementos principales.';

$_lang['setting_confirm_navigation'] = 'Confirmar Navegación con Cambios sin Guardar';
$_lang['setting_confirm_navigation_desc'] = 'Al activar, se preguntará al usuario la intención de abandonar una página cuando ésta tenga cambios sin guardar.';

$_lang['setting_container_suffix'] = 'Sufijo del Contenedor';
$_lang['setting_container_suffix_desc'] = 'El sufijo para añadir a los Recursos configurados como contenedores cuando se utilizan URLs amigables.';

$_lang['setting_context_tree_sort'] = 'Ordenar los Contextos en el Árbol de Recursos';
$_lang['setting_context_tree_sort_desc'] = 'Si se activa, los Contextos se ordenarán alfabéticamente en el Árbol de Recursos de la parte lateral izquierda.';
$_lang['setting_context_tree_sortby'] = 'Campo por el cual ordenar Contextos en el Árbol de Recursos';
$_lang['setting_context_tree_sortby_desc'] = 'El campo que utilizar para establecer el orden de los Contextos en el Árbol de Recursos, si el ordenamiento de los mismos está activado.';
$_lang['setting_context_tree_sortdir'] = 'Dirección del Ordenamiento de los Contextos en el Árbol de Recursos';
$_lang['setting_context_tree_sortdir_desc'] = 'La dirección del Ordenamiento de los Contextos en el Árbol de Recursos, si el ordenamiento de los mismos está activado.';

$_lang['setting_cultureKey'] = 'Idioma';
$_lang['setting_cultureKey_desc'] = 'Selecciona el idioma para todos los Contextos menos el panel de administración, incluyendo el contexto Web.';

$_lang['setting_date_timezone'] = 'Zona Horaria por Defecto';
$_lang['setting_date_timezone_desc'] = 'Si no está vacío, controla la zona horaria por defecto para la función "date()" de PHP. Si está vacío y la configuración de PHP "date.timezone" no está configurada, se asumirá el uso de UTC.';

$_lang['setting_debug'] = 'Depuración';
$_lang['setting_debug_desc'] = 'Controla la activación/desactivación de la depuración en MODX y/o configura el nivel de la variable de PHP "error_reporting". "" = usar el valor actual de error_reporting, "0" = false (error_reporting = 0), "1" = true (error_reporting = -1), o cualquier valor válido para error_reporting (como número entero).';

$_lang['setting_default_content_type'] = 'Tipo de Contenido por Defecto';
$_lang['setting_default_content_type_desc'] = 'Selecciona el Tipo de Contenido por defecto para los nuevos recursos. Podrás seleccionar también el Tipo de Contenido en el Editor de Recursos; esto tan solo pre-selecciona un Tipo de Contenido por defecto.';

$_lang['setting_default_duplicate_publish_option'] = 'Estado por Defecto de Publicación al Duplicar Recursos';
$_lang['setting_default_duplicate_publish_option_desc'] = 'Configura el estado de publicación de un recurso al duplicarse. Puede ser "unpublish" para que todos aparezcan sin publicar, "publish" para que todos sean publicados, o "preserve" para determinarlo a través del estado del recurso duplicado.';

$_lang['setting_default_media_source'] = 'Orígenes Multimedia por Defecto';
$_lang['setting_default_media_source_desc'] = 'Los Orígenes Multimedia a cargar por defecto.';

$_lang['setting_default_template'] = 'Plantilla Predeterminada';
$_lang['setting_default_template_desc'] = 'Selecciona la Plantilla predeterminada que deseas usar para los Recursos nuevos. Puedes seleccionar una plantilla diferente en el editor de Recursos, esta configuración solo selecciona por defecto una de tus Plantillas.';

$_lang['setting_default_per_page'] = 'Elementos por Página';
$_lang['setting_default_per_page_desc'] = 'El número predeterminado de resultados a mostrar en cada página en la cuadrícula de todas las páginas del panel de administración.';

$_lang['setting_editor_css_path'] = 'Ruta al archivo CSS';
$_lang['setting_editor_css_path_desc'] = 'Introduce la ruta a la hoja de estilos CSS que deseas utilizar dentro del editor de texto enriquecido. La mejor manera de de introducirla es mediante una ruta absoluta desde la raíz del servidor, por ejemplo: /assets/site/style.css. Si no deseas cargar una hoja de estilos en el editor de texto enriquecido, deja este campo en blanco.';

$_lang['setting_editor_css_selectors'] = 'Selectores de CSS para el Editor';
$_lang['setting_editor_css_selectors_desc'] = 'Una lista separada por comas de selectores de CSS para el editor de texto formateado.';

$_lang['setting_emailsender'] = 'Remitente del Email de Registro';
$_lang['setting_emailsender_desc'] = 'Aquí puedes especificar la dirección de email usada cuando se le envíen a los usuarios sus credenciales de acceso.';
$_lang['setting_emailsender_err'] = 'Por favor indica la dirección de email de la administración.';

$_lang['setting_emailsubject'] = 'Asunto del Email de Registro';
$_lang['setting_emailsubject_desc'] = 'Asunto predeterminado para el email de registro.';
$_lang['setting_emailsubject_err'] = 'Por favor, introduce el asunto para el email de registro.';

$_lang['setting_enable_dragdrop'] = 'Habilitar Arrastrar/Soltar en los árboles de Recursos/Elementos';
$_lang['setting_enable_dragdrop_desc'] = 'Si está desactivado, prevendrá arrastrar y soltar en los árboles de Recursos y Elementos.';

$_lang['setting_error_page'] = 'Página de Error';
$_lang['setting_error_page_desc'] = 'Introduce el ID del documento que quieres mostrar a los usuarios si solicitan un documento que no existe. <strong>NOTA: ¡Asegúrate que el ID introducido pertenece a un documento existente y que ha sido publicado!</strong>';
$_lang['setting_error_page_err'] = 'Por favor especifica el ID de un documento válido.';

$_lang['setting_ext_debug'] = 'Depurador de ExtJS';
$_lang['setting_ext_debug_desc'] = 'Cargar o no la librería "ext-all-debug.js" para ayudar a depurar el código de ExtJS.';

$_lang['setting_extension_packages'] = 'Paquetes de Extensiones';
$_lang['setting_extension_packages_desc'] = 'Un array JSON de paquetes a cargar en la instalación de MODX. En el formato [{"nombredelpaquete":{ruta":"ruta/al/paquete"},{"otropaquete":{"ruta":"ruta/al/otropaquete"}}]';

$_lang['setting_enable_gravatar'] = 'Enable Gravatar';
$_lang['setting_enable_gravatar_desc'] = 'If enabled, Gravatar will be used as a profile image (if user do not have profile photo uploaded).';

$_lang['setting_failed_login_attempts'] = 'Intentos de Inicio de Sesión Fallidos';
$_lang['setting_failed_login_attempts_desc'] = 'El número de intentos de inicio de sesión fallidos que un usuario tiene permitidos antes de ser "bloqueado".';

$_lang['setting_fe_editor_lang'] = 'Idioma del Editor del Front-end';
$_lang['setting_fe_editor_lang_desc'] = 'Escoge un idioma para el editor cuando sea usado como editor del Front-end.';

$_lang['setting_feed_modx_news'] = 'URL del Feed de Noticias de MODX';
$_lang['setting_feed_modx_news_desc'] = 'Configura la URL para el feed de RSS para el panel de noticias de MODX en el panel de administración.';

$_lang['setting_feed_modx_news_enabled'] = 'Habilitar el Feed de Noticias de MODX';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Si se configura como "No", MODX ocultará el feed de noticias en la sección de bienvenida del panel de administración.';

$_lang['setting_feed_modx_security'] = 'URL del Feed de Noticias de Seguridad de MODX';
$_lang['setting_feed_modx_security_desc'] = 'Configura la URL para el feed de RSS para el panel de noticias de seguridad de MODX en el panel de administración.';

$_lang['setting_feed_modx_security_enabled'] = 'Habilitar el Feed de Noticias de Seguridad de MODX';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Si se configura como "No", MODX ocultará el feed de noticias de seguridad en la sección de bienvenida del panel de administración.';

$_lang['setting_filemanager_path'] = 'Ruta de Administrador de Archivos (Obsoleto)';
$_lang['setting_filemanager_path_desc'] = 'Obsoleto - Utilizar Orígenes Multimedia en su lugar. IIS normalmente no completa correctamente la configuración de la raíz del sistema de archivos (document_root), la cual es utilizada por el administrador de archivos para determinar lo que puedes ver. Si estás teniendo problemas utilizando el administrador de archivos, asegúrate de que esta ruta corresponde a la raíz de la instalación de MODX.';

$_lang['setting_filemanager_path_relative'] = '¿Es la Ruta del Administrador de Archivos Relativa? (Obsoleto)';
$_lang['setting_filemanager_path_relative_desc'] = 'Obsoleto - Utilizar Orígenes Multimedia en su lugar. Si la configuración anterior (filemanager_path) es una ruta relativa, configura el valor a "Sí". Si por el contrario, la ruta está fuera del directorio raíz de MODX, configura el valor a "No".';

$_lang['setting_filemanager_url'] = 'URL del Administrador de Archivos (Obsoleto)';
$_lang['setting_filemanager_url_desc'] = 'Obsoleto - Utilizar Orígenes Multimedia en su lugar. Opcional. Configura esta variable si quieres utilizar una URL explícita para acceder a los archivos en el administrador de archivos de MODX (útil si has cambiado "filemanager_path" a una ruta fuera de la raíz web de MODX). Asegúrate de que ésta sea la URL correspondiente al valor configurado en filemanager_path y de que ésta sea accesible. Si dejas el campo vacío, MODX intentará calcularlo automáticamente.';

$_lang['setting_filemanager_url_relative'] = '¿Es la URL del Administrador de Archivos Relativa? (Obsoleto)';
$_lang['setting_filemanager_url_relative_desc'] = 'Obsoleto - Utilizar Orígenes Multimedia en su lugar. Si la configuración filemanager_url es una ruta relativa al directorio raíz de MODX, configura el valor como "Sí". Si por el contrario, la ruta está fuera de la raíz de MODX, configura el valor como "No".';

$_lang['setting_forgot_login_email'] = 'Email de Recuperación de Contraseña';
$_lang['setting_forgot_login_email_desc'] = 'La plantilla utilizada en el email de recuperación de usuario y/o contraseña de MODX.';

$_lang['setting_form_customization_use_all_groups'] = 'Utilizar todos los Grupos a los que pertenece un Usuario para la Personalización de Formularios';
$_lang['setting_form_customization_use_all_groups_desc'] = 'Si se activa, la Personalización de Formularios utilizará *todas* las propiedades de *todos* los grupos de usuarios. Si no, utilizará las propiedades pertenecientes al grupo primario del usuario. Nota: Configurar el valor a "Sí" podría causar errores debido a conflictos entre conjuntos de propiedades en la Personalización de Formularios.';

$_lang['setting_forward_merge_excludes'] = 'Excluir Campos al Mezclar mediante SymLink';
$_lang['setting_forward_merge_excludes_desc'] = 'Un Symlink mezcla campos no vacíos sobre los valores del recurso de destino; utilizando esta lista separada por comas de exclusiones previene que los campos especificados sean sobreescritos por el Symlink.';

$_lang['setting_friendly_alias_lowercase_only'] = 'Alias de URLs Amigables en Minúsculas';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Determina si sólo se permiten caractéres en minúsculas en el alias de un Recurso.';

$_lang['setting_friendly_alias_max_length'] = 'Longitud Máxima de Alias de URL Amigable';
$_lang['setting_friendly_alias_max_length_desc'] = 'Número máximo de caractéres permitidos en el alias de un Recurso. Cero indica que no hay límite.';

$_lang['setting_friendly_alias_realtime'] = 'FURL Alias Real-Time';
$_lang['setting_friendly_alias_realtime_desc'] = 'Determines whether a resource alias should be created on the fly when typing the pagetitle or if this should happen when the resource is saved (automatic_alias needs to be enabled for this to have an effect).';

$_lang['setting_friendly_alias_restrict_chars'] = 'Método de Restricción de Caractéres del Alias de URL Amigable';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'El método usado para restringir los caractéres usados en el alias de un Recurso. "pattern" permite el uso de una expresión regular, "legal" permite cualquier caracter legal en URLs, "alfa" sólo permite letras (a-z) y "alphanumeric" sólo permite letras y números.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'Patrón de Restricción de Caractéres del Alias de URL Amigable';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Un patrón de expresión regular válido para restringir los caractéres usados en el alias de un Recurso.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'Quitar Etiquetas de Elementos en el Alias de URL Amigable';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Determina is las etiquetas de Elementos deben de ser eliminadas del alias de un Recurso.';

$_lang['setting_friendly_alias_translit'] = 'Transcripción del Alias de URL Amigable';
$_lang['setting_friendly_alias_translit_desc'] = 'El método de transcripción a usar en el alias especificado para un Recurso. Si está vacío o "none" es el valor predeterminado, entonces la transcripción es saltada. Otros valores posibles son "iconv" (si está disponible) o el nombre de una tabla de transcripción provista por una clase de servicio de transcripción personalizada.';

$_lang['setting_friendly_alias_translit_class'] = 'Clase de Servicio de Transcripción del Alias de URL Amigable';
$_lang['setting_friendly_alias_translit_class_desc'] = 'Una clase de servicio opcional para proveer servicios de transcripción para la generación/filtrado de Alias de FURL.';

$_lang['setting_friendly_alias_translit_class_path'] = 'Ruta de la Clase de Servicio de Transcripción del Alias de URL Amigable';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'Localización del paquete desde el cual se cargará la Clase de Servicio de Transcripción del Alias de URL Amigable.';

$_lang['setting_friendly_alias_trim_chars'] = 'Caractéres a Cortar del Alias de URL Amigable';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Los caractéres a cortar del principio y el final del alias de un Recurso.';

$_lang['setting_friendly_alias_word_delimiter'] = 'Delimitador de Palabras del Alias de URL Amigable';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'El delimitador de palabras preferido para el alias en URLs amigables.';

$_lang['setting_friendly_alias_word_delimiters'] = 'Delimitadores de Palabras del Alias de URL Amigable';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Los caractéres que representan los delimitadores de palabras cuando se procesan loa alias de URLs amigables. Estos caractéres serán convertidos y transformados en el delimitador de palabras preferido para alias de URLs Amigables.';

$_lang['setting_friendly_urls'] = 'Usar URLs Amigables';
$_lang['setting_friendly_urls_desc'] = 'Esto te permite usar URLs amigables para los buscadores con MODX. Por favor nota, esto sólo funciona para instalaciones de MODX funcionando sobre Apache. Necesitarás crear un archivo .htaccess para que funcione. Ver el archivo .htaccess incluido en la distribución para más información.';
$_lang['setting_friendly_urls_err'] = 'Por favor indica si quieres usar o no URLs amigables.';

$_lang['setting_friendly_urls_strict'] = 'Utilizar URLs amigables de manera estricta';
$_lang['setting_friendly_urls_strict_desc'] = 'Cuando las URLs amigables se activan, esta opción obligará a las peticiones a elementos a través de una URL no preferida causen una redirección 301 a la URL preferida de dicho recurso. ATENCIÓN: No activar si se utilizan reglas personalizadas de sobreescritura que no coinciden con, al menos, el principio de la URL preferida. Por ejemplo, la URL "foo/" con una reescritura personalizada a "foo/bar.html" funcionará, pero intentar reescribir "bar/foo.html" como "foo/" forzaría una redirección a "foo/" si esta opción está activada.';

$_lang['setting_global_duplicate_uri_check'] = 'Comprobar URIs Duplicadas a Través de Todos los Contextos';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Selecciona "Sí" para que las comprobaciones de URIs duplicados incluyan todos los Contextos en la búsqueda. De otra manera, sólo el Contexto en el que se esté guardando el Recurso es revisado.';

$_lang['setting_hidemenu_default'] = 'Ocultar de los Menús';
$_lang['setting_hidemenu_default_desc'] = 'Selecciona "Sí" para ocultar todos los recursos nuevos de los menús.';

$_lang['setting_inline_help'] = 'Mostrar Texto de Ayuda Junto a los Campos de un Formulario';
$_lang['setting_inline_help_desc'] = 'Si se configura como "Sí", la ayuda de los campos se mostrará directamente debajo de ellos. En caso contrario, los campos mostrarán un mensaje de ayuda al poner el cursor del ratón sobre ellos.';

$_lang['setting_link_tag_scheme'] = 'Esquema de Generación de URLs';
$_lang['setting_link_tag_scheme_desc'] = 'Esquema de generación de URLs para la etiqueta [[~id]]. Opciones disponibles: <a href="http://rtfm.modx.com/revolution/2.x/developing-in-modx/other-development-resources/class-reference/modx/modx.makeurl">http://rtfm.modx.com/revolution/2.x/developing-in-modx/other-development-resources/class-reference/modx/modx.makeurl</a>.';

$_lang['setting_locale'] = 'Conriguración Regional';
$_lang['setting_locale_desc'] = 'Selecciona la configuración regional para el sistema. Dejar en blanco para utilizar la configuración por defecto. Ver <a href="http://php.net/setlocale" target="_blank">la documentación de PHP</a> para más información.';

$_lang['setting_lock_ttl'] = 'Tiempo de Vida de los Bloqueos';
$_lang['setting_lock_ttl_desc'] = 'El número de segundos que permanecerá el bloqueo sobre un Recurso si el usuario está inactivo.';

$_lang['setting_log_level'] = 'Nivel de Registro';
$_lang['setting_log_level_desc'] = 'El nivel por defecto de registro; cuanto más bajo, menos mensajes se registrarán. Opciones disponibles: 0 (FATAL), 1 (ERROR), 2 (WARN), 3 (INFO), y 4 (DEBUG).';

$_lang['setting_log_target'] = 'Tipo de Registro';
$_lang['setting_log_target_desc'] = 'El tipo de registro por defecto en el que los mensajes de registro son escritos. Opciones disponibles: "FILE", "HTML", or "ECHO". El valor por defecto es "FILE".';

$_lang['setting_mail_charset'] = 'Conjunto de Caractéres del Correo';
$_lang['setting_mail_charset_desc'] = 'El conjunto de caractéres predeterminado para los emails, ejemplo: "iso-8859-1" o "utf-8"';

$_lang['setting_mail_encoding'] = 'Codificación del Correo';
$_lang['setting_mail_encoding_desc'] = 'Configura la Codificación del mensaje. Las opciones disponibles son: "8bit", "7bit", "binary", "base64", y "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Usar SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Si se activa, MODX intentará usar SMTP en las funciones de correo.';

$_lang['setting_mail_smtp_auth'] = 'Autenticación SMTP';
$_lang['setting_mail_smtp_auth_desc'] = 'Configura la autenticación de SMTP. Utiliza las configuraciones mail_smtp_user y mail_smtp_pass.';

$_lang['setting_mail_smtp_helo'] = 'Mensaje HELO de SMTP';
$_lang['setting_mail_smtp_helo_desc'] = 'Configura el HELO SMTP del mensaje (por defecto se corresponde con el servidor de correo).';

$_lang['setting_mail_smtp_hosts'] = 'Hosts de SMTP';
$_lang['setting_mail_smtp_hosts_desc'] = 'Configura los hosts de SMTP. Todos los hosts deben de estar separados por un punto y coma. También puedes especificar un puerto diferente para cada host usando el siguiente formato: [nombredelhost:puerto] (p.e. "smtp1.ejemplo.com:25;smtp2.ejemplo.com"). El uso de los hosts se realizará según el orden de aparición.';

$_lang['setting_mail_smtp_keepalive'] = 'SMTP Keep-Alive';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Previene que la conexión de SMTP sea cerrada después de enviar cada email. No recomendable.';

$_lang['setting_mail_smtp_pass'] = 'Contraseña de SMTP';
$_lang['setting_mail_smtp_pass_desc'] = 'La contraseña utilizada para autenticarse en el servidor SMTP.';

$_lang['setting_mail_smtp_port'] = 'Puerto de SMTP';
$_lang['setting_mail_smtp_port_desc'] = 'Configura el puerto predeterminado del servidor de SMTP.';

$_lang['setting_mail_smtp_prefix'] = 'Prefijo de Conexión de SMTP';
$_lang['setting_mail_smtp_prefix_desc'] = 'Configura el prefijo de conexión SMTP. Las opciones son: "", "ssl" or "tls"';

$_lang['setting_mail_smtp_single_to'] = 'Un Solo Receptor de SMTP';
$_lang['setting_mail_smtp_single_to_desc'] = 'Provee la habilidad de que el campo "Destino" procese emails de individualmente, en lugar de enviar todos a la vez (correo colectivo).';

$_lang['setting_mail_smtp_timeout'] = 'Tiempo de Caducidad de SMTP';
$_lang['setting_mail_smtp_timeout_desc'] = 'Configura el tiempo de caducidad de la conexión con el servidor SMTP en segundos. Esta función no funcionará en servidores win32.';

$_lang['setting_mail_smtp_user'] = 'Usuario de SMTP';
$_lang['setting_mail_smtp_user_desc'] = 'El usuario utilizado para autenticarse en el servidor SMTP.';

$_lang['setting_main_nav_parent'] = 'Main menu parent';
$_lang['setting_main_nav_parent_desc'] = 'The container used to pull all records for the main menu.';

$_lang['setting_manager_direction'] = 'Dirección del Texto en el Panel de Administración';
$_lang['setting_manager_direction_desc'] = 'Elige la dirección en la cual será mostrado el texto en el Panel de Administración, izquierda a derecha o derecha a izquierda.';

$_lang['setting_manager_date_format'] = 'Formato de Fechas del Panel de Administración';
$_lang['setting_manager_date_format_desc'] = 'El formato de fecha, en formato de la función date() de PHP, para las fechas mostradas en el Panel de Administración.';

$_lang['setting_manager_favicon_url'] = 'URL del Favicon del Panel de Administración';
$_lang['setting_manager_favicon_url_desc'] = 'Si está configurado, cargará el favicon para el Panel de Administración de MODX desde esta URL. Debe de ser una URL relativa al directorio "manager/", o una URL absoluta.';

$_lang['setting_manager_js_cache_file_locking'] = 'Habilitar Bloqueo de Archivos para la Caché de CSS/JS del Panel de Administración';
$_lang['setting_manager_js_cache_file_locking_desc'] = 'Bloqueo de Archivos de la Caché. Configurar a "No" si el sistema de archivos es NFS.';
$_lang['setting_manager_js_cache_max_age'] = 'Tiempo de Vida de la Caché de CSS/JS del Panel de Administración';
$_lang['setting_manager_js_cache_max_age_desc'] = 'El número de segundos que permanecerán los ficheros CSS/JS del panel de administración en la caché del navegador. Superado este período, el navegador mandará otra solicitud condicional GET. Aumenta este valor para reducir el tráfico del sitio.';
$_lang['setting_manager_js_document_root'] = 'Directorio Principal para la Compresión de Archivos CSS/JS del Panel de Administración';
$_lang['setting_manager_js_document_root_desc'] = 'Si el servidor da problemas al utilizar la variable DOCUMENT_ROOT, configúrala explícitamente aquí para permitir la compresión de los archivos CSS/JS del Panel de Administración. Cambiar sólo si se sabe lo que se hace.';
$_lang['setting_manager_js_zlib_output_compression'] = 'Activar Compresión de Salida mediante zlib para los archivos CSS/JS del Panel de Administración';
$_lang['setting_manager_js_zlib_output_compression_desc'] = 'Activar o no la compresión mediante zlib de los ficheros CSS/JS del Panel de Administración. No activar si no se está seguro de que la variable de configuración de PHP "zlib.output_compression" se puede configurar a "1". MODX recomienda desactivar esta característica.';

$_lang['setting_manager_lang_attribute'] = 'Atributo de Idioma de HTML y XML del Panel de Administración';
$_lang['setting_manager_lang_attribute_desc'] = 'Introduce el código de idioma que mejor se corresponda con el idioma que elegiste para el Panel de Administración, esto asegurará que el navegador pueda presentar el contenido en el mejor formato posible.';

$_lang['setting_manager_language'] = 'Idioma del Panel de Administración';
$_lang['setting_manager_language_desc'] = 'Selecciona el idioma para el Panel de Administración de Contenido de MODX.';

$_lang['setting_manager_login_url_alternate'] = 'URL Alternativa de Inicio de Sesión en el Panel de Administración';
$_lang['setting_manager_login_url_alternate_desc'] = 'Una URL alternativa a la que dirigir al usuario cuando necesita iniciar sesión en el panel de administración. El formulario debe identificar al usuario en el contexto "mgr" para funcionar.';

$_lang['setting_manager_login_start'] = 'Página Inicial del Panel de Administración';
$_lang['setting_manager_login_start_desc'] = 'Introduce el ID del documento al que quieren enviar al usuario después de que inicie sesión en el Panel de Administración. <strong>NOTA: ¡Asegúrate de que el ID introducido corresponde a un documento existente, de que ha sido publicado y de que es accesible al usuario!</strong>';

$_lang['setting_manager_theme'] = 'Tema del Panel de Administración';
$_lang['setting_manager_theme_desc'] = 'Selecciona el Tema para el Panel de Administración de Contenido.';

$_lang['setting_manager_time_format'] = 'Formato de Tiempo en el Panel de Administración';
$_lang['setting_manager_time_format_desc'] = 'El formato de tiempo, en formato de la función date() de PHP, para las horas mostradas en el Panel de Administración.';

$_lang['setting_manager_use_tabs'] = 'Usar Pestañas en el Diseño del Panel de Administración';
$_lang['setting_manager_use_tabs_desc'] = 'Si es verdadero, el Panel de Administración usará pestañas para mostrar los páneles de contenido. De otra manera, usará portales.';

$_lang['setting_manager_week_start'] = 'Inicio de la Semana';
$_lang['setting_manager_week_start_desc'] = 'Define el dia por el cual comenzará la semana. Usar 0 para domingo, 1 para lunes, etc.';

$_lang['setting_mgr_tree_icon_context'] = 'Context tree icon';
$_lang['setting_mgr_tree_icon_context_desc'] = 'Define a CSS class here to be used to display the context icon in the tree. You can use this setting on each context to customize the icon per context.';

$_lang['setting_mgr_source_icon'] = 'Media Source icon';
$_lang['setting_mgr_source_icon_desc'] = 'Indicate a CSS class to be used to display the Media Sources icons in the files tree. Defaults to "icon-folder-open-o"';

$_lang['setting_modRequest.class'] = 'Clase Manejadora de Solicitudes';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_tree_hide_files'] = 'Media Browser Tree Hide Files';
$_lang['setting_modx_browser_tree_hide_files_desc'] = 'If true the files inside folders are not displayed in the Media Browser source tree. Defaults to false.';

$_lang['setting_modx_browser_tree_hide_tooltips'] = 'Media Browser Tree Hide Tooltips';
$_lang['setting_modx_browser_tree_hide_tooltips_desc'] = 'If true, no image preview tooltips are shown when hovering over a file in the Media Browser tree. Defaults to true.';

$_lang['setting_modx_browser_default_sort'] = 'Orden por Defecto del Navegador de Archivos';
$_lang['setting_modx_browser_default_sort_desc'] = 'El método de ordenamiento por defecto cuando se utilice el navegador de archivos en una ventana emergente. Los valores disponibles son "name" (nombre), "size" (tamaño), "lastmod" (última modificación).';

$_lang['setting_modx_browser_default_viewmode'] = 'Modo de Vista Predeterminada del Navegador de Archivos';
$_lang['setting_modx_browser_default_viewmode_desc'] = 'El modo de vista predeterminado al utilizar el Navegador de Archivos emergente en el panel de administración. Los valores disponibles son: cuadrícula, lista.';

$_lang['setting_modx_charset'] = 'Codificación de Caractéres';
$_lang['setting_modx_charset_desc'] = 'Selecciona que codificación de caractéres deseas usar. Por favor, ten en cuenta que MODX ha sido probado con un buen número de estas codificaciones, pero no con todas ellas. Para la mayoría de los idiomas, la configuración por defecto de UTF-8 es la preferida.';

$_lang['setting_new_file_permissions'] = 'Permisos de Archivo Nuevo';
$_lang['setting_new_file_permissions_desc'] = 'Cuando se carga un archivo nuevo en el Panel de Administración de Archivos, éste intentará cambiar los permisos del archivo a los aquí introducidos. Esto puede no funcionar en algunas instalaciones, tales como IIS, en cuyo caso necesitarás cambiar los permisos manualmente.';

$_lang['setting_new_folder_permissions'] = 'Permisos de Carpeta Nueva';
$_lang['setting_new_folder_permissions_desc'] = 'Cuando se crea una carpeta nueva en el Panel de Administración de Archivos, éste intentará cambiar los permisos de la carpeta a los aquí introducidos. Esto puede no funcionar en algunas instalaciones, tales como IIS, en cuyo caso necesitarás cambiar los permisos manualmente.';

$_lang['setting_parser_recurse_uncacheable'] = 'Delay Uncacheable Parsing';
$_lang['setting_parser_recurse_uncacheable_desc'] = 'If disabled, uncacheable elements may have their output cached inside cacheable element content. Disable this ONLY if you are having problems with complex nested parsing which stopped working as expected.';

$_lang['setting_password_generated_length'] = 'Longitud de Contraseña Auto-Generada';
$_lang['setting_password_generated_length_desc'] = 'La longitud de la contraseña auto-generada para un Usuario.';

$_lang['setting_password_min_length'] = 'Longitud Mínima de la Contraseña';
$_lang['setting_password_min_length_desc'] = 'La longitud mínima de una contraseña para un Usuario.';

$_lang['setting_preserve_menuindex'] = 'Preserve Menu Index When Duplicating Resources';
$_lang['setting_preserve_menuindex_desc'] = 'When duplicating Resources, the menu index order will also be preserved.';

$_lang['setting_principal_targets'] = 'Destino de las ACL';
$_lang['setting_principal_targets_desc'] = 'Personalizar dónde se utilizarán las ACL sobre los Usuarios de MODX.';

$_lang['setting_proxy_auth_type'] = 'Tipo de Autentificación mediante Proxy';
$_lang['setting_proxy_auth_type_desc'] = 'Soporta BASIC o NTLM.';

$_lang['setting_proxy_host'] = 'Host del Proxy';
$_lang['setting_proxy_host_desc'] = 'Si tu servidor está usando un proxy, configura el nombre del host para habilitar las características de MODX que puedan ser necesarias para trabajar tras proxy, como el Panel de Administración de Paquetes.';

$_lang['setting_proxy_password'] = 'Contraseña del Proxy';
$_lang['setting_proxy_password_desc'] = 'La contraseña requerida para autenticarte en el servidor de proxy.';

$_lang['setting_proxy_port'] = 'Puerto del Proxy';
$_lang['setting_proxy_port_desc'] = 'El puerto para tu servidor de proxy.';

$_lang['setting_proxy_username'] = 'Nombre de usaurio del Proxy';
$_lang['setting_proxy_username_desc'] = 'El nombre de usuario para autenticarte en el servidor de proxy.';

$_lang['setting_photo_profile_source'] = 'User photo Media Source';
$_lang['setting_photo_profile_source_desc'] = 'The Media Source used to store users profiles photos. Defaults to default Media Source.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb Permitir carpeta de miniaturas fuera del directorio raíz';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Indica si se permite utilizar una ruta para la carpeta de miniaturas fuera del directorio raíz. Esto es útil para instalaciones multi-contextos con múltiples hosts virtuales.';

$_lang['setting_phpthumb_cache_maxage'] = 'phpThumb Tiempo de Vida de la Caché';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Borrar miniaturas en la caché que no han sido utilizadas en mas de X días.';

$_lang['setting_phpthumb_cache_maxsize'] = 'phpThumb Tamaño Máximo de la Caché';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Borrar las miniaturas utilizadas menos recientemente cuando la caché supere los X megabytes de tamaño.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'phpThumb Archivos Máximos en la Caché';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Borrar las miniaturas utilizadas menos recientemente cuando la caché supere los X archivos.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'phpThumb Almacenar los Archivos Originales en Caché';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Almacenar o no en caché los archivos originales de las miniaturas según éstas se van generando. Se recomienda desactivarlo.';

$_lang['setting_phpthumb_document_root'] = 'phpThumb Carpeta de miniaturas';
$_lang['setting_phpthumb_document_root_desc'] = 'Configura esto si estás experimentando problemas con la variable de servidor DOCUMENT_ROOT, u obteniendo errores con OutputThumbnail o !is_resource. Introduce la ruta absoluta a la carpeta de miniaturas que quieras utilizar. Si está vacío, MODX usará la variable de servidor DOCUMENT_ROOT.';

$_lang['setting_phpthumb_error_bgcolor'] = 'phpThumb Color del Fondo de los Mensajes de Error';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'Un valor hexadecimal, sin el símbolo #, para indicar el color de fondo para los mensajes de error de phpThumb.';

$_lang['setting_phpthumb_error_fontsize'] = 'phpThumb Tamaño de Letra de los Mensajes de Error';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'Un valor en "em" indicando el tamaño de letra para los mensajes de error de phpThumb.';

$_lang['setting_phpthumb_error_textcolor'] = 'phpThumb Color de Letra de los Mensajes de Error';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'Un valor hexadecimal, sin el símbolo #, para indicar el color de letra para los mensajes de error de phpThumb.';

$_lang['setting_phpthumb_far'] = 'phpThumb Forzar la Relación de Aspecto';
$_lang['setting_phpthumb_far_desc'] = 'La configuracion por defecto para Forzar la Relación de Aspecto de phpThumb cuando es usado en MODX. Se configura por defecto a "C" para forzar la relación de aspecto hacia el centro.';

$_lang['setting_phpthumb_imagemagick_path'] = 'phpThumb Ruta de ImageMagick';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Opcional. Configurar una ruta alternativa a ImageMagick para generar miniaturas con phpThumb, si es que no está presente en la configuración por defecto de PHP.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb Deshabilitar Hotlinking';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Se permitirá introducir Servidores externos en el parámetro src a menos que se deshabilite el hotlinking en phpThumb.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb Borrar Imágenes generadas por Hotlinking';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Indica si una imagen generada desde un servidor remoto deberá ser borrada el hotlinking esté deshabilitado.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb Mensaje de "No Permitido" para Hotlinking';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'Mensaje que será mostrado en lugar de la miniatura cuando se rechace un intento de hotlinking.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb Dominios Válidos para Hotlinking';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'Una lista de nombres de dominio separada por comas que son válidos para realizar hotlinking.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb Deshabilitar Enlazar Miniaturas desde Sitios Externos';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Deshabilita la posibilidad de que las miniaturas generadas mediante phpThumb sean mostradas en sitios externos.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb Borrar Imágenes generadas por Enlazar Miniaturas desde Sitios Externos';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Indica si una miniatura enlazada desde un sitio externo deberá ser borrada ésto no se permita.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb Requerir Referidor al Enlazar Miniaturas desde Sitios Externos';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'Si se habilita, cualquier intento de enlace a miniatura desde un sitio externo sin un referidor válido en la cabecera será rechazado.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb Mensaje de "No Permitido" al Enlazar Miniaturas desde Sitios Externos';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'Mensaje que será mostrado en lugar de la miniatura cuando se rechace un intento de enlace a una miniatura desde un sitio externo.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb Dominios Válidos para Enlazar Miniaturas desde Sitios Externos';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'Una lista de nombres de dominio separada por comas desde los cuales se permite enlazar miniaturas.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb Archivo de Marca de Agua al Enlazar Miniaturas desde Sitios Externos';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Opcional. Ruta al archivo a utilizar como marca de agua cuanto se enlacen miniaturas desde sitios externos.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb Zoom-Recortar';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'EL patrón de recortado y zoom por defecto para phpThumb cuando se utiliza en MODX. El valor por defecto es 0 para evitar recortado y zoom.';

$_lang['setting_publish_default'] = 'Valor por Defecto de Estado de Publicación';
$_lang['setting_publish_default_desc'] = 'Selecciona "Sí" para hacer todos los recursos nuevos publicados por defecto.';
$_lang['setting_publish_default_err'] = 'Por favor, indica si quieres que los nuevos elementos estén publicados desde su creación.';

$_lang['setting_rb_base_dir'] = 'Ruta Base del Directorio de Recursos';
$_lang['setting_rb_base_dir_desc'] = 'Introduce la ruta física al directorio de recursos. Normalmente, ésta es generada automáticamente. Sin embargo, en un servidor IIS MODX podría no generarla correctamente, causando que el Navegador de Recursos muestre un error. En ese caso, puedes introducir la ruta física a la carpeta aquí (tal como harías en el explorador de ficheros del sistema operativo). <strong>NOTA:</strong> El directorio de recursos deberá contener las carpetas correspodientes para las imágenes, archivos flash, y elementos multimedia, de manera que el navegador de recursos funcione correctamente.';
$_lang['setting_rb_base_dir_err'] = 'Por favor, indica el directorio base del navegador de recursos.';
$_lang['setting_rb_base_dir_err_invalid'] = 'Este recurso no existe o no pudo ser encontrado. Por favor, indica un directorio válido o ajusta los permisos de este directorio.';

$_lang['setting_rb_base_url'] = 'URL Base de Recursos';
$_lang['setting_rb_base_url_desc'] = 'Introduce la ruta virtual al directorio de recursos. Normalmente, ésta es generada automáticamente. Sin embargo, en un servidor IIS MODX podría no generarla correctamente, causando que el Navegador de Recursos muestre un error. En ese caso, puedes introducir la ruta virtual a la carpeta aquí (tal como harías en el navegador web).';
$_lang['setting_rb_base_url_err'] = 'Por favor indica la URL base del navegador de recursos.';

$_lang['setting_request_controller'] = 'Nombre de Archivo del Controlador de Solicitudes';
$_lang['setting_request_controller_desc'] = 'El nombre de archivo del controlador de solicitudes principal desde el cual se carga MODX. La mayoría de los usuarios pueden dejarlo como "index.php".';

$_lang['setting_request_method_strict'] = 'Método de Respuesta Estricto';
$_lang['setting_request_method_strict_desc'] = 'Si se habilita, las peticiones a través de la ID de un recurso se ignorarán si las URLs Amigables están activadas, y aquellas peticiones a través del alias de un recurso serán ignoradas si las URLs Amigables están desactivadas.';

$_lang['setting_request_param_alias'] = 'Parámetro de Alias de Solicitud';
$_lang['setting_request_param_alias_desc'] = 'El nombre del parámetro GET para identificar los alias de un Recurso cuando se utilicen URLs Amigables.';

$_lang['setting_request_param_id'] = 'Parámetro de ID de Solicitud';
$_lang['setting_request_param_id_desc'] = 'El nombre del parámetro GET para identificar los IDs de un Recurso cuando no se utilicen FURLs.';

$_lang['setting_resolve_hostnames'] = 'Resolver Nombres de Dominio';
$_lang['setting_resolve_hostnames_desc'] = '¿Quieres que MODX trate de resolver los nombres de dominio de tus visitantes cuando ellos visiten tu sitio? Ésto puede suponer una carga extra para el servidor, aunque no tendrá repercusión en los usuarios del sitio.';

$_lang['setting_resource_tree_node_name'] = 'Campo de Nodo del Árbol de Recursos';
$_lang['setting_resource_tree_node_name_desc'] = 'Especifica el campo del Recurso a mostrar cuando se generen los nodos en el Árbol de Recursos. El valor por defecto es pagetitle, aunque puede usarse cualquier campo del Recurso, como son menutitle, alias, longtitle, etc.';

$_lang['setting_resource_tree_node_name_fallback'] = 'Campo de Reserva para el Nodo del Árbol de Recursos';
$_lang['setting_resource_tree_node_name_fallback_desc'] = 'Campo de Recurso a utilizar como reserva al renderizar los nodos en el Árbol de Recursos. Se utilizará si el recurso tiene el Campo para el Nodo del Árbol de Recursos previamente configurado en blanco.';

$_lang['setting_resource_tree_node_tooltip'] = 'Campo de Pista del Árbol de Recursos';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Especifíca el campo de Recurso a usar como pista cuando se muestran los nodos en el Árbol de Recursos y se pone el ratón sobre uno de ellos. El valor por defecto es longtitle, aunque puede usarse cualquier campo del Recurso, como son menutitle, alias, longtitle, etc.';

$_lang['setting_richtext_default'] = 'Texto Enriquecido por Defecto';
$_lang['setting_richtext_default_desc'] = 'Selecciona "Sí" para hacer que todos los recursos nuevos utilicen el Editor de Texto Enriquecido.';

$_lang['setting_search_default'] = 'Buscable por Defecto';
$_lang['setting_search_default_desc'] = 'Selecciona "Sí" para permitir que todos los recursos nuevos puedan ser buscados.';
$_lang['setting_search_default_err'] = 'Por favor, especifica si quieres o no que los nuevos documentos puedan ser buscados.';

$_lang['setting_server_offset_time'] = 'Diferencia de Tiempo del Servidor';
$_lang['setting_server_offset_time_desc'] = 'Selecciona el número de horas de diferencia que existe entre la hora del lugar donde te encuentras y el lugar donde se encuentra el servidor.';

$_lang['setting_server_protocol'] = 'Tipo de Servidor';
$_lang['setting_server_protocol_desc'] = 'Especifica si tu sitio utiliza HTTPS.';
$_lang['setting_server_protocol_err'] = 'Por favor especifica si tu sitio es un sitio seguro.';
$_lang['setting_server_protocol_http'] = 'HTTP';
$_lang['setting_server_protocol_https'] = 'HTTPS';

$_lang['setting_session_cookie_domain'] = 'Dominio de Cookie de Sesión';
$_lang['setting_session_cookie_domain_desc'] = 'Usa esta configuración para personalizar el dominio de la cookie de sesión. Déjalo en blanco para usar el dominio actual.';

$_lang['setting_session_cookie_lifetime'] = 'Tiempo de Vida de la Cookie de Sesión';
$_lang['setting_session_cookie_lifetime_desc'] = 'Usa esta configuración para personalizar el tiempo de vida en segundos de la cookie de sesión. Esto sirve praa configurar el tiempo de validez de una cookie de sesión de un cliente éste escoge la opción "recuérdame".';

$_lang['setting_session_cookie_path'] = 'Ruta de la Cookie de Sesión';
$_lang['setting_session_cookie_path_desc'] = 'Usa esta configuración para personalizar la ruta de cookies para identificar las cookies de sesión específicas del sitio. Dejar en blanco para usar MODX_BASE_URL.';

$_lang['setting_session_cookie_secure'] = 'Cookies de Sesión Segura';
$_lang['setting_session_cookie_secure_desc'] = 'Habilitar esta configuración para usar cookies de sesión segura.';

$_lang['setting_session_cookie_httponly'] = 'HttpOnly en las Cookies de Sesión';
$_lang['setting_session_cookie_httponly_desc'] = 'Configurar este parámetro para activar el flag "HttpOnly" en las cookies de sesión.';

$_lang['setting_session_gc_maxlifetime'] = 'Tiempo de Vida Máximo del Garbage Collector de la Sesión';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Permite personalizar la variable de PHP "session.gc_maxlifetime" cuando se utilice "modSessionHandler".';

$_lang['setting_session_handler_class'] = 'Nombre de Clase del Manejador de Sesiones';
$_lang['setting_session_handler_class_desc'] = 'Para sesiones administradas en base de datos, usar "modSessionHandler". Dejar en blanco para usar la administración de sesiones estándar de PHP.';

$_lang['setting_session_name'] = 'Nombre de Sesión';
$_lang['setting_session_name_desc'] = 'Usa esta configuración para personalizar el nombre de sesión usado para las sesiones en MODX. Déjalo en blanco para usar el valor de nombre de sesión por defecto de PHP.';

$_lang['setting_settings_version'] = 'Versión de Configuración';
$_lang['setting_settings_version_desc'] = 'La versión de MODX actualmente instalada.';

$_lang['setting_settings_distro'] = 'Distribución de Configuración';
$_lang['setting_settings_distro_desc'] = 'La distribución de MODX actualmente instalada.';

$_lang['setting_set_header'] = 'Configurar Cabeceras de HTTP';
$_lang['setting_set_header_desc'] = 'Cuando está habilitado, MODX intentará configurar las cabeceras de HTTP para los Recursos.';

$_lang['setting_send_poweredby_header'] = 'Send X-Powered-By Header';
$_lang['setting_send_poweredby_header_desc'] = 'When enabled, MODX will send the "X-Powered-By" header to identify this site as built on MODX. This helps tracking global MODX usage through third party trackers inspecting your site. Because this makes it easier to identify what your site is built with, it might pose a slightly increased security risk if a vulnerability is found in MODX.';

$_lang['setting_show_tv_categories_header'] = 'Mostrar la Cabecera de la Pestaña "Categorías" con las VdP';
$_lang['setting_show_tv_categories_header_desc'] = 'Configurar como "Sí" para que MODX muestre la cabecera de "Categorías" bajo la primera pestaña de categorías cuando se editen Variables de Plantilla en un Recurso.';

$_lang['setting_signupemail_message'] = 'Email de Registro';
$_lang['setting_signupemail_message_desc'] = 'Aquí puedes configurar el mensaje que será enviado a los usuarios cuando una nueva cuenta es creada y MODX les envía un email con las credenciales de acceso. <br /><strong>NOTA:</strong> Las siguientes variables son reemplazadas por el Administrador de Contenido cuando el mensaje es enviado: <br /><br />[[+sname]] - Nombre del sitio web, <br />[[+saddr]] - La dirección de correo electrónico del Administrador del sitio web, <br />[[+surl]] - La URL del sitio, <br />[[+uid]] - El nombre o ID del usuario, <br />[[+pwd]] - La contraseña del usuario, <br />[[+ufn]] - El nombre completo del usuario. <br /><br /><strong>¡Deja los campos [[+uid]] y [[+pwd]] en el email, o el nombre de usuario y la contraseña no serán enviados en el email y los usuarios no conocerán sus credenciales!</strong>';
$_lang['setting_signupemail_message_default'] = 'Hola [[+uid]] \n\nAquí están tus credenciales de acceso para el Panel de Administración de Contenido de [[+sname]]:\n\nNombre de Usuario: [[+uid]]\nContraseña: [[+pwd]]\n\nUna vez que entres en el Panel de Administración de Contenido ([[+surl]]), podrás cambiar tu contraseña.\n\nSaludos,\nEl Administrador del Sitio';

$_lang['setting_site_name'] = 'Nombre del Sitio';
$_lang['setting_site_name_desc'] = 'Introduce aquí el nombre del sitio.';
$_lang['setting_site_name_err']  = 'Por favor, introduce un nombre para el sitio.';

$_lang['setting_site_start'] = 'Página de Inicio del Sitio';
$_lang['setting_site_start_desc'] = 'Introduce aquí el ID del Recurso que quieres usar como página de inicio. <strong>NOTA: ¡Asegúrate de que el ID corresponde a un Recurso existente, y de que ha sido publicado!</strong>';
$_lang['setting_site_start_err'] = 'Por favor, introduce un ID de Recurso para que sea la página de inicio del sitio.';

$_lang['setting_site_status'] = 'Sitio Disponible';
$_lang['setting_site_status_desc'] = 'Selecciona "Sí" para permitir el acceso a la web. Si seleccionas "No", tus visitantes verán el "Mensaje de Sitio No Disponible", y no podrán navegar por el sitio.';
$_lang['setting_site_status_err'] = 'Por favor, selecciona si quieres que el sitio esté en línea (Si) o fuera de línea (No).';

$_lang['setting_site_unavailable_message'] = 'Mensaje de Sitio No Disponible';
$_lang['setting_site_unavailable_message_desc'] = 'El mensaje que se mostrará cuando el sitio esté fuera de línea o si ocurre algún error. <strong>NOTA: Este mensaje sólo será mostrado si la opción "Página de Sitio No Disponible" no está configurada.</strong>';

$_lang['setting_site_unavailable_page'] = 'Página de Sitio No Disponible';
$_lang['setting_site_unavailable_page_desc'] = 'Introduce el ID del Recurso que quieres mostrar cuanto tu sitio no esté disponible. <strong>NOTA: ¡Asegúrate de que el ID introducido pertenece a un Recurso existente y de que ha sido publicado!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Por favor, especifíca el ID del Recurso utilizado como página a mostrar cuando el sitio no esté disponible.';

$_lang['setting_strip_image_paths'] = '¿Re-escribir rutas del navegador?';
$_lang['setting_strip_image_paths_desc'] = 'Si se configura como "No", MODX creará las rutas a recursos (imágenes, archivos, flash, etc.) del navegador de recursos como URLs absolutas. Las URLs relativas son útiles cuando se desea migrar la instalación de MODX, como por ejemplo de un sitio de desarrollo a un sitio de producción. Si no tienes idea de lo que esto significa, es mejor dejarlo configurado a "Sí".';

$_lang['setting_symlink_merge_fields'] = 'Combinar Campos de Recurso en SymLinks';
$_lang['setting_symlink_merge_fields_desc'] = 'Si está configurado como "Sí", combinará automáticamente los campos no vacíos con el recurso de destino cuando se utilicen SymLinks si el forwarding está activado.';

$_lang['setting_syncsite_default'] = 'Empty Cache default';
$_lang['setting_syncsite_default_desc'] = 'Select \'Yes\' to empty the cache after you save a resource by default.';
$_lang['setting_syncsite_default_err'] = 'Please state whether or not you want to empty the cache after saving a resource by default.';

$_lang['setting_topmenu_show_descriptions'] = 'Mostrar Descripciones en el Menú Superior';
$_lang['setting_topmenu_show_descriptions_desc'] = 'Si se configura a "No", MODX ocultará las descripciones de los artículos del menú superior del Panel de Administración.';

$_lang['setting_tree_default_sort'] = 'Campo para Ordenar por Defecto los Elementos del Árbol de Recursos';
$_lang['setting_tree_default_sort_desc'] = 'El campo utilizado por defecto para ordenar los elementos del Árbol de Recursos del Panel de Administración.';

$_lang['setting_tree_root_id'] = 'ID de la Raíz de Árbol';
$_lang['setting_tree_root_id_desc'] = 'Introduce el ID de un Recurso válido para que al iniciar el árbol de Recursos de la parte lateral izquierda tome este nodo como la raíz. El usuario sólo podra ver Recursos que son hijos del Recurso especificado.';

$_lang['setting_tvs_below_content'] = 'Mostrar Variables de Plantilla bajo el Contenido';
$_lang['setting_tvs_below_content_desc'] = 'Configurar como "Sí" para mover las variables de plantilla debajo del campo Contenido cuando se edite un recurso.';

$_lang['setting_ui_debug_mode'] = 'Modo de Depuración en la Interfaz de Usuario';
$_lang['setting_ui_debug_mode_desc'] = 'Configurar como "Sí" para mostrar mensajes de depuración al utilizar la interfaz gráfic de la plantilla predeterminada del panel de administración. El navegador debe soportar "console.log".';

$_lang['setting_udperms_allowroot'] = 'Permitir Crear Elementos en la Raíz';
$_lang['setting_udperms_allowroot_desc'] = '¿Quieres permitir que tus usuarios creen nuevos Recursos en la raíz de tu sitio? ';

$_lang['setting_unauthorized_page'] = 'Página de Acceso No Autorizado';
$_lang['setting_unauthorized_page_desc'] = 'Introduce el ID del Recurso que quieres mostrar a los usuarios cuando solicitan un Recurso seguro o no autorizado. <strong>NOTA: ¡Asegúrate de que el ID introducido pertenece a un Recurso existente que ha sido publicado y que es públicamente accesible!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Por favor, especifíca un ID de Recurso para la página de Acceso No Autorizado.';

$_lang['setting_upload_files'] = 'Tipo de Archivos Permitidos en Subida';
$_lang['setting_upload_files_desc'] = 'Lista separada por comas de los tipos de archivo permitidos para subir a la carpeta "assets/files" utilizando el Administrador de Recursos.';

$_lang['setting_upload_flash'] = 'Tipos de Archivos Flash Permitidos en Subida';
$_lang['setting_upload_flash_desc'] = 'Lista separada por comas de los tipos de archivos flash permitidos para subir a la carpeta "assets/flash" utilizando el Administrador de Recursos.';

$_lang['setting_upload_images'] = 'Tipos de Archivos de Imagen Permitidos en Subida';
$_lang['setting_upload_images_desc'] = 'Lista separada por comas de los tipos de archivos de imagen permitidos para subir a la carpeta "assets/images" utilizando el Administrador de Recursos.';

$_lang['setting_upload_maxsize'] = 'Tamaño Máximo de Archivo en Subida';
$_lang['setting_upload_maxsize_desc'] = 'Tamaño máximo permitido de los archivos a ser subidos a través del Administrador de Archivos. El tamaño debe introducirse en bytes. <strong>NOTA: ¡Los archivos muy grandes pueden tardar bastante en ser subidos!</strong>';

$_lang['setting_upload_media'] = 'Tipos de Archivo Multimedia Permitidos en Subida';
$_lang['setting_upload_media_desc'] = 'Lista separada por comas de los tipos de archivo multimedia permitidos para subir a la carpeta "assets/media" utilizando el Administrador de Recursos.';

$_lang['setting_use_alias_path'] = 'Usar la Ruta de Alias Amigable';
$_lang['setting_use_alias_path_desc'] = 'Configurando esta opción a "Sí" se mostrará la ruta completa al Recurso si éste tiene un alias. Por ejemplo, si un Recurso con un alias de "hijo" se encuentra dentro de un Recurso contenedor con un alias de "padre", entonces la ruta completa del alias al Recurso será mostrado como "/padre/hijo.html".<br /><strong>NOTA: Cuando esta opción se configura como "Sí" (activando las rutas de alias), los recursos (como imágenes, css, javascript, etc.) usan la ruta absoluta, como por ejemplo "/assets/images" en lugar de "assets/images". Al hacer esto, prevendrás que navegador o servidor web añadan la ruta relativa a la ruta del alias.</strong>';

$_lang['setting_use_browser'] = 'Habilitar Navegador de Recursos';
$_lang['setting_use_browser_desc'] = 'Selecciona "Sí" para habilitar el navegador de recursos. Esto permitirá a tus usuarios a navegar y subir recursos tales como imágenes, archivos flash y archivos multimedia en el servidor.';
$_lang['setting_use_browser_err'] = 'Por favor, indica si quieres o no que el navegador de recursos esté disponible para los usuarios.';

$_lang['setting_use_editor'] = 'Habilitar Editor de Texto Enriquecido';
$_lang['setting_use_editor_desc'] = '¿Habilitar el editor de texto enriquecido? Para editar y escribir código HTML, usa esta configuración para desactivarlo. <strong>NOTA:</strong> ¡Esta configuración se aplica a todos los documentos y a todos los usuarios!';
$_lang['setting_use_editor_err'] = 'Por favor, indica si quieres que se use o no el editor de texto enriquecido.';

$_lang['setting_use_frozen_parent_uris'] = 'Use Frozen Parent URIs';
$_lang['setting_use_frozen_parent_uris_desc'] = 'When enabled, the URI for children resources will be relative to the frozen URI of one of its parents, ignoring the aliases of resources high in the tree.';

$_lang['setting_use_multibyte'] = 'Usar Extensión Multibyte';
$_lang['setting_use_multibyte_desc'] = 'Configurar a verdadero si quieres usar la extensión mbstring para caractéres multibyte en tu instalación de MODX. Sólo configúralo a verdadero si tienes la extension mbstring de PHP instalada.';

$_lang['setting_use_weblink_target'] = 'Usar el Destino del Weblink';
$_lang['setting_use_weblink_target_desc'] = 'Activar para que las etiquetas de enlaces de MODX utilicen como valor la URL de destino. Si no se activa, se utilizarán las etiquetas y direcciones internas de MODX.';

$_lang['setting_user_nav_parent'] = 'User menu parent';
$_lang['setting_user_nav_parent_desc'] = 'The container used to pull all records for the user menu.';

$_lang['setting_webpwdreminder_message'] = 'Email de Recuperación de Contraseña';
$_lang['setting_webpwdreminder_message_desc'] = 'Introduce el mensaje que será enviado a los usuarios cuando soliciten una contraseña nueva a través de email. El Administrador de Contenido enviará un email conteniendo la nueva contraseña e información para activarla. <br /><strong>NOTA:</strong> Las siguientes variables son reemplazadas por el Administrador de Contenido cuando el mensaje es enviado: <br /><br />[[+sname]] - Nombre del sitio web, <br />[[+saddr]] - La dirección de email de contacto del sitio web, <br />[[+surl]] - La URL del sitio, <br />[[+uid]] - El nombre o ID del usuario, <br />[[+pwd]] - La contraseña del usuario, <br />[[+ufn]] - El nombre completo del usuario. <br /><br /><strong>¡Deja los campos [[+uid]] y [[+pwd]] en el email, o el nombre de usuario y la contraseña no serán enviados en el email y los usuarios no conocerán sus credenciales!</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'Hola [[+uid]]\n\nPara activar tu nueva contraseña haz clic en el siguiente enlace:\n\n[[+surl]]\n\nSi la activación se realiza con éxito, tus datos de acceso serán los siguientes:\n\nContraseña:[[+pwd]]\n\nSi no solicitaste este email entonces por favor ignóralo.\n\nSaludos,\nEl Administrador del Sitio';

$_lang['setting_websignupemail_message'] = 'Email de Registro';
$_lang['setting_websignupemail_message_desc'] = 'Introduce el mensaje que será enviado a los usuarios cuando se registre un nuevo usuario. El Administrador de Contenido enviará un email conteniendo su nombre de usuario y contraseña contraseña. <br /><strong>NOTA:</strong> Las siguientes variables son reemplazadas por el Administrador de Contenido cuando el mensaje es enviado: <br /><br />[[+sname]] - Nombre de tu sitio web, <br />[[+saddr]] - La dirección de email de contacto del sitio web, <br />[[+surl]] - La URL del sitio, <br />[[+uid]] - El nombre o ID del usuario, <br />[[+pwd]] - La contraseña del usuario, <br />[[+ufn]] - El nombre completo del usuario. <br /><br /><strong>¡Deja los campos [[+uid]] y [[+pwd]] en el email, o el nombre de usuario y la contraseña no serán enviados en el email y los usuarios no conocerán sus credenciales!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Hola [[+uid]] \n\nAquí están tus credenciales de acceso para el Panel de Administración de Contenido de [[+sname]]:\n\nNombre de Usuario: [[+uid]]\nContraseña: [[+pwd]]\n\nUna vez que entres en el Panel de Administración de Contenido ([[+surl]]), podrás cambiar tu contraseña.\n\nSaludos,\nEl Administrador del Sitio';

$_lang['setting_welcome_screen'] = 'Mostrar la Página de Bienvenida';
$_lang['setting_welcome_screen_desc'] = 'Si se activa, se mostrará la página de bienvenida la próxima vez que se cargue el sitio.';

$_lang['setting_welcome_screen_url'] = 'URL de la Página de Bienvenida';
$_lang['setting_welcome_screen_url_desc'] = 'La URL de la página de bienvenida que se cargará la primera vez que se cargue MODX Revolution.';

$_lang['setting_welcome_action'] = 'Acción de Bienvenida';
$_lang['setting_welcome_action_desc'] = 'Controlador por defecto a cargar al acceder con éxito al panel de administración cuando no se especifica ningún controlador en la URL.';

$_lang['setting_welcome_namespace'] = 'Espacio de Nombres de Bienvenida';
$_lang['setting_welcome_namespace_desc'] = 'El Espacio de Nombres al que pertenece la Acción de Bienvenida.';

$_lang['setting_which_editor'] = 'Editor a usar';
$_lang['setting_which_editor_desc'] = 'Selecciona el Editor de Texto Enriquecido a utilizar. Puedes descargar e instalar Editores de Texto Enriquecido adicionales del Administrador de Paquetes.';

$_lang['setting_which_element_editor'] = 'Editor a usar para Elementos';
$_lang['setting_which_element_editor_desc'] = 'Selecciona el Editor de Texto Enriquecido a utilizar para editar Elementos. Puedes descargar e instalar Editores de Texto Enriquecido adicionales del Administrador de Paquetes.';

$_lang['setting_xhtml_urls'] = 'URLs como XHTML';
$_lang['setting_xhtml_urls_desc'] = 'Si se activa, todas los URLs generados por MODX respetarán la sintaxis de XHTML, incluyendo la codificación del caracter del símbolo "&".';

$_lang['setting_default_context'] = 'Contexto por Defecto';
$_lang['setting_default_context_desc'] = 'Configura el Contexto que se aplicará a los nuevos Recursos.';

$_lang['setting_auto_isfolder'] = 'Set container automatically';
$_lang['setting_auto_isfolder_desc'] = 'If set to yes, container property will be changed automatically.';

$_lang['setting_default_username'] = 'Default username';
$_lang['setting_default_username_desc'] = 'Default username for an unauthenticated user.';

$_lang['setting_manager_use_fullname'] = 'Show fullname in manager header ';
$_lang['setting_manager_use_fullname_desc'] = 'If set to yes, the content of the "fullname" field will be shown in manager instead of "loginname"';
