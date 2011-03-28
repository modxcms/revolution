<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX requiert l\'extenssion mysql pour PHP et il semblerait qu\'elle ne soit pas chargée.';
$_lang['mysql_err_pdo'] = 'MODX requiert le driver pdo_mysql lorsque PDO est utilisé et il semblerait qu\'il ne soit pas chargé.';
$_lang['mysql_version_5051'] = 'MODX rencontrera des problèmes avec votre version de MySQL ([[+version]]), à cause de plusieurs bugs liés au driver PDO de cette version. Veuillez mettre à jour MySQL pour corriger ces problèmes. Même si vous choisissez de ne pas utiliser MODX, il est recommandé de mettre à jour MySQL pour la sécurité et la stabilité de votre site.';
$_lang['mysql_version_client_nf'] = 'MODX n\'a pu détecter votre version de MySQL client via mysql_get_client_info(). Veuillez vous assurer manuellement que votre version de MySQL client est au moins 4.1.20 avant de continuer.';
$_lang['mysql_version_client_start'] = 'Vérification de la version de MySQL client:';
$_lang['mysql_version_client_old'] = 'MODX peut rencontrer des problèmes parceque vous utilisez une très vieille version de MySQL client ([[+version]]). MODX autorise l\'installation en utilisant cette version de MySQL client, mais nous ne pouvons garantir que toutes les fonctionnalités seront disponibles ou fonctionneront correctement lors de l\'utilisation d\'anciennes versions des librairies MySQL client.';
$_lang['mysql_version_fail'] = 'Vous utilisez MySQL [[+version]], et MODX Revolution requiert MySQL 4.1.20 ou supérieur. Veuillez mettre à jour MySQL à la version 4.1.20 minimum.';
$_lang['mysql_version_server_nf'] = 'MODX n\'a pu détecter votre version de MySQL server via mysql_get_server_info(). Veuillez vous assurer manuellement que votre version de MySQL server est au moins 4.1.20 avant de continuer.';
$_lang['mysql_version_server_start'] = 'Vérification de la version de MySQL server:';
$_lang['mysql_version_success'] = 'OK! Version: [[+version]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';