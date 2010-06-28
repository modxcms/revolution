<?php
/**
 * Test-related German Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Überprüfe, ob <span class="mono">%s</span> existiert und beschreibbar ist: ';
$_lang['test_config_file_nw'] = 'Für neue Linux-/Unix-Installationen erstellen Sie bitte eine leere Datei mit dem Dateinamen <span class="mono">%s.inc.php</span> im Verzeichnis <span class="mono">core/config/</span> und setzen sie die Dateirechte so, dass die Datei für PHP beschreibbar ist.';
$_lang['test_db_check'] = 'Stelle Verbindung zur Datenbank her: ';
$_lang['test_db_check_conn'] = 'Bitte überprüfen Sie die Verbindungsdaten und versuchen Sie es erneut.';
$_lang['test_db_failed'] = 'Datenbankverbindung fehlgeschlagen!';
$_lang['test_db_setup_create'] = 'Setup versucht, die Datenbank zu erstellen.';
$_lang['test_dependencies'] = 'Überprüfe, ob die PHP-Extension "zlib" installiert ist: ';
$_lang['test_dependencies_fail_zlib'] = 'In Ihrer PHP-Installation ist die "zlib"-Extension nicht verfügbar. Diese Extension wird von MODx benötigt. Bitte aktivieren Sie sie, bevor Sie fortfahren.';
$_lang['test_directory_exists'] = 'Überprüfe, ob das Verzeichnis <span class="mono">%s</span> existiert: ';
$_lang['test_directory_writable'] = 'Überprüfe, ob das Verzeichnis <span class="mono">%s</span> beschreibbar ist: ';
$_lang['test_memory_limit'] = 'Überprüfe, ob das memory_limit auf mindestens 24M gesetzt ist: ';
$_lang['test_memory_limit_fail'] = 'MODx hat festgestellt, dass Ihre memory_limit-Einstellung unter dem empfohlenen Wert von 24M liegt. MODx hat versucht, das memory_limit auf 24M zu setzen, was aber fehlgeschlagen ist. Bitte setzen Sie das memory_limit in Ihrer php.ini-Datei auf mindestens 24M, bevor Sie fortfahren. Falls Sie dennoch Probleme feststellen (z.B. eine leere weiße Seite während der Installation sehen), setzen Sie den Wert auf 32M, 64M oder höher.';
$_lang['test_memory_limit_success'] = 'OK! Aktueller Wert: %s';
$_lang['test_mysql_version_5051'] = 'MODx wird mit der auf diesem System installierten MySQL-Version (%s) Probleme haben wegen der vielen Bugs in Zusammenhang mit den PDO-Treibern in dieser Version. Bitte führen Sie ein MySQL-Upgrade durch, um diese Probleme zu beseitigen. Auch wenn Sie sich entscheiden, MODx nicht zu verwenden, ist es empfehlenswert, ein MySQL-Upgrade durchzuführen, um die Sicherheit und Stabilität Ihrer Website zu gewährleisten.';
$_lang['test_mysql_version_client_nf'] = 'Konnte die MySQL-Client-Version nicht feststellen!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODx konnte Ihre MySQL-Client-Version nicht via mysql_get_client_info() ermitteln. Bitte vergewissern Sie sich, dass Ihre MySQL-Client-Version mindestens 4.1.20 ist, bevor Sie fortfahren.';
$_lang['test_mysql_version_client_start'] = 'Überprüfe MySQL-Client-Version:';
$_lang['test_mysql_version_fail'] = 'Sie verwenden MySQL %s, MODx Revolution benötigt aber mindestens MySQL 4.1.20. Bitte führen Sie ein MySQL-Upgrade auf mindestens Version 4.1.20 durch.';
$_lang['test_mysql_version_server_nf'] = 'Konnte die MySQL-Server-Version nicht feststellen!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODx konnte Ihre MySQL-Server-Version nicht via mysql_get_server_info() ermitteln. Bitte vergewissern Sie sich, dass Ihre MySQL-Server-Version mindestens 4.1.20 ist, bevor Sie fortfahren.';
$_lang['test_mysql_version_server_start'] = 'Überprüfe MySQL-Server-Version:';
$_lang['test_mysql_version_success'] = 'OK! Gefundene Version: %s';
$_lang['test_php_version_fail'] = 'Sie verwenden PHP %s, MODx Revolution benötigt aber mindestens PHP 5.1.1. Bitte führen Sie ein PHP-Upgrade auf mindestens Version 5.1.1 durch. MODx empfiehlt ein Upgrade auf Version 5.3.0 oder höher.';
$_lang['test_php_version_516'] = 'MODx wird mit der auf diesem System installierten PHP-Version (%s) Probleme haben wegen der vielen Bugs in Zusammenhang mit den PDO-Treibern in dieser Version. Bitte führen Sie ein PHP-Upgrade auf Version 5.3.0 oder höher durch, wodurch diese Probleme behoben werden. MODx empfiehlt ein Upgrade auf Version 5.3.2 oder höher. Auch wenn Sie sich entscheiden, MODx nicht zu verwenden, ist es empfehlenswert, ein PHP-Upgrade durchzuführen, um die Sicherheit und Stabilität Ihrer Website zu gewährleisten.';
$_lang['test_php_version_520'] = 'MODx wird mit der auf diesem System installierten PHP-Version (%s) Probleme haben wegen der vielen Bugs in Zusammenhang mit den PDO-Treibern in dieser Version. Bitte führen Sie ein PHP-Upgrade auf Version 5.3.0 oder höher durch, wodurch diese Probleme behoben werden. MODx empfiehlt ein Upgrade auf Version 5.3.2 oder höher. Auch wenn Sie sich entscheiden, MODx nicht zu verwenden, ist es empfehlenswert, ein PHP-Upgrade durchzuführen, um die Sicherheit und Stabilität Ihrer Website zu gewährleisten.';
$_lang['test_php_version_start'] = 'Überprüfe PHP-Version:';
$_lang['test_php_version_success'] = 'OK! Gefundene Version: %s';
$_lang['test_sessions_start'] = 'Überprüfe, ob Sessions korrekt konfiguriert sind:';
$_lang['test_simplexml'] = 'Überprüfe, ob SimpleXML verfügbar ist:';
$_lang['test_simplexml_nf'] = 'Konnte SimpleXML nicht finden!';
$_lang['test_simplexml_nf_msg'] = 'MODx konnte SimpleXML in Ihrer PHP-Umgebung nicht finden. Die Package-Verwaltung und andere Features werden nicht funktionieren, wenn SimpleXML nicht installiert ist. Sie können mit der Installation fortfahren, aber MODx empfiehlt, SimpleXML zu aktivieren, um zusätzliche Features und Funktionen verfügbar zu machen.';
$_lang['test_table_prefix'] = 'Überprüfe das Tabellenpräfix "%s": ';
$_lang['test_table_prefix_inuse'] = 'Das Tabellenpräfix wird in dieser Datenbank bereits verwendet!';
$_lang['test_table_prefix_inuse_desc'] = 'Setup konnte die Installation in die gewählte Datenbank nicht vornehmen, da sie bereits Tabellen mit dem angegebenen Präfix enthält. Bitte wählen Sie ein anderes Präfix und starten Sie die Installation erneut.';
$_lang['test_table_prefix_nf'] = 'Das Tabellenpräfix existiert in dieser Datenbank nicht!';
$_lang['test_table_prefix_nf_desc'] = 'Setup konnte die Installation in die gewählte Datenbank nicht vornehmen, da sie keine Tabellen mit dem angegebenen Präfix enthält, für die ein Upgrade vorgenommen werden könnte. Bitte wählen Sie ein existierendes Tabellen-Präfix und starten Sie die Installation erneut.';
$_lang['test_zip_memory_limit'] = 'Überprüfe, ob das memory_limit für zip-Extensions auf mindestens 24M gesetzt ist: ';
$_lang['test_zip_memory_limit_fail'] = 'MODx hat festgestellt, dass Ihre memory_limit-Einstellung unter dem empfohlenen Wert von 24M liegt. MODx hat versucht, das memory_limit auf 24M zu setzen, was aber fehlgeschlagen ist. Bitte setzen Sie das memory_limit in Ihrer php.ini-Datei auf mindestens 24M, bevor Sie fortfahren, damit die zip-Extensions korrekt funktionieren können.';