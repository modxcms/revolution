<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Kérjük, forduljon a rendszergazdához, és figyelmeztesse őket erre az üzenetre!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'allow_tags_in_post környezeti beállítás engedélyezve van a `mgr` részen kívül';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'Az allow_tags_in_post környezeti beállítás engedélyezett a telepítőben a mgr környezeten kívül. A MODX ennek tiltását javasolja, hacsak nem kifejezetten engedni akarja a felhasználókat MODX címkék, számok, vagy HTML script kódok beküldésére POST eljáráson keresztül az oldalán található űrlapokba. Javasolt általánosan tiltani a mgr környezeten kívül.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post rendszerbeállítás engedélyezve van';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'Az allow_tags_in_post rendszerbeállítás engedélyezett a telepítésében. A MODX ennek tiltását javasolja, hacsak nem kifejezetten engedni akarja a felhasználókat MODX címkék, számok, vagy HTML script kódok beküldésére POST eljáráson keresztül az oldalán található űrlapokba. Javasolt a környezeti beállításokon keresztül engedélyezni az egyes környezetekre.';
$_lang['configcheck_cache'] = 'Gyorsítótár mappa nem írható';
$_lang['configcheck_cache_msg'] = 'A MODX nem tud írni a gyorsítótár mappába. A MODX ettől még rendben működik, de nem gyorsítótáraz. A gyorsítótárazáshoz tegye írhatóvá a /_cache/ mappát.';
$_lang['configcheck_configinc'] = 'A beállítási állomány még írható!';
$_lang['configcheck_configinc_msg'] = 'A weboldala sérülékeny, a rosszindulatú támadások sok kárt okozhatnak benne. Kérjük, állítsa csak olvashatóra a beállítási állományt! Ha nem rendszergazda, értesítse az oldal rendszergazdáját erről a figyelmeztetésről! Az üzenet helye [[+path]]';
$_lang['configcheck_default_msg'] = 'Ismeretlen figyelmeztetés találtunk, ami furcsa.';
$_lang['configcheck_errorpage_unavailable'] = 'A weboldal hibaoldala nem érhető el.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Ez azt jelenti, hogy a hibaoldalt nem érik el az átlagos látogatók, vagy az nem létezik. Ez önmagukba visszatérő lépésekhez és sok hibaüzenethez vezethet. Ellenőrizze, hogy ne legyenek webes felhasználói csoportok rendelve az oldalhoz.';
$_lang['configcheck_errorpage_unpublished'] = 'A weboldal hibaoldala nincs közzétéve vagy nem létezik.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Ez azt jelenti, hogy a hibaoldalt nem érik el az átlagos látogatók. Tegye közzé az oldalt, vagy ellenőrizze, hogy létező dokumentumhoz van hozzárendelve a Rendszer &gt; Rendszerbeállítások menüben.';
$_lang['configcheck_htaccess'] = 'Az alapkönyvtár elérhető a weben';
$_lang['configcheck_htaccess_msg'] = 'MODX detected that your core folder is (partially) accessible to the public.
<strong>This is not recommended and a security risk.</strong>
If your MODX installation is running on a Apache webserver
you should at least set up the .htaccess file inside the core folder <em>[[+fileLocation]]</em>.
This can be easily done by renaming the existing ht.access example file there to .htaccess.
<p>There are other methods and webservers you may use, please read the <a href="https://docs.modx.com/current/en/getting-started/maintenance/securing-modx">Hardening MODX Guide</a>
for further information about securing your site.</p>
If you setup everything correctly, browsing e.g. to the <a href="[[+checkUrl]]" target="_blank">Changelog</a>
should give you a 403 (permission denied) or better a 404 (not found). If you can see the changelog
there in the browser, something is still wrong and you need to reconfigure or call an expert to solve this.';
$_lang['configcheck_images'] = 'Képek mappa nem írható';
$_lang['configcheck_images_msg'] = 'A képek mappa nem írható, vagy nem létezik. Emiatt a képkezelő működés nem használható a szerkesztőben!';
$_lang['configcheck_installer'] = 'A telepítő még nincs törölve';
$_lang['configcheck_installer_msg'] = 'A setup/ mappában megtalálható a MODX telepítője. Képzelje csak el, hogy mi történhetne, ha egy rossz szándékú személy megtalálja ezt a mappát és futtatja a telepítőt! Valószínűleg nem jutna messzire, mert meg kell adnia az adatbázis hozzáférési adatait, de legjobb eltávolítani ezt a mappát a kiszolgálóról. Az elérhetősége: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Helytelen számú bejegyzés a nyelvi állományban';
$_lang['configcheck_lang_difference_msg'] = 'A jelenleg kiválasztott nyelvben eltérő számú bejegyzés szerepel, mint az alapértelmezettben. Habár ez nem feltétlen okoz hibát, jelentheti azt, hogy frissíteni kell a nyelvi állományt.';
$_lang['configcheck_notok'] = 'Egy vagy több beállítás nincs rendben: ';
$_lang['configcheck_ok'] = 'Az ellenőrzés nem talált hibát.';
$_lang['configcheck_phpversion'] = 'A PHP verziója elavult';
$_lang['configcheck_phpversion_msg'] = 'A PHP verzióját [[+phpversion]] már nem támogatják a PHP fejlesztői, ezért nem érhetők el biztonsági frissítések. Valószínű az is, hogy a MODX vagy hozzáadott csomagok most, vagy a jövőben nem támogatják ezt a verziót. Kérjük, az oldala biztonsága érdekében frissítsen legalább PHP [[+phprequired]] verzióra amint lehet.';
$_lang['configcheck_register_globals'] = 'A register_globals értéke ON a php.ini állományban';
$_lang['configcheck_register_globals_msg'] = 'Ezen beállítás miatt a weboldala sokkal kevésbé ellenálló a Cross Site Scripting (XSS) támadásokkal szemben. Keresse meg tárhelyszolgáltatóját, hogy mit tehetne a beállítás kikapcsolása érdekében.';
$_lang['configcheck_title'] = 'Konfiguráció ellenőrzése';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'A weboldal jogosultság hiányát jelző oldala nincs közzétéve vagy nem létezik.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Ez azt jelenti, hogy a jogosultság hiányát jelző oldalt nem érik el az átlagos látogatók, vagy az nem létezik. Ez önmagukba visszatérő lépésekhez és sok hibaüzenethez vezethet. Ellenőrizze, hogy ne legyenek webes felhasználói csoportok rendelve az oldalhoz.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'A weboldalhoz beállított jogosultság hiányát jelző oldal nincs közzétéve.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Ez azt jelenti, hogy a jogosultság hiányát jelző oldalt nem érik el az átlagos látogatók. Tegye közzé az oldalt, vagy ellenőrizze, hogy létező dokumentumhoz van hozzárendelve a Rendszer &gt; Rendszerbeállítások menüben.';
$_lang['configcheck_warning'] = 'Beállítási hibajelzés:';
$_lang['configcheck_what'] = 'Ez mit jelent?';
