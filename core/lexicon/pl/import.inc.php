<?php
/**
 * Import English lexicon entries
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Specify a comma-delimited list of file extensions to import.<br /><small><em>Leave blank to import all files according to the content types available in your site. Unknown types will be mapped as plain text.</em></small>';
$_lang['import_base_path'] = 'Enter the base file path containing the files to import.<br /><small><em>Leave blank to use the target context\'s static file path setting.</em></small>';
$_lang['import_duplicate_alias_found'] = 'Resource [[+id]] is already using the alias [[+alias]]. Please enter a unique alias.';
$_lang['import_element'] = 'Enter the root HTML element to import:';
$_lang['import_element_help'] = 'Provide JSON with associations "field":"value". If value starts with $ it is jQuery-like selector. Field can be a Resource field or TV name.';
$_lang['import_enter_root_element'] = 'Enter the root element to import:';
$_lang['import_files_found'] = '<p><strong>Znaleziono %s dokumentów do importu...</strong></p>';
$_lang['import_parent_document'] = 'Dokument nadrzędny:';
$_lang['import_parent_document_message'] = 'Use the document tree presented below to select the parent location to import your files into.';
$_lang['import_resource_class'] = 'Select a modResource class for import:<br /><small><em>Use modStaticResource to link to static files, or modDocument to copy the content to the database.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Nieudane!</span>';
$_lang['import_site_html'] = 'Import site from HTML';
$_lang['import_site_importing_document'] = 'Importowanie pliku <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Maksymalny czas importu:';
$_lang['import_site_maxtime_message'] = 'Tu możesz określić limit czasu (w sekundach), w którym MODX ma wyeksportować serwis (nadpisując ustawienia PHP). Wpisz 0 dla nieograniczonego czasu. UWAGA, 0 lub zbyt duża liczba może spowodować przeciążenie twojego serwera.';
$_lang['import_site_message'] = '<p>Using this tool you can import the content from a set of HTML files into the database. <em>Please note that you will need to copy your files and/or folders into the core/import folder.</em></p><p>Please fill out the form options below, optionally select a parent resource for the imported files from the document tree, and press \'Import HTML\' to start the import process. The files imported will be saved into the selected location, using, where possible, the file\'s name as the document\'s alias, the page title as the document\'s title.</p>';
$_lang['import_site_resource'] = 'Importowanie documentów z plików statycznych';
$_lang['import_site_resource_message'] = '<p>Using this tool you can import resources from a set of static files into the database. <em>Please note that you will need to copy your files and/or folders into the core/import folder.</em></p><p>Please fill out the form options below, optionally select a parent resource for the imported files from the document tree, and press \'Import Resources\' to start the import process. The files imported will be saved into the selected location, using, where possible, the file\'s name as the document\'s alias, and, if HTML, the page title as the document\'s title.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000"> Pominięte!</span>';
$_lang['import_site_start'] = 'Rozpocznij importowanie';
$_lang['import_site_success'] = '<span style="color:#009900">Sukces!</span>';
$_lang['import_site_time'] = 'Import zakończony, Czas operacji %s sekund.';
$_lang['import_use_doc_tree'] = 'Use the document tree presented below to select the parent location to import your files into.';