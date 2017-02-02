<?php
/**
 * Export English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['export_site_cacheable'] = 'Dołącz niebuforowane pliki:';
$_lang['export_site_exporting_document'] = 'Eksportuję plik <strong>%s</strong> z <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Nieudane!</span>';
$_lang['export_site_html'] = 'Eksportuj serwis do pliku HTML';
$_lang['export_site_maxtime'] = 'Maksymalny czas eksportu:';
$_lang['export_site_maxtime_message'] = 'Tu możesz określić limit czasu w sekundach, w którym MODX ma wyeksportować serwis (nadpisując ustawienia PHP). Wpisz 0 dla nieograniczonego czasu. UWAGA, 0 lub zbyt duża liczba może spowodować przeciążenie twojego serwera i nie jest rekomendowane.';
$_lang['export_site_message'] = '<p>Using this function you can export the entire site to HTML files. Please note, however, that you will lose a lot of the MODX functionality should you do so:</p><ul><li>Page reads on the exported files will not be recorded.</li><li>Interactive snippets will NOT work in exported files</li><li>Only regular documents will be exported, Weblinks will not be exported.</li><li>The export process may fail if your documents contain snippets which send redirection headers.</li><li>Depending on how you\'ve written your documents, style sheets and images, the design of your site may be broken. To fix this, you can save/move your exported files to the same directory where the main MODX index.php file is located.</li></ul><p>Please fill out the form and press \'Export\' to start the export process. The files created will be saved in the location you specify, using, where possible, the document\'s aliases as filenames. While exporting your site, it\'s best to have the MODX configuration item \'Friendly aliases\' set to \'yes\'. Depending on the size of your site, the export may take a while.</p><p><em>Any existing files will be overwritten by the new files if their names are identical!</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>Znaleziono %s dokumentów do wyeksportowania...</strong></p>';
$_lang['export_site_prefix'] = 'Prefiks pliku:';
$_lang['export_site_start'] = 'Zacznij eksportować';
$_lang['export_site_success'] = '<span style="color:#009900">Sukces!</span>';
$_lang['export_site_suffix'] = 'Sufiks pliku:';
$_lang['export_site_target_unwritable'] = 'Docelowy katalog nie jest zapisywalny. Proszę upewnij się, że katalog jest zapisywalny i powtórz.';
$_lang['export_site_time'] = 'Eksport zakończony, Eksport trwał %s sekund.';