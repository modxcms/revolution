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
$_lang['export_site_message'] = '<p>Using this function you can export the entire site to HTML files. Please note, however, that you will lose a lot of the MODX functionality should you do so:</p><ul><li>Page reads on the exported files will not be recorded.</li><li>Interactive snippets will NOT work in exported files</li><li>Only regular documents will be exported, Weblinks will not be exported.</li><li>The export process may fail if your documents contain snippets which send redirection headers.</li><li>Depending on how you\'ve written your documents, style sheets and images, the design of your site may be broken. To fix this, you can save/move your exported files to the same directory where the main MODX index.php file is located.</li></ul><p>Please fill out the form and press \'Export\' to start the export process. The files created will be saved in the location you specify, using, where possible, the document\'s aliases as filenames. While exporting your site, it\'s best to have the MODX configuration item \'Friendly aliases\' set to \'yes\'. Depending on the size of your site, the export may take a while.</p><p><em>Any existing files will be overwritten by the new files if their names are identical!</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>%s documenten gevonden om te exporteren...</strong></p>';
$_lang['export_site_prefix'] = 'Bestand prefix:';
$_lang['export_site_start'] = 'Start exporteren';
$_lang['export_site_success'] = '<span style="color:#009900">Succes!</span>';
$_lang['export_site_suffix'] = 'Bestand suffix:';
$_lang['export_site_target_unwritable'] = 'Bestemming map is niet schrijfbaar! zorg dat de map schrijfbaar is en probeer het opnieuw.';
$_lang['export_site_time'] = 'Export gereed. Export duurde %s seconden om te voltooien.';