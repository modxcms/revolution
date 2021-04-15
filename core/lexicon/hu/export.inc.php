<?php
/**
 * Export English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['export_site_cacheable'] = 'A nem gyorsítótárazható állományokat is tartalmazza:';
$_lang['export_site_exporting_document'] = 'A <strong>%s</strong> állomány kivitele ebben: <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Sikertelen!</span>';
$_lang['export_site_html'] = 'Oldal exportálása HTML-ként';
$_lang['export_site_maxtime'] = 'Exportálás időkorlátja:';
$_lang['export_site_maxtime_message'] = 'Itt másodpercben adhatja meg, hogy a MODX mennyi ideig végezheti a teljes oldal exportálását (a PHP beállítások felülírásábal). Nincs időkorlát, ha 0-t ad meg. Figyelmeztetés, 0 vagy nagyon magas érték megadása furcsa dolgokat tehet a szerverrel, ezért nem ajánlott.';
$_lang['export_site_message'] = '<p>Ezzel a működéssel a teljes weboldalt HTML állományokba mentheti. Azonban ne feledje, hogy így elveszíti a MODX működésének nagy részét:</p><ul><li>Egy oldal megtekintései a kimentett állományokon nem lesznek rögzítve.</li><li>A párbeszédkész kódrészletek NEM működnek a kimentett állományokban</li><li>Csak a hagyományos dokumentumok lesznek kimentve, a webes hivatkozások nem.</li><li>A kimentés sikertelen lehet, ha a dokumentumok olyan kódrészleteket tartalmaznak, amelyek átirányítási fejléceket tartalmaznak.</li><li>A kialakításuktól függően az egyes dokumentumokban levő stíluslapok és képek, az oldal megjelenése széteshet. Ennek elkerülésére a kimentett állományait áthelyezheti a MODX index.php állományával azonos mappába.</li></ul><p>Kérjük, töltse ki az űrlapot, és nyomja meg a \'Kimentés\' gombot a folyamat elindításához. A létrehozott állományok a megadott mappába lesznek mentve, ahol lehet, a dokumentum hivatkozási nevét használva állománynévként. Az oldalának kimentésekor legjobb, ha a \'Barátságos hivatkozási nevek\' beállítást \'Igen\' értékre állítja. Az oldalának méretétől függően a kiemtés eltarthat egy darabig.</p><p><em>Az összes létező állomány felül lesz írva a vele azonos nevű új állománnyal!</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>%s dokumentum található kivitelre...</strong></p>';
$_lang['export_site_prefix'] = 'Fájl előtag:';
$_lang['export_site_start'] = 'Export indítása';
$_lang['export_site_success'] = '<span style="color:#009900">Siker!</span>';
$_lang['export_site_suffix'] = 'Fájl utótag:';
$_lang['export_site_target_unwritable'] = 'A célmappa nem írható. Kérjük, biztosítsa az írhatóságát, és próbálja újra.';
$_lang['export_site_time'] = 'Exportálás kész. A művelet %s másodpercig tartott.';