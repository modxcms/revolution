<?php
/**
 * Export English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['export_site_cacheable'] = 'Inclusief non-cacheable bestanden:';
$_lang['export_site_exporting_document'] = 'Bestand <strong>%s</strong> van <strong>%s</strong> exporteren <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Mislukt!</span>';
$_lang['export_site_html'] = 'Exporteer site naar HTML';
$_lang['export_site_maxtime'] = 'Maximale export tijd:';
$_lang['export_site_maxtime_message'] = 'Hier kun je het aantal seconden dat MODX mag gebruiken om de site te exporteren (overschrijft PHP instellingen). Voer 0 in voor ongelimiteerde tijd. Maar let op, instellen op 0 op een erg hoog getal kan rare dingen met de server doen en is niet aanbevolen.';
$_lang['export_site_message'] = '<p>Met behulp van deze functie kunt u de hele site exporteren naar HTML-bestanden. Let wel op dat je veel van de MODX functionaliteit verliest als je dit doet:</p><ul><li>Statistieken over hoevaak de pagina is bekeken van de geëxporteerde bestanden worden niet opgenomen.</li><li>Interactieve snippets werken NIET in geëxporteerde bestanden</li><li>Alleen reguliere documenten worden geëxporteerd, Weblinks worden niet geëxporteerd.</li><li>Het exportproces kan mislukken als uw documenten snippets bevatten die de doorsturing naar andere pagina\'s maken.</li><li>Afhankelijk van hoe u uw documenten, stijlbladen en afbeeldingen hebt geschreven, kan het ontwerp van uw site misvormd zijn. Om dit te verhelpen, kun je de geëxporteerde bestanden opslaan/verplaatsen naar dezelfde map waar het belangrijkste MODX index.php bestand zich bevindt.</li></ul><p>Vul het formulier in en druk op \'Exporteren\' om het exportproces te starten. De aangemaakte bestanden worden opgeslagen op de locatie die je opgeeft en gebruikt zo mogelijk de aliassen van het document als filenames. Tijdens het exporteren van jouw site, is het het beste om het MODX configuratie-item \'Vriendelijke aliassen\' te hebben ingesteld op \'ja\'. Afhankelijk van de grootte van je site kan de export een tijdje duren.</p><p><em>Alle bestaande bestanden worden overschreven door de nieuwe bestanden als hun namen identiek zijn!</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>%s documenten gevonden om te exporteren...</strong></p>';
$_lang['export_site_prefix'] = 'Bestand prefix:';
$_lang['export_site_start'] = 'Start exporteren';
$_lang['export_site_success'] = '<span style="color:#009900">Succes!</span>';
$_lang['export_site_suffix'] = 'Bestand suffix:';
$_lang['export_site_target_unwritable'] = 'Bestemming map is niet schrijfbaar! zorg dat de map schrijfbaar is en probeer het opnieuw.';
$_lang['export_site_time'] = 'Export gereed. Export duurde %s seconden om te voltooien.';