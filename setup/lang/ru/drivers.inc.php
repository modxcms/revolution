<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX требуется расширение mysql для PHP, оно не загружено.';
$_lang['mysql_err_pdo'] = 'MODX требуется драйвер pdo_mysql при использовании собственного PDO, он не загружен.';
$_lang['mysql_version_5051'] = 'У MODX будут проблемы с вашей версией MySQL  ([[+version]]), которые обусловлены многочисленными ошибками, связанных с работой PDO драйвера в этой версии. Обновите MySQL для устранения этих проблем. Даже если Вы не будете использовать MODX, мы рекомендуем обновить эту версию MySQL для повышения стабильности и безопасности ваших сайтов.';
$_lang['mysql_version_client_nf'] = 'MODX не смог определить версию клиента MySQL, используя функцию mysql_get_client_info(). Проверьте версию самостоятельно, она должна быть не ниже 4.1.20.';
$_lang['mysql_version_client_start'] = 'Проверка версии MySQL клиента:';
$_lang['mysql_version_client_old'] = 'В MODX могут возникать проблемы, потому что Вы используете очень старую версию MySQL([[+version]]). MODX может продолжить установку с этой версией MySQL, но мы не можем гарантировать, что все функциональные возможности будут доступны и будут работать должным образом при использовании старых версий MySQL.';
$_lang['mysql_version_fail'] = 'Вы используете MySQL [[+version]], для MODX Revolution требуется MySQL 4.1.20 или более поздняя версия. Обновите MySQL хотя бы до 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODX не смог определить версию сервера MySQL, используя mysql_get_server_info(). Убедитесь, что версия вашего сервера не меньше 4.1.20, перед тем как продолжить.';
$_lang['mysql_version_server_start'] = 'Проверка версии MySQL сервера:';
$_lang['mysql_version_success'] = 'OK! Работает: [[+version]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';