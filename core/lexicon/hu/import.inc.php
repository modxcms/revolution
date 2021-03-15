<?php
/**
 * Import English lexicon entries
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Adja meg a betöltendő állományok kiterjesztéseinek vesszővel elválasztott felsorolását.<br /><small><em>Hagyja üresen az összes állomány betöltéséhez a weboldalán elérhető tartalomtípusok alapján. Az ismeretlen típusok egyszerű szövegként lesznek betöltve.</em></small>';
$_lang['import_base_path'] = 'Adja meg a betöltendő állományok alapmappáját.<br /><small><em>Hagyja üresen, ha a megadott környezet állandó állományainak mappáját akarja használni.</em></small>';
$_lang['import_duplicate_alias_found'] = '[[+id]] erőforrás már használja a [[+alias]] hivatkozási nevet. Kérjük, adjon meg egy egyedi értéket.';
$_lang['import_element'] = 'Adja meg a betöltendő gyökérszintű HTML elemet:';
$_lang['import_element_help'] = 'Adjon meg JSON-t "mező":"érték" párokkal. Ha az érték $ jellel kezdődik, az jQuery-szerű elemválasztó. A mező lehet erőforrás vagy sablonváltozó neve.';
$_lang['import_enter_root_element'] = 'Adja meg a betöltendő gyökérszintű elemet:';
$_lang['import_files_found'] = '<strong>%s betöltendő dokumentum található...</strong><p/>';
$_lang['import_parent_document'] = 'Szülő dokumentum:';
$_lang['import_parent_document_message'] = 'Használja az alábbi dokumentumfát a betöltés alapmappájának megadására.';
$_lang['import_resource_class'] = 'Válasszon modResource osztályt a betöltéshez:<br /><small><em>Használjon modStaticResource osztályt az állandó állományokra hivatkozáshoz, vagy modDocument osztályt a tartalom adattárba töltéséhez.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Sikertelen!</span>';
$_lang['import_site_html'] = 'Oldal betöltése HTML-ből';
$_lang['import_site_importing_document'] = '<strong>%s</strong> állomány betöltése ';
$_lang['import_site_maxtime'] = 'Betöltés időkorlátja:';
$_lang['import_site_maxtime_message'] = 'Itt másodpercben adhatja meg, hogy a tartalomkezelő meddig végezheti az oldal betöltését (a PHP beállítások felülírásával). Nincs időkorlát, ha 0-t ad meg. Figyelem, 0 vagy nagyon magas érték megadása furcsa dolgokat tehet a szerverrel, ezért nem ajánlott.';
$_lang['import_site_message'] = '<p>Ezzel az eszközzel az adattárba tudja tölteni a tartalmat HTML állományokból. <em>Ne feledje, hogy az állományokat és/vagy a mappákat a core/import mappába kell töltenie.</em></p><p>Töltse ki az alábbi űrlapot, szükség esetén válassza ki a szülő erőforrást a dokumentumfából, majd nyomja meg a \'HTML betöltése\' gombra a betöltési folyamat elindításához. A betöltött állományok a kiválasztott helyre lesznek mentve, ahol lehet, az állomány neve lesz a dokumentum hivatkozási neve, az oldal címe pedig a dokumentum címe.</p>';
$_lang['import_site_resource'] = 'Erőforrások betöltése állandó állományokból';
$_lang['import_site_resource_message'] = '<p>Ezzel az eszközzel az adattárba tudja tölteni az erőforrásokat állandó állományokból. <em>Ne feledje, hogy az állományokat és/vagy a mappákat a core/import mappába kell töltenie.</em></p><p>Töltse ki az alábbi űrlapot, szükség esetén válassza ki a szülő erőforrást a dokumentumfából, majd nyomja meg a \'Erőforrások betöltése\' gombra a betöltési folyamat elindításához. A betöltött állományok a kiválasztott helyre lesznek mentve, ahol lehet, az állomány neve lesz a dokumentum hivatkozási neve, és ha HTML, az oldal címe a dokumentum címe.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">Kihagyva!</span>';
$_lang['import_site_start'] = 'Betöltés indítása';
$_lang['import_site_success'] = '<span style="color:#009900">Siker!</span>';
$_lang['import_site_time'] = 'Betöltés kész. A művelet %s másodpercig tartott.';
$_lang['import_use_doc_tree'] = 'Használja az alábbi dokumentumfát a betöltés alapmappájának megadására.';