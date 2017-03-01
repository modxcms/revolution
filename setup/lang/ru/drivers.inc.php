<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX требуется расширение mysql для PHP и похоже оно не установлено.';
$_lang['mysql_err_pdo'] = 'MODX требует pdo_mysql драйвер, когда используется PHP с собственным PDO, и он не был загружен.';
$_lang['mysql_version_5051'] = 'У MODX будут проблемы с вашей версией MySQL  ([[+version]]), которые обусловлены многочисленными ошибками, связанных с работой PDO драйвера в этой версии. Обновите MySQL для устранения этих проблем. Даже если Вы не будете использовать MODX, мы рекомендуем обновить эту версию MySQL для повышения стабильности и безопасности ваших сайтов.';
$_lang['mysql_version_client_nf'] = 'MODX не смог определить версию клиента MySQL, используя функцию mysql_get_client_info(). Проверьте версию самостоятельно, она должна быть не ниже 4.1.20.';
$_lang['mysql_version_client_start'] = 'Проверка версии клиента MySQL:';
$_lang['mysql_version_client_old'] = 'В MODX могут возникать проблемы, потому что вы используете очень старую версию клиента MySQL ([[+version]]). MODX может продолжить установку с этой версией MySQL, но мы не можем гарантировать, что все функциональные возможности будут доступны и будут работать должным образом при использовании старых версий MySQL.';
$_lang['mysql_version_fail'] = 'Вы используете MySQL [[+version]], но MODX Revolution требует MySQL 4.1.20 или выше. Обновите MySQL до версии не ниже 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODX не смог определить версию сервера MySQL, используя функцию mysql_get_server_info(). Проверьте её самостоятельно, она должна быть не ниже 4.1.20.';
$_lang['mysql_version_server_start'] = 'Проверка версии сервера MySQL:';
$_lang['mysql_version_success'] = 'ОК! Работает: [[+version]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';