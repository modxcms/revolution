<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX har brug for mysql udvidelsen til PHP og den lader ikke til at være indlæst.';
$_lang['mysql_err_pdo'] = 'MODX requires the pdo_mysql driver when native PDO is being used and it does not appear to be loaded.';
$_lang['mysql_version_5051'] = 'MODX vil have problemer på din MySQL version ([[+version]]), på grund af de mange fejl relateret til PDO driverne på denne version. Opgrader venligst MySQL for at rette disse problemer. Selv hvis du vælger ikke at bruge MODX, anbefales det, at du har opgraderet til denne version for sikkerheden og stabiliteten af din egen hjemmeside.';
$_lang['mysql_version_client_nf'] = 'MODX kunne ikke registrere din MySQL klientversion via mysql_get_client_info(). Sørg venligst manuelt for at din MySQL klientversion er mindst 4.1.20 inden du fortsætter.';
$_lang['mysql_version_client_start'] = 'Kontrollerer MySQL klientversion:';
$_lang['mysql_version_client_old'] = 'MODX kan have problemer, fordi du bruger en meget gammel MySQL klientversion ([[+version]]). MODX vil tillade installation med denne MySQL klientversion, men vi kan ikke garantere, at alle funktioner vil være tilgængelige eller virke ordentligt ved brug af ældre versioner af MySQL klienter.';
$_lang['mysql_version_fail'] = 'Du kører på MySQL [[+version]], og MODX Revolution har brug for MySQL 4.1.20 eller senere. Opgrader venligst MySQL til mindst 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODX kunne ikke registrere din MySQL serverversion via mysql_get_server_info(). Sørg venligst manuelt for at din MySQL serverversion er mindst 4.1.20 inden du fortsætter.';
$_lang['mysql_version_server_start'] = 'Kontrollerer MySQL serverversion:';
$_lang['mysql_version_success'] = 'OK! Kører: [[+version]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';