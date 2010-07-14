<?php
/**
 * Test-related Swedish Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Kontrollerar om <span class="mono">%s</span> existerar och är skrivbar: ';
$_lang['test_config_file_nw'] = 'För nya installationer i Linux/Unix-miljö måste en tom fil med namnet <span class="mono">%s.inc.php</span> skapas i din MODx-kärnas <span class="mono">config/</span>-katalog med åtkomsträttigheterna satta så att den blir skrivbar av PHP.';
$_lang['test_db_check'] = 'Skapar anslutning till databasen: ';
$_lang['test_db_check_conn'] = 'Kontrollera anslutningsdetaljerna och försök sedan igen.';
$_lang['test_db_failed'] = 'Anslutningen till databasen misslyckades!';
$_lang['test_db_setup_create'] = 'Installationsprogrammet kommer att försöka skapa databasen.';
$_lang['test_dependencies'] = 'Kontrollerar att PHP har zlib installerat: ';
$_lang['test_dependencies_fail_zlib'] = 'Din PHP-installation har inte tillägget zlib installerat. Detta tillägg behövs för att MODx ska kunna köras. Aktivera det för att fortsätta.';
$_lang['test_directory_exists'] = 'Kontrollerar om katalogen <span class="mono">%s</span> finns: ';
$_lang['test_directory_writable'] = 'Kontrollerar om katalogen <span class="mono">%s</span> är skrivbar: ';
$_lang['test_memory_limit'] = 'Kontrollerar om minnesgränsen är satt till minst 24M: ';
$_lang['test_memory_limit_fail'] = 'MODx upptäckte att din inställning för memory_limit  är lägre än den rekommenderade 24M. MODx försökte utan framgång att ändra memory_limit till 24M. Ange inställningen memory_limit i din php.ini-fil till 24M eller mer innan du fortsätter. Om du fortfarande har problem (till exempel en tom vit skärm vid installationen), sätt värdet till 32M, 64M eller högre.';
$_lang['test_memory_limit_success'] = 'OK! Satt till %s';
$_lang['test_mysql_version_5051'] = 'MODx kommer att få problem med din MySQL-version (%s), på grund av de många buggar i PDO-drivrutinerna i den versionen. Uppgradera MySQL för att rätta till dessa problem. Även om du väljer att inte använda MODx så rekommenderas du att uppgradera så att din webbplats fortsätter vara säker och stabil.';
$_lang['test_mysql_version_client_nf'] = 'Kunde inte avgöra versionen på MySQL-klienten!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODx kunde inte avgöra versionen på din MySQL-klient via mysql_get_client_info(). Kontrollera manuellt att versionen på MySQL-klienten är minst 4.1.20 innan du fortsätter.';
$_lang['test_mysql_version_client_old'] = 'MODx kan få problem på grund av att du använder en väldigt gammal version av MySQL-klienten';
$_lang['test_mysql_version_client_old_msg'] = 'MODx tillåter installation med denna version av MySQL-klienten, men vi kan inte garantera att all funktionalitet kommer att vara tillgänglig eller fungera som den ska när äldre versioner av MySQLs klientbibliotek används.';
$_lang['test_mysql_version_client_start'] = 'Kontrollerar versionen på MySQL-klienten:';
$_lang['test_mysql_version_fail'] = 'Du kör på MySQL %s, och MODx kräver MySQL 4.1.20 eller senare. Uppgradera MySQL till minst 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Kunde inte avgöra versionen på MySQL-servern!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODx kunde inte avgöra versionen på din MySQL-server via mysql_get_server_info(). Kontrollera manuellt att versionen på MySQL-servern är minst 4.1.20 innan du fortsätter.';
$_lang['test_mysql_version_server_start'] = 'Kontrollerar versionen på MySQL-servern:';
$_lang['test_mysql_version_success'] = 'OK! Kör: %s';
$_lang['test_php_version_fail'] = 'Du kör på PHP %s, och MODx kräver PHP 5.1.1 eller senare. Uppgradera PHP till minst 5.1.1. MODx rekommenderar en uppgradering till 5.3.0.';
$_lang['test_php_version_516'] = 'MODx kommer att få problem med din PHP-version (%s), på grund av de många buggarna i PDO-drivrutinerna i den versionen. Uppgradera PHP till version 5.3.0 eller högre för att rätta till dessa problem. MODx rekommenderar en uppgradering till 5.3.2+. Även om du väljer att inte använda MODx så rekommenderas du att uppgradera så att din webbplats fortsätter vara säker och stabil.';
$_lang['test_php_version_520'] = 'MODx kommer att få problem med din PHP-version (%s), på grund av de många buggarna i PDO-drivrutinerna i den versionen. Uppgradera PHP till version 5.3.0 eller högre för att rätta till dessa problem. MODx rekommenderar en uppgradering till 5.3.2+. Även om du väljer att inte använda MODx så rekommenderas du att uppgradera så att din webbplats fortsätter vara säker och stabil.';
$_lang['test_php_version_start'] = 'Kontrollerar PHP-version:';
$_lang['test_php_version_success'] = 'OK! Kör: %s';
$_lang['test_sessions_start'] = 'Kontrollerar att sessioner är korrekt konfigurarade:';
$_lang['test_simplexml'] = 'Kontrollerar SimpleXML:';
$_lang['test_simplexml_nf'] = 'Kunde inte hitta SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODx kunde inte hitta SimpleXML i din PHP-miljö. Pakethantering och annan funktionalitet kommer inte att fungera utan att denna är installerad. Du kan fortsätta installationen, men MODx rekommenderar att SimpleXML aktiveras för att få tillgång till avancerade möjligheter och funktionalitet.';
$_lang['test_table_prefix'] = 'Kontrollerar tabellprefixet `%s`: ';
$_lang['test_table_prefix_inuse'] = 'Tabellprefixet används redan i denna databas!';
$_lang['test_table_prefix_inuse_desc'] = 'Installationsprogrammet kunde inte installera till den angivna databasen eftersom den redan innehöll tabeller med det prefix du angav. Ange ett nytt tabellprefix och kör sedan installationsprogrammet igen.';
$_lang['test_table_prefix_nf'] = 'Tabellprefixet finns inte i den här databasen!';
$_lang['test_table_prefix_nf_desc'] = 'Installationsprogrammet kunde inte installera till den angivna databasen eftersom den inte innehåller tabeller med det prefix som du angav för uppgradering. Ange ett existerande tabellprefix och kör sedan installationsprogrammet igen.';
$_lang['test_zip_memory_limit'] = 'Kontrollerar om minnesgränsen är satt till minst 24M för zip-tillägg: ';
$_lang['test_zip_memory_limit_fail'] = 'MODx upptäckte att din inställning för memory_limit är lägre än den rekommenderade 24M. MODx försökte utan framgång att ändra memory_limit till 24M. Ange inställningen memory_limit i din php.ini-fil till 24M eller mer innan du fortsätter så att zip-tillägg kan fungera korrekt.';