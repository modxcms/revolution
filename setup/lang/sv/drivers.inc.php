<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODx kräver tillägget mysql för PHP och det verkar inte vara laddat.';
$_lang['mysql_err_pdo'] = 'MODx kräver drivrutinen pdo_mysql när PDO används och den verkar inte vara laddad.';
$_lang['mysql_version_5051'] = 'MODx kommer att få problem med din MySQL-version ([[+version]]), på grund av de många buggar i PDO-drivrutinerna i den versionen. Uppgradera MySQL för att rätta till dessa problem. Även om du väljer att inte använda MODx så rekommenderas du att uppgradera så att din webbplats fortsätter vara säker och stabil.';
$_lang['mysql_version_client_nf'] = 'MODx kunde inte avgöra versionen på MySQL-klienten via mysql_get_client_info(). Kontrollera manuellt att versionen på din MySQL-klient är åtminstone 4.1.20 innan du fortsätter.';
$_lang['mysql_version_client_start'] = 'Kontrollerar versionen på MySQL-klienten:';
$_lang['mysql_version_client_old'] = 'MODx kan få problem på grund av att du använder en väldigt gammal version av MySQL-klienten ([[+version]]). MODx tillåter installation med denna version av MySQL-klienten, men vi kan inte garantera att all funktionalitet kommer att vara tillgänglig eller fungera som den ska när äldre versioner av MySQLs klientbibliotek används.';
$_lang['mysql_version_fail'] = 'Du kör på MySQL [[+version]], och MODx Revolution kräver MySQL 4.1.20 eller senare. Uppgradera MySQL till minst 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODx kunde inte avgöra versionen på din MySQL-server via mysql_get_server_info(). Kontrollera manuellt att versionen på MySQL-servern är minst 4.1.20 innan du fortsätter.';
$_lang['mysql_version_server_start'] = 'Kontrollerar versionen på MySQL-servern:';
$_lang['mysql_version_success'] = 'OK! Kör: [[+version]]';
