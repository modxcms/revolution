<?php
/**
 * Export Dutch lexicon topic
 *
 * @language nl
 * @package modx
 * @subpackage lexicon
 * 
 * @author Bert Oost at OostDesign.nl <bert@oostdesign.nl>
 */
$_lang['export_site_cacheable'] = 'Inclusief non-cacheable bestanden:';
$_lang['export_site_exporting_document'] = 'Bestand <strong>%s</strong> van <strong>%s</strong> exporteren <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Mislukt!</span>';
$_lang['export_site_html'] = 'Exporteer site naar HTML';
$_lang['export_site_maxtime'] = 'Maximale export tijd:';
$_lang['export_site_maxtime_message'] = 'Hier kun je het aantal seconden dat MODX mag gebruiken om de site te exporteren (overschrijft PHP instellingen). Voer 0 in voor ongelimiteerde tijd. Maar let op, instellen op 0 op een erg hoog getal kan rare dingen met de server doen en is niet aanbevolen.';
$_lang['export_site_message'] = '<p>Middels deze functie kun je de hele site naar HTML bestanden exporteren. Let op, echter, zul je vele MODX functionaliteiten verliezen, zoals:</p><ul><li>Geëxporteerde pagina\'s worden niet bijgehouden.</li><li>Interactieve snippets zullen niet werken in deze bestanden.</li><li>Enkel normale documenten worden geëxporteerd, Weblinks worden niet geëxporteerd.</li><li>De export kan falen als jouw documenten snippets bevatten welke redirect headers versturen.</li><li>Afhankelijk van hoe je jouw documenten hebt geschreven, stylesheets en afbeeldingen in jouw site kunnen niet werken. Om dit op te lossen kun je de geëxporteerde bestanden opslaan of verplaatsen naar de map waar de MODX index.php opgeslagen is.</li></ul><p>Vul het formulier in en druk op \'Exporteren\' om het proces te starten. De bestanden worden opgeslagen op de door jou opgegeven locatie, met indien mogelijk de document aliassen als bestandsnaam. Tijdens het exporteren is het het beste om \'Vriendelijke aliassen\' op \'Ja\' in te stellen. Afhankelijk van de grootte van de site kan het exporteren even duren.</p><p><em>Alle bestaande bestanden worden overschreven met nieuwe bestanden als de namen gelijk zijn.</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>%s documenten gevonden om te exporteren...</strong></p>';
$_lang['export_site_prefix'] = 'Bestand prefix:';
$_lang['export_site_start'] = 'Start exporteren';
$_lang['export_site_success'] = '<span style="color:#009900">Succes!</span>';
$_lang['export_site_suffix'] = 'Bestand suffix:';
$_lang['export_site_target_unwritable'] = 'Bestemming map is niet schrijfbaar! zorg dat de map schrijfbaar is en probeer het opnieuw.';
$_lang['export_site_time'] = 'Export gereed. Export duurde %s seconden om te voltooien.';