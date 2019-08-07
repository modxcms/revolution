<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Ellenőrzés, hogy <span class="mono">[[+file]]</span> létezik és írható: ';
$_lang['test_config_file_nw'] = 'Új Linux/Unix telepítésnél kérjük, hozza létre a <span class="mono">[[+key]].inc.php</span> üres állományt a MODX alap <span class="mono">config/</span> könyvtárában a PHP által írható engedélyekkel.';
$_lang['test_db_check'] = 'Kapcsolódás létrehozása az adatbázishoz: ';
$_lang['test_db_check_conn'] = 'Ellenőrizze a kapcsolódás részleteit és próbálja újra.';
$_lang['test_db_failed'] = 'Sikertelen kapcsolódás az adatbázishoz!';
$_lang['test_db_setup_create'] = 'A telepítő megkísérli létrehozni az adatbázist.';
$_lang['test_dependencies'] = 'A PHP ellenőrzése a zlib függőség miatt: ';
$_lang['test_dependencies_fail_zlib'] = 'A telepített PHP nem tartalmazza a "zlib" kiterjesztést. Ez a kiterjesztés nélkülözhetetlen a MODX működéséhez. Kérjük, adja hozzá, mielőtt folytatja.';
$_lang['test_directory_exists'] = 'Ellenőrzés, hogy <span class="mono">[[+dir]]</span> mappa létezik: ';
$_lang['test_directory_writable'] = 'Ellenőrzés, hogy <span class="mono">[[+dir]]</span> mappa írható: ';
$_lang['test_memory_limit'] = 'Ellenőrizzük, hogy a memóriakorlát legalább 24M: ';
$_lang['test_memory_limit_fail'] = 'A MODX a memory_limit beállításánál [[+memory]] értéket találta, a javasolt 24M érték alatt. A MODX próbálta beállítani a memory_limit értékét 24M-ra, de nem sikerült. Kérjük, állítsa a memory_limit értékét a php.ini állományban legalább 24M vagy nagyobb értékre, mielőtt folytatja. Ha így is gondok vannak (pl. üres fehér képernyőt lát telepítéskor), állítsa 32M, 64M vagy nagyobb értékre.';
$_lang['test_memory_limit_success'] = 'Rendben! [[+memory]] beállítva';
$_lang['test_mysql_version_5051'] = 'A MODX hibákat ad a betöltött MySQL verzión ([[+version]]) az ehhez tartozó PDO meghajtó számos hibája miatt. Frissítse a MySQL-t ezek megelőzésére. Még ha nem is a MODX használata mellett dönt, javasolt a frissítés a weboldala biztonsága és megbízható működése érdekében.';
$_lang['test_mysql_version_client_nf'] = 'MySQL kliens verziója nem derült ki!';
$_lang['test_mysql_version_client_nf_msg'] = 'A MODX nem tudta felismerni a MySQL kliens verzióját a mysql_get_client_info() meghívásával. Kérjük, hogy továbblépés előtt győződjön meg róla, hogy a MySQL kliens verziója legalább 4.1.20.';
$_lang['test_mysql_version_client_old'] = 'A MODX hibákat adhat, mert nagyon régi verziójú MySQL klienst ([[+version]]) használ';
$_lang['test_mysql_version_client_old_msg'] = 'A MODX engedélyezi a telepítést ezzel a MySQL kliens verzióval, de nem tudja biztosítani, hogy minden működés elérhető lesz vagy megfelelően fog működni a MySQL régebbi verzióival.';
$_lang['test_mysql_version_client_start'] = 'MySQL kliens verziójának ellenőrzése:';
$_lang['test_mysql_version_fail'] = 'A MySQL [[+version]] fut, és a MODX Revolution a MySQL 4.1.20 vagy újabb változatát igényli. Kérjük, frissítse a MySQL-t legalább 4.1.20-ra.';
$_lang['test_mysql_version_server_nf'] = 'MySQL kiszolgáló verziója nem érzékelhető!';
$_lang['test_mysql_version_server_nf_msg'] = 'A MODX nem tudta felismerni a MySQL kiszolgáló verzióját a mysql_get_server_info() meghívásával. Kérjük, hogy továbblépés előtt győződjön meg róla, hogy a MySQL kiszolgáló verziója legalább 4.1.20.';
$_lang['test_mysql_version_server_start'] = 'MySQL kiszolgáló verziójának ellenőrzése:';
$_lang['test_mysql_version_success'] = 'Rendben! Fut a [[+version]] verzió';
$_lang['test_nocompress'] = 'CSS/JS tömörítést ki kell-e kapcsolni: ';
$_lang['test_nocompress_disabled'] = 'Rendben! Letiltva.';
$_lang['test_nocompress_skip'] = 'Nincs kiválasztva, ellenőrzés kihagyva.';
$_lang['test_php_version_fail'] = 'Jelenleg a PHP [[+version]] verzióját futtatja, de a MODX Revolution számára legalább a PHP [[+required]] verziója szükséges. Kérjük, frissítsen legalább a PHP [[+required]] verziójára. A MODX javaslata, hogy frissítsen a jelenlegi stabil főverzióra [[+recommended]] a biztonság és a jövőbeli támogatás érdekében.';
$_lang['test_php_version_start'] = 'PHP verzió ellenőrzése:';
$_lang['test_php_version_success'] = 'Rendben! Fut a [[+version]] verzió';
$_lang['test_safe_mode_start'] = 'Ellenőrzése annak, hogy a safe_mode kikapcsolt:';
$_lang['test_safe_mode_fail'] = 'A MODX a safe_mode bekapcsolt állapotát érzékeli. A továbblépéshez ezt ki kell kapcsolnia a PHP beállításaiban.';
$_lang['test_sessions_start'] = 'A munkamenetek megfelelő beállításának ellenőrzése:';
$_lang['test_simplexml'] = 'SimpleXML vizsgálata:';
$_lang['test_simplexml_nf'] = 'SimpleXML nem található!';
$_lang['test_simplexml_nf_msg'] = 'A MODX nem találta a SimpleXML-t a PHP környezetében. A csomagkezelés és más működések enélkül nem érhetők el. Folytathatja a telepítést, de a MODX javasolja a SimpleXML engedélyezését a haladó beállításokhoz és működéshez.';
$_lang['test_suhosin'] = 'Suhosin hibák keresése:';
$_lang['test_suhosin_max_length'] = 'Suhosin GET legnagyobb értéke túl alacsony!';
$_lang['test_suhosin_max_length_err'] = 'Jelenleg a PHP suhosin kiterjesztését használja, és a suhosin.get.max_value_length értéke túl alacsony a MODX számára a JS állományok tömörítéséhez a kezelőben. A MODX javasolja az érték átállítását 4096-ra; addig a MODX önműködően 0-ra állítja a JS tömörítést (compress_js beállítás) a hibák elkerülésére.';
$_lang['test_table_prefix'] = '`[[+prefix]]` tábla előtag ellenőrzése: ';
$_lang['test_table_prefix_inuse'] = 'A tábla előtag már szerepel ebben az adatbázisban!';
$_lang['test_table_prefix_inuse_desc'] = 'A telepítő nem tudta használni a kiválasztott adatbázist, mert abban már vannak táblák a megadott előtaggal. Kérjük, válasszon új előtagot, és indítsa újra a telepítőt.';
$_lang['test_table_prefix_nf'] = 'Nem létezik táblaelőtag ebben az adattárban!';
$_lang['test_table_prefix_nf_desc'] = 'A telepítő nem tudta használni a kiválasztott adatbázist, mert nem tartalmaz táblákat a frissítéshez megadott előtaggal. Kérjük, válasszon egy létező előtagot, és indítsa újra a telepítőt.';
$_lang['test_zip_memory_limit'] = 'Ellenőrizzük, hogy a memóriakorlát legalább 24M a zip kiterjesztéseknek: ';
$_lang['test_zip_memory_limit_fail'] = 'A MODX a memory_limit beállításánál a javasolt 24M alatti értéket talált. A MODX próbálta beállítani a memory_limit értékét 24M-ra, de nem sikerült. Kérjük, hogy folytatás előtt állítsa a memory_limit értékét a php.ini állományban 24M vagy nagyobb értékre, hogy a zip kiterjesztések megfelelően működhessenek.';