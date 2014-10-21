<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX potrzebuje by PHP było rozbudowane o rozszerzenie mysql.  Nie wykryto takiego rozszerzenia.';
$_lang['mysql_err_pdo'] = 'MODX requires the pdo_mysql driver when native PDO is being used and it does not appear to be loaded.';
$_lang['mysql_version_5051'] = 'MODX może nie działać prawidłowo na Twojej wersji bazy MySQL ([[+version]]), z powodu wielu błędów związanych z obsługą sterowników PDO wykrytych w tej wersji. Proszę uaktualnij bazę MySQL do wersji, która jest wolna od tych błędów. Nawet jeśli zrezygnujesz z używania MODX i tak należy zaktualizować bazę by podnieść poziom bezpieczeństwa i stabilności Twojej strony internetowej.';
$_lang['mysql_version_client_nf'] = 'MODX nie może wykryć wersji klienta MySQL poprzez funkcję mysql_get_client_info(). Przed pójściem dalej upewnij się, że Twoja wersja klienta MySQL to 4.1.20 lub wyższa.';
$_lang['mysql_version_client_start'] = 'Sprawdzam wersję klienta MySQL:';
$_lang['mysql_version_client_old'] = 'MODX może nie działać prawidłowo, ponieważ używasz bardzo starej wersji klienta MySQL ([[+version]]).  Instalacja MODX nie zostanie zatrzymana, jednak nie możemy gwarantować poprawnego działania systemu ani dostępu do wszystkich jego funkcji podczas pracy w oparciu o przestarzałą wersję MySQL.';
$_lang['mysql_version_fail'] = 'Twoja baza MySQL jest w wersji [[+version]]. MODX Revolution wymaga wersji MySQL 4.1.20 lub późniejszej. Proszę uaktualnij MySQL przynajmniej do wersji nr 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODX nie potrafi wykryć wersji Twojego serwera MySQL za pomocą funkcji mysql_get_server_info(). Proszę, przed kontynuacją upewnij się, że Twoja wersja serwera MySQL to co najmniej 4.1.20.';
$_lang['mysql_version_server_start'] = 'Sprawdzam wersję serwera MySQL:';
$_lang['mysql_version_success'] = 'OK! Wersja: [[+version]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';