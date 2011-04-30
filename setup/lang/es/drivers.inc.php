<?php
/**
 * Spanish Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX requiere la extensión mysql para PHP y no parece estar cargada.';
$_lang['mysql_err_pdo'] = 'MODX requiere el driver pdo_mysql cuando se está usando PDO nativo y no parece estar cargado.';
$_lang['mysql_version_5051'] = 'MODX tendrá problemas en tu versión de MySQL ([[+version]]), porque existen muchos bugs relacionados con los drivers de PDO en esta versión.  Por favor actualiza MySQL para parchar estos problemas.  Hasta si no eliges usar MODX, es recomendable que la actualices esta versión por la seguridad y estabilidad de tu sitio web.';
$_lang['mysql_version_client_nf'] = 'MODX no pudo detectar la versión de tu cliente de MySQL via mysql_get_client_info().  Por favor asegúrate manualmente de que la versión de tu cliente de MySQL es por lo menos 4.1.20 antes de proceder.';
$_lang['mysql_version_client_start'] = 'Revisando la versión del cliente de MySQL:';
$_lang['mysql_version_client_old'] = 'MODX puede tener problemas porque estás usando una versión muy vieja del cliente de MySQL ([[+version]]).  MODX permitirá la instalación usando esta versión del cliente de MySQL, pero no podemos garantizar que toda la funcionalidad estará disponible o trabajará apropiadamente cuando se usan versiónes antiguas de las librerías del cliente de MySQL.';
$_lang['mysql_version_fail'] = 'Estás corriendo la versión [[+version]] de MySQL, y MODX Revolution requiere MySQL 4.1.20 o más nuevo.  Por favor actualiza MySQL a por lo menos 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODX no pudo detectar tu versión del servidor de MySQL via mysql_get_server_info().  Por favor asegúrate manualmente de que tu versión del servidor de MySQL es por lo menos 4.1.20 antes de proceder.';
$_lang['mysql_version_server_start'] = 'Revisando la versión del servidor de MySQL:';
$_lang['mysql_version_success'] = 'OK! Corriendo: [[+version]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';