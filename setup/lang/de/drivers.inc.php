<?php
/**
 * German Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 * @language de
 *
 * MODX Revolution translated to German by Jan-Christoph Ihrens (enigmatic_user, enigma@lunamail.de)
 */
$_lang['mysql_err_ext'] = 'MODX benötigt die mysql-Extension für PHP, aber diese scheint nicht geladen zu sein.';
$_lang['mysql_err_pdo'] = 'MODX benötigt den pdo_mysql-Treiber, wenn natives PDO verwendet wird, aber dieser scheint nicht geladen zu sein.';
$_lang['mysql_version_5051'] = 'MODX wird mit der auf diesem System installierten MySQL-Version ([[+version]]) Probleme haben wegen der vielen Bugs in Zusammenhang mit den PDO-Treibern in dieser Version. Bitte führen Sie ein MySQL-Upgrade durch, um diese Probleme zu beseitigen. Auch wenn Sie sich entscheiden, MODX nicht zu verwenden, ist es empfehlenswert, ein MySQL-Upgrade durchzuführen, um die Sicherheit und Stabilität Ihrer Website zu gewährleisten.';
$_lang['mysql_version_client_nf'] = 'MODX konnte Ihre MySQL-Client-Version nicht via mysql_get_client_info() ermitteln. Bitte vergewissern Sie sich, dass Ihre MySQL-Client-Version mindestens 4.1.20 ist, bevor Sie fortfahren.';
$_lang['mysql_version_client_start'] = 'Überprüfe MySQL-Client-Version:';
$_lang['mysql_version_client_old'] = 'MODX könnte an einigen Stellen nicht korrekt funktionieren, weil Sie eine sehr alte MySQL-Client-Version verwenden ([[+version]]). MODX wird die Installation bei Verwendung dieser MySQL-Client-Version zulassen, aber wir können nicht garantieren, dass alle Funktionen verfügbar sind oder korrekt funktionieren, wenn ältere Versionen der MySQL-Client-Bibliotheken verwendet werden.';
$_lang['mysql_version_fail'] = 'Sie verwenden MySQL [[+version]], MODX Revolution benötigt aber mindestens MySQL 4.1.20. Bitte führen Sie ein MySQL-Upgrade auf mindestens Version 4.1.20 durch.';
$_lang['mysql_version_server_nf'] = 'MODX konnte Ihre MySQL-Server-Version nicht via mysql_get_server_info() ermitteln. Bitte vergewissern Sie sich, dass Ihre MySQL-Server-Version mindestens 4.1.20 ist, bevor Sie fortfahren.';
$_lang['mysql_version_server_start'] = 'Überprüfe MySQL-Server-Version:';
$_lang['mysql_version_success'] = 'OK! Gefundene Version: [[+version]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';