<?php
/**
 * Import Estonian lexicon entries
 *
 * @language et
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Määra koma-eraldatud nimekiri faili laienditest, mida importida.<br /><small><em>
Jäta tühjaks, et importida kõik failid vastavalt sisu tüüpidele (content types) mis on teie saidil määratud. Teadmata tüübid latakse süsteemi kui tavaline tekst (plain text)</em></small>';
$_lang['import_base_path'] = 'Sisesta süsteemi kataloog, mis sisaldab faile, mis tuleb importida.<br /><small><em>Tühjaksjätmise korral, kasutatakse contexti "static file path" seadet.</em></small>';
$_lang['import_duplicate_alias_found'] = 'Ressurss [[+id]] juba kasutab aliast [[+alias]]. Palun siseta unikaalne alias.';
$_lang['import_element'] = 'Siesta HTML element, mille vahelt importida sisu:';
$_lang['import_enter_root_element'] = 'Sisesta juur element, mida importida:';
$_lang['import_files_found'] = '<strong>Leidsin %s dokumenti importimiseks...</strong><p/>';
$_lang['import_parent_document'] = 'Parent Dokument:';
$_lang['import_parent_document_message'] = 'Kasuta seda dokumendi puud allpool, et valida sihtkoht (parent), kuhu failid importida.';
$_lang['import_resource_class'] = 'Vali modResource class importimiseks:<br /><small><em>Kasuta modStaticResource, et linkida staatilistele ressurssidele või modDocument, et kopeerida sisu andmebaasi.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Ebaõnnestus!</span>';
$_lang['import_site_html'] = 'Impordi sait HTML-ist';
$_lang['import_site_importing_document'] = 'Importin faili <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Maks. importimisele kuluv aeg:';
$_lang['import_site_maxtime_message'] = 'Siit saab määrata, kui kaua Content Manager võib kuluda sekundeid importimisel (kirjutab üle PHP seade). Siesta 0 piiramatuks ajaks. Aga palun pane tähele, et väärtus 0 või väga suur number sekundeid võib teha imalikka sju sinu serverile ja ei ole soovitatud.';
$_lang['import_site_message'] = '<p>Kasutades seda tööriista, saate importida sisu HTML failidest andmebaasi. <em>Peate kopeerima oma failid core/import kausta.</em></p><p>Palun täida vormi valikud allpool ja soovikorral vali parent ressurss imporditavatele failidele dokumendi puust ja vajuta "Import HTML", et alustada importimise protsessi. Failid salvestatakse valitud asukohta, kasutades, kus võimalk failinime dokumendi aliasena ja lehe tiitlit dokumendi tiitlina</p>';
$_lang['import_site_resource'] = 'Importi ressurssid staatilistest failidest';
$_lang['import_site_resource_message'] = '<p>Kasutades seda tööriista, saate importida ressursse staatilistest failidest andmebaasi. <em>Peate kopeerima oma failid core/import kausta.</em></p><p>Palun täida vormi valikud allpool ja soovikorral vali parent ressurss imporditavatele failidele dokumendi puust ja vajuta "Import HTML", et alustada importimise protsessi. Failid salvestatakse valitud asukohta, kasutades, kus võimalk failinime dokumendi aliasena ja lehe tiitlit dokumendi tiitlina</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">Vahelejäetud!</span>';
$_lang['import_site_start'] = 'Alusta Importi';
$_lang['import_site_success'] = '<span style="color:#009900">Edukas!</span>';
$_lang['import_site_time'] = 'Import lõpetatud. Kulus %s sekundit.';
$_lang['import_use_doc_tree'] = 'Kasuta dokumendi puud allpool, et valida parent sihtkoht kuhu failid importida.';
