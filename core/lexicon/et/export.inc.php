<?php
/**
 * Export Estonian lexicon topic
 *
 * @language et
 * @package modx
 * @subpackage lexicon
 */

$_lang['export_site_cacheable'] = 'Kaasa puhverdamata failid:';
$_lang['export_site_exporting_document'] = 'Ekpordin faili <strong>%s</strong> / <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Ebaõnnestunud!</span>';
$_lang['export_site_html'] = 'Ekspordi sait HTML-i';
$_lang['export_site_maxtime'] = 'Maks. saidi ekspordi ajakulu:';
$_lang['export_site_maxtime_message'] = 'Siin saad täpsustada sekundutes kui kaua MODX-il võib aega kuluda lehe eksportimisel (kirjtuab üle PHP seaded). Sisesta 0 piiramatuks ajaks. Aga palun pane tähele, et väärtus 0 või väga suur number sekundeid võib teha imalikka sju sinu serverile ja ei ole soovitatud.';
$_lang['export_site_message'] = '<p>Kasutades seda funkstiooni, saate te eksportida kogu lehe HTML failidena. Aga selle tulemusel kaotate palju MODX funktsionaalsust:</p><ul><li>Lehtede kuvamiste arvu ei salvestata.</li><li>Snippetid ei tööta</li><li>Ainult tavalised dokumendi eksporditakse, mitte weblinks.</li><li>Eksportimise protsess võib mitte töödata, kui teie dokumentides on ümbersuunamised headerid.</li><li>Sõltuvalt kuidas oled dokumendid loonud (css, pilid) lehe disain võib minna katki. Et seda parandada, võite salvestada/liigutada eksporditud failid samase kausta kus asub MODX-i index.php.</li></ul><p>Palun täitke vorm ja vajutage "Eksport", et alustada eksportimise protsessi. Failid salvestatakse teiepoolt määratud sihtkohta kasutades, kus võimalk, dokuenid aliast failinimena. Lehe eksportimisel on parim kui MODX konfiguratiooni seade "Friendly aliases" on seatud "yes" peale. Sõltuvalt lehe suurusest, eksportile võib kuluda palju aega.</p><p><em>Kõik eksisteerivad failid sihtkohas kirjutatakse üle, kui nende nimed on samad.</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>Leidsin %s dokumenti eksportimiseks...</strong></p>';
$_lang['export_site_prefix'] = 'Faili prefiks:';
$_lang['export_site_start'] = 'Alusta eksporti';
$_lang['export_site_success'] = '<span style="color:#009900">Edukas!</span>';
$_lang['export_site_suffix'] = 'Faili järelliide (suffix):';
$_lang['export_site_target_unwritable'] = 'Sihtkaust ei ole kirjutatav . Palun kontrollege et kaust oleks kirjutatav ja proovige uuesti.';
$_lang['export_site_time'] = 'Eksport lõpetatud. Eksportimisele kulus %s sekundit.';
