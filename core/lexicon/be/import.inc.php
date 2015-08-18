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
$_lang['import_files_found'] = '<strong>Found %s documents for import...</strong><p/>';
$_lang['import_parent_document'] = 'Parent Document:';
$_lang['import_parent_document_message'] = 'Use the document tree presented below to select the parent location to import your files into.';
$_lang['import_resource_class'] = 'Select a modResource class for import:<br /><small><em>Use modStaticResource to link to static files, or modDocument to copy the content to the database.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Няўдала!</span>';
$_lang['import_site_html'] = 'Import site from HTML';
$_lang['import_site_importing_document'] = 'Importing file <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Max import time:';
$_lang['import_site_maxtime_message'] = 'Here you can specify the number of seconds the Content Manager can take to import the site (overriding PHP settings). Enter 0 for unlimited time. Please note, setting 0 or a really high number can do weird things to your server and is not recommended.';
$_lang['import_site_message'] = '<p>Using this tool you can import the content from a set of HTML files into the database. <em>Please note that you will need to copy your files and/or folders into the core/import folder.</em></p><p>Please fill out the form options below, optionally select a parent resource for the imported files from the document tree, and press \'Import HTML\' to start the import process. The files imported will be saved into the selected location, using, where possible, the file\'s name as the document\'s alias, the page title as the document\'s title.</p>';
$_lang['import_site_resource'] = 'Import resources from static files';
$_lang['import_site_resource_message'] = '<p>Using this tool you can import resources from a set of static files into the database. <em>Please note that you will need to copy your files and/or folders into the core/import folder.</em></p><p>Please fill out the form options below, optionally select a parent resource for the imported files from the document tree, and press \'Import Resources\' to start the import process. The files imported will be saved into the selected location, using, where possible, the file\'s name as the document\'s alias, and, if HTML, the page title as the document\'s title.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">Skipped!</span>';
$_lang['import_site_start'] = 'Start Import';
$_lang['import_site_success'] = '<span style="color:#009900">Паспяхова!</span>';
$_lang['import_site_time'] = 'Import finished. Import took %s seconds to complete.';
$_lang['import_use_doc_tree'] = 'Use the document tree presented below to select the parent location to import your files into.';