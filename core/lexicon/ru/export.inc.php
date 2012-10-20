<?php
/**
 * Export Russian lexicon topic
 *
 * @language ru
 * @package modx
 * @subpackage lexicon
 */
$_lang['export_site_cacheable'] = 'Включать некэшируемые файлы:';
$_lang['export_site_exporting_document'] = 'Экспортируется файл <strong>%s</strong> из <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Неудача!</span>';
$_lang['export_site_html'] = 'Экспортировать сайт в HTML';
$_lang['export_site_maxtime'] = 'Максимальное время экспорта:';
$_lang['export_site_maxtime_message'] = 'Здесь вы можете указать количество секунд MODX может экспортировать сайте (будут переопределены настройки PHP). Введите 0 для неограниченного времени. Пожалуйста, обратите внимание, что устанавка 0 или очень большого числа может нарушить работу вашего сервера и не рекомендуется.';
$_lang['export_site_message'] = '<p>Using this function you can export the entire site to HTML files. Please note, however, that you will lose a lot of the MODX functionality should you do so:</p><ul><li>Page reads on the exported files will not be recorded.</li><li>Interactive snippets will NOT work in exported files</li><li>Only regular documents will be exported, Weblinks will not be exported.</li><li>The export process may fail if your documents contain snippets which send redirection headers.</li><li>Depending on how you\'ve written your documents, style sheets and images, the design of your site may be broken. To fix this, you can save/move your exported files to the same directory where the main MODX index.php file is located.</li></ul><p>Please fill out the form and press \'Export\' to start the export process. The files created will be saved in the location you specify, using, where possible, the document\'s aliases as filenames. While exporting your site, it\'s best to have the MODX configuration item \'Friendly aliases\' set to \'yes\'. Depending on the size of your site, the export may take a while.</p><p><em>Any existing files will be overwritten by the new files if their names are identical!</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>Найдено %s документов для экспорта...</strong></p>';
$_lang['export_site_prefix'] = 'Префикс файла:';
$_lang['export_site_start'] = 'Начать экспорт';
$_lang['export_site_success'] = '<span style="color:#009900">Успех!</span>';
$_lang['export_site_suffix'] = 'Суффикс файла:';
$_lang['export_site_target_unwritable'] = 'Невозможно произвести запись в папку. Убедитесь в том, что папка доступна для записи, и повторите попытку.';
$_lang['export_site_time'] = 'Экспорт закончен. Экспорт занял %s секунд.';