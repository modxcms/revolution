<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'A MODX megköveteli a PHP mysql kiterjesztését, és úgy tűnik, ez nincs betöltve.';
$_lang['mysql_err_pdo'] = 'A MODX megköveteli a pdo_mysql vezérlőt, ha natív PDO van használatban, és úgy látszik, hogy ez nincs betöltve.';
$_lang['mysql_version_5051'] = 'A MODX hibákat ad a betöltött MySQL verzión ([[+version]]) az ehhet tartozó PDO meghajtó számos hibája miatt. Frissítse a MySQL-t ezek megelőzésére. Még ha nem is a MODX használata mellett dönt, javasolt a frissítés a weboldala biztonsága és megbízható működése érdekében.';
$_lang['mysql_version_client_nf'] = 'A MODX nem tudta felismerni a MySQL kliens verzióját a mysql_get_client_info() meghívásával. Kérjük, hogy továbblépés előtt győződjön meg róla, hogy a MySQL kliens verziója legalább 4.1.20.';
$_lang['mysql_version_client_start'] = 'MySQL kliens verziójának ellenőrzése:';
$_lang['mysql_version_client_old'] = 'A MODX hibákat adhat, mert nagyon régi MySQL kliens verziót ([[+version]]) használ. A MODX engedi a telepítést ezzel a MySQL kliens verzióval, de nem tudjuk biztosítani, hogy minden működés elérhető lesz vagy megfelelően fog működni, ha a MySQL kliens könyvtár régebbi verzióit használja.';
$_lang['mysql_version_fail'] = 'A MySQL [[+version]] fut, és a MODX Revolution a MySQL 4.1.20 vagy újabb változatát igényli. Kérjük, frissítse a MySQL-t legalább 4.1.20-ra.';
$_lang['mysql_version_server_nf'] = 'A MODX nem tudta felismerni a MySQL kiszolgáló verzióját a mysql_get_server_info() meghívásával. Kérjük, hogy továbblépés előtt győződjön meg róla, hogy a MySQL kiszolgáló verziója legalább 4.1.20.';
$_lang['mysql_version_server_start'] = 'MySQL kiszolgáló verziójának ellenőrzése:';
$_lang['mysql_version_success'] = 'Rendben! Fut a [[+version]] verzió';

$_lang['sqlsrv_version_success'] = 'Rendben!';
$_lang['sqlsrv_version_client_success'] = 'Rendben!';