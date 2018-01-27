<?php
/**
 * Export English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['export_site_cacheable'] = 'A nem gyorsítótárazható állományokat is tartalmazza:';
$_lang['export_site_exporting_document'] = 'Exporting file <strong>%s</strong> of <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Failed!</span>';
$_lang['export_site_html'] = 'Oldal exportálása HTML-ként';
$_lang['export_site_maxtime'] = 'Exportálás időkorlátja:';
$_lang['export_site_maxtime_message'] = 'Itt másodpercben adhatja meg, hogy a MODX mennyi ideig végezheti a teljes oldal exportálását (a PHP beállítások felülírásábal). Nincs időkorlát, ha 0-t ad meg. Figyelmeztetés, 0 vagy nagyon magas érték megadása furcsa dolgokat tehet a szerverrel, ezért nem ajánlott.';
$_lang['export_site_message'] = '<p>Using this function you can export the entire site to HTML files. Please note, however, that you will lose a lot of the MODX functionality should you do so:</p><ul><li>Page reads on the exported files will not be recorded.</li><li>Interactive snippets will NOT work in exported files</li><li>Only regular documents will be exported, Weblinks will not be exported.</li><li>The export process may fail if your documents contain snippets which send redirection headers.</li><li>Depending on how you\'ve written your documents, style sheets and images, the design of your site may be broken. To fix this, you can save/move your exported files to the same directory where the main MODX index.php file is located.</li></ul><p>Please fill out the form and press \'Export\' to start the export process. The files created will be saved in the location you specify, using, where possible, the document\'s aliases as filenames. While exporting your site, it\'s best to have the MODX configuration item \'Friendly aliases\' set to \'yes\'. Depending on the size of your site, the export may take a while.</p><p><em>Any existing files will be overwritten by the new files if their names are identical!</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>Found %s documents to export...</strong></p>';
$_lang['export_site_prefix'] = 'Fájl előtag:';
$_lang['export_site_start'] = 'Export indítása';
$_lang['export_site_success'] = '<span style="color:#009900">Success!</span>';
$_lang['export_site_suffix'] = 'Fájl utótag:';
$_lang['export_site_target_unwritable'] = 'Target directory isn\'t writable. Please ensure the directory is writable and try again.';
$_lang['export_site_time'] = 'Exportálás kész. A művelet %s másodpercig tartott.';