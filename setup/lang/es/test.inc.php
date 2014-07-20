<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Comprobando si <span class="mono">[[+file]]</span> existe y se puede escribir: ';
$_lang['test_config_file_nw'] = 'Para nuevas instalaciones en Linux/Unix, por favor, crear un archivo en blanco llamado <span class="mono">[[+key]].inc.php</span> en el directorio <span class="mono">config/</span> del núcleo de MODX con permisos de escritura para PHP.';
$_lang['test_db_check'] = 'Creando conexión a la base de datos: ';
$_lang['test_db_check_conn'] = 'Comprueba los detalles de conexión y prueba de nuevo.';
$_lang['test_db_failed'] = '¡La conexión a la base de datos falló!';
$_lang['test_db_setup_create'] = 'La instalación intentará crear la base de datos.';
$_lang['test_dependencies'] = 'Comprobando PHP para la dependencia zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'La instalación de PHP no tiene la extensión "zlib" instalada. Esta extensión es necesaria para el funcionamiento de MODX. Por favor, habilitar la extensión para continuar.';
$_lang['test_directory_exists'] = 'Comprobando si existe el directorio <span class="mono">[[+dir]]</span>: ';
$_lang['test_directory_writable'] = 'Comprobando si se puede escribir en el directorio <span class="mono">[[+dir]]</span>: ';
$_lang['test_memory_limit'] = 'Comprobando si el límite de memoria configurado es de, al menos, 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX detectó que el parámetro memory_limit se encuentra configurado en [[+memory]], por debajo del recomendado de 24M. MODX intentó configurar dicho límite a 24M, pero se produjo un error. Por favor, configura el parámetro memory_limit en el fichero php.ini a 24M o más antes de continuar. Si sigues experimentando problemas (como que se muestre una pantalla en blanco durante la instalación), configurar a 32M, 64M o mayor.';
$_lang['test_memory_limit_success'] = '¡OK! Configurado en [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX tendrá problemas con la versión actual de MySQL ([[+version]]), debido a los múltiples bugs relacionados con el driver de PDO de esta versión. Por favor, actualiza MySQL para solventar este problema. Incluso si decides no utilizar MODX, es recomendable actualizar MySQL para mejorar la seguridad y estabilidad del sitio.';
$_lang['test_mysql_version_client_nf'] = '¡No se pudo detectar la versión del cliente MySQL!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX no pudo detectar la versión del cliente MySQL a través de mysql_get_client_info(). Por favor, comprueba manualmente que la versión del cliente MySQL es al menos 4.1.20 antes de continuar.';
$_lang['test_mysql_version_client_old'] = 'MODX podría tener varios problemas usando una versión tan antigua del cliente MySQL ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'Se permitirá la instalación de MODX con esta versión del cliente MySQL, pero no se garantiza que todas las funcionalidades estén disponibles o funcionen correctamente utilizando versiones antiguas de las librerías del cliente MySQL.';
$_lang['test_mysql_version_client_start'] = 'Comprobando versión del cliente MySQL:';
$_lang['test_mysql_version_fail'] = 'Estás usando la versión [[+version]] de MySQL, y MODX Revolution necesita como mínimo la versión 4.1.20 o posterior. Por favor, actualiza MySQL a, al menos, la versión 4.1.20.';
$_lang['test_mysql_version_server_nf'] = '¡No se pudo detectar la versión del servidor MySQL!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX no pudo detectar la versión del servidor MySQL a través de mysql_get_server_info(). Por favor, comprueba manualmente que la versión del servidor MySQL es al menos 4.1.20 antes de continuar.';
$_lang['test_mysql_version_server_start'] = 'Comprobando versión del servidor MySQL:';
$_lang['test_mysql_version_success'] = '¡OK! Versión: [[+version]]';
$_lang['test_nocompress'] = 'Comprobando si se debe desactivar la compresión de CSS/JS: ';
$_lang['test_nocompress_disabled'] = '¡OK! Desactivada.';
$_lang['test_nocompress_skip'] = 'No seleccionado, omitiendo test.';
$_lang['test_php_version_fail'] = 'La versión actual de PHP es [[+version]], y MODX Revolution necesita la versión 5.1.1 o posterior. Por favor, actualizar PHP a, al menos, la versión 5.1.1. MODX recomienda actualizar al menos a la versión 5.3.2+.';
$_lang['test_php_version_516'] = 'MODX tendrá problemas con la versión actual de PHP ([[+version]]), debido a los múltiples bugs relacionados con el driver de PDO de esta versión. Por favor, actualizar PHP a la versión 5.3.0 o posterior, que soluciona estos problemas. MODX recomienda actualizar a, al menos, la versión 5.3.2+. Incluso si decides no utilizar MODX, es recomendable actualizar PHP para mejorar la seguridad y estabilidad del sitio.';
$_lang['test_php_version_520'] = 'MODX tendrá problemas con la versión actual de PHP ([[+version]]), debido a los múltiples bugs relacionados con el driver de PDO de esta versión. Por favor, actualizar PHP a la versión 5.3.0 o posterior, que soluciona estos problemas. MODX recomienda actualizar a, al menos, la versión 5.3.2+. Incluso si decides no utilizar MODX, es recomendable actualziar PHP para mejorar la seguridad y estabilidad del sitio.';
$_lang['test_php_version_start'] = 'Comprobando versión de PHP:';
$_lang['test_php_version_success'] = '¡OK! Versión: [[+version]]';
$_lang['test_safe_mode_start'] = 'Asegúrate de que safe_mode está desactivado:';
$_lang['test_safe_mode_fail'] = 'MODX ha encontrado safe_mode activado. Debes deshabilitar safe_mode en la configuración de PHP para continuar.';
$_lang['test_sessions_start'] = 'Comprobando si las sesiones están configuradas correctamente:';
$_lang['test_simplexml'] = 'Buscando SimpleXML:';
$_lang['test_simplexml_nf'] = 'No se pudo encontrar SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX no pudo encontrar SimpleXML en el entorno de PHP. El Gestor de Paquetes y otras funciones no funcionarán si no está instalado. Se puede continuar con la instalación, pero MODX recomienda habilitar SimpleXML para las características y funcionalidades avanzadas.';
$_lang['test_suhosin'] = 'Buscando problemas en suhosin:';
$_lang['test_suhosin_max_length'] = '¡Valor GET de Suhosin demasiado bajo!';
$_lang['test_suhosin_max_length_err'] = 'Actualmente se está utilizando la extensión suhosin de PHP, y el parámetro suhosin.get.max_value_length tiene configurado un valor demasiado bajo para MODX para comprimir correctamente archivos JS en el panel de administración. MODX recomienda incrementar este valor hasta 4096; hasta entonces, MODX configurará la compresión de JS (compress_js) a 0 para prevenir errores.';
$_lang['test_table_prefix'] = 'Comprobando el prefijo de tabla `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = '¡El prefijo de tabla ya está en uso en esta base de datos!';
$_lang['test_table_prefix_inuse_desc'] = 'No se puede instalar en la base de datos seleccionada, ya que contiene tablas con el prefijo especificado. Por favor, introduce un nuevo prefijo de tabla y ejecutar la instalación de nuevo.';
$_lang['test_table_prefix_nf'] = '¡El prefijo de tabla no existe en esta base de datos!';
$_lang['test_table_prefix_nf_desc'] = 'No se puede instalar en la base de datos seleccionada, ya que no contiene tablas con el prefijo especificado. Por favor, introduce un prefijo de tabla existente y ejecuta la instalación de nuevo.';
$_lang['test_zip_memory_limit'] = 'Comprobando si el límite de memoria está configurado como mínimo en 24M para extensiones zip: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX detectó que el parámetro memory_limit se encuentra configurado por debajo del recomendado de 24M. MODX intentó configurar dicho límite a 24M, pero se produjo un error. Por favor, configura el parámetro memory_limit en el fichero php.ini a 24M o más antes de continuar para que las extensiones zip puedan funcionar correctamente.';