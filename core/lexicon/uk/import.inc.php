<?php
/**
 * Import English lexicon entries
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Вкажіть через кому список розширень файлів, призначених для імпорту.<br /><small><em>Залиште поле порожнім, щоб імпортувати усі файли у відповідності з типами вмісту, доступними на Вашому сайті. Невідомі типи будуть відмічені як звичайний текст.</em></small>';
$_lang['import_base_path'] = 'Введіть шлях до каталогу з файлами, призначеними для імпорту.<br /><small><em>Залиште поле порожнім, щоб використовувати шлях до статичних файлів контексту.</em></small>';
$_lang['import_duplicate_alias_found'] = 'Resource [[+id]] is already using the alias [[+alias]]. Please enter a unique alias.';
$_lang['import_element'] = 'Введіть кореневий HTML-елемент для імпорту:';
$_lang['import_element_help'] = 'Вкажіть JSON у вигляді асоціацій "поле":"значення". Якщо значення починається з $, воно буде оброблене як jQuery-селектор. Поле може бути полем ресурсу або іменем змінної шаблону.';
$_lang['import_enter_root_element'] = 'Введіть кореневий елемент для імпорту:';
$_lang['import_files_found'] = '<strong>Знайдено %s документів для імпорту...</strong><p/>';
$_lang['import_parent_document'] = 'Батьківський документ:';
$_lang['import_parent_document_message'] = 'Використовуйте дерево документів, що розташоване нижче, для вибору батьківського каталогу для імпортування до нього Ваших файлів.';
$_lang['import_resource_class'] = 'Виберіть клас modResource для імпорту:<br /><small><em>Використовуйте modStaticResource щоб зв\'язати зі статичними файлами або modDocument для копіювання вмісту до бази даних.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Невдача!</span>';
$_lang['import_site_html'] = 'Імпортування сайту з HTML';
$_lang['import_site_importing_document'] = 'Імпортування файлу <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Максимальний час імпорту:';
$_lang['import_site_maxtime_message'] = 'Here you can specify the number of seconds the Content Manager can take to import the site (overriding PHP settings). Enter 0 for unlimited time. Please note, setting 0 or a really high number can do weird things to your server and is not recommended.';
$_lang['import_site_message'] = '<p>Using this tool you can import the content from a set of HTML files into the database. <em>Please note that you will need to copy your files and/or folders into the core/import folder.</em></p><p>Please fill out the form options below, optionally select a parent resource for the imported files from the document tree, and press \'Import HTML\' to start the import process. The files imported will be saved into the selected location, using, where possible, the file\'s name as the document\'s alias, the page title as the document\'s title.</p>';
$_lang['import_site_resource'] = 'Імпортування ресурсів зі статичних файлів';
$_lang['import_site_resource_message'] = '<p>Using this tool you can import resources from a set of static files into the database. <em>Please note that you will need to copy your files and/or folders into the core/import folder.</em></p><p>Please fill out the form options below, optionally select a parent resource for the imported files from the document tree, and press \'Import Resources\' to start the import process. The files imported will be saved into the selected location, using, where possible, the file\'s name as the document\'s alias, and, if HTML, the page title as the document\'s title.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">Skipped!</span>';
$_lang['import_site_start'] = 'Start Import';
$_lang['import_site_success'] = '<span style="color:#009900">Успішно!</span>';
$_lang['import_site_time'] = 'Імпортування завершено. Імпортування файлів зайняло %s секунд.';
$_lang['import_use_doc_tree'] = 'Використовуйте дерево документів, що розташоване нижче, для вибору батьківського каталогу для імпортування до нього Ваших файлів.';