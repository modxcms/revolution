<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX requires the mysql extension for PHP and it does not appear to be loaded.';
$_lang['mysql_err_pdo'] = 'MODX requires the pdo_mysql driver when native PDO is being used and it does not appear to be loaded.';
$_lang['mysql_version_5051'] = 'MODX will have issues on your MySQL version ([[+version]]), because of the many bugs related to the PDO drivers on this version. Please upgrade MySQL to patch these problems. Even if you choose not to use MODX, it is recommended you upgrade to this version for the security and stability of your own website.';
$_lang['mysql_version_client_nf'] = 'MODX could not detect your MySQL client version via mysql_get_client_info(). Please manually make sure that your MySQL client version is at least 4.1.20 before proceeding.';
$_lang['mysql_version_client_start'] = 'Checking MySQL client version:';
$_lang['mysql_version_client_old'] = 'MODX may have issues because you are using a very old MySQL client version ([[+version]]). MODX will allow installation using this MySQL client version, but we cannot guarantee all functionality will be available or work properly when using older versions of the MySQL client libraries.';
$_lang['mysql_version_fail'] = 'You are running on MySQL [[+version]], and MODX Revolution requires MySQL 4.1.20 or later. Please upgrade MySQL to at least 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODX could not detect your MySQL server version via mysql_get_server_info(). Please manually make sure that your MySQL server version is at least 4.1.20 before proceeding.';
$_lang['mysql_version_server_start'] = 'Checking MySQL server version:';
$_lang['mysql_version_success'] = 'OK! Running: [[+version]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';