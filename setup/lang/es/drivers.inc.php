<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX requiere la extensión mysql para PHP, pero no parece estar cargada.';
$_lang['mysql_err_pdo'] = 'MODX requiere el driver pdo_mysql cuando se está usando PDO nativo, pero no parece estar cargado.';
$_lang['mysql_version_5051'] = 'MODX tendrá problemas con la versión actual de MySQL ([[+version]]), debido a los múltiples bugs relacionados con el driver de PDO de esta versión. Por favor, actualiza MySQL para solventar este problema. Incluso si decides no utilizar MODX, es recomendable actualizar MySQL para mejorar la seguridad y estabilidad del sitio.';
$_lang['mysql_version_client_nf'] = 'MODX no pudo detectar la versión del cliente MySQL a través de mysql_get_client_info(). Por favor, comprueba manualmente que la versión del cliente MySQL es al menos 4.1.20 antes de continuar.';
$_lang['mysql_version_client_start'] = 'Comprobando versión del cliente MySQL:';
$_lang['mysql_version_client_old'] = 'MODX podría tener varios problemas usando una versión tan antigua del cliente MySQL ([[+version]]). Se permitirá la instalación de MODX con esta versión del cliente MySQL, pero no se garantiza que todas las funcionalidades estén disponibles o funcionen correctamente utilizando versiones antiguas de las librerías del cliente MySQL.';
$_lang['mysql_version_fail'] = 'Estás usando la versión [[+version]] de MySQL, y MODX Revolution necesita como mínimo la versión 4.1.20 o posterior. Por favor, actualiza MySQL a, al menos, la versión 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODX no pudo detectar la versión del servidor MySQL a través de mysql_get_server_info(). Por favor, comprueba manualmente que la versión del servidor MySQL es al menos 4.1.20 antes de continuar.';
$_lang['mysql_version_server_start'] = 'Comprobando versión del servidor MySQL:';
$_lang['mysql_version_success'] = '¡OK! Versión: [[+version]]';

$_lang['sqlsrv_version_success'] = '¡OK!';
$_lang['sqlsrv_version_client_success'] = '¡OK!';