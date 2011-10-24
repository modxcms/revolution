<?php
/**
 * Import Czech lexicon entries
 *
 * @language cs
 * @package modx
 * @subpackage lexicon
 *
 * @author modxcms.cz
 * @updated 2011-10-23
 */
// $_lang['import_allowed_extensions'] = 'Specify a comma-delimited list of file extensions to import.<br /><small><em>Leave blank to import all files according to the content types available in your site. Unknown types will be mapped as plain text.</em></small>';
$_lang['import_allowed_extensions'] = 'Vydefinujte čárkou oddělený seznam přípon souborů pro import.<br /><small><em>Políčko ponechte prázdné pro import všech souborů. Neznámé typy budou importovány jako plain text.</em></small>';

// $_lang['import_base_path'] = 'Enter the base file path containing the files to import.<br /><small><em>Leave blank to use the target context's static file path setting.</em></small>';
$_lang['import_base_path'] = 'Zadejte cestu k souborům pro import.<br /><small><em>Políčko ponechte prázdné pro nastavení cesty z daného kontextu.</em></small>';

// $_lang['import_duplicate_alias_found'] = 'Resource [[+id]] is already using the alias [[+alias]]. Please enter a unique alias.';
$_lang['import_duplicate_alias_found'] = 'Dokument [[+id]] již používá alias [[+alias]]. Zadejte ještě nepoužitý, unikátní alias.';

// $_lang['import_element'] = 'Enter the root HTML element to import:';
$_lang['import_element'] = 'Zadejte hlavní HTML element pro import:';

// $_lang['import_enter_root_element'] = 'Enter the root element to import:';
$_lang['import_enter_root_element'] = 'Zadejte hlavní element pro import:';

// $_lang['import_files_found'] = '<strong>Found %s documents for import...</strong><p/>';
$_lang['import_files_found'] = '<strong>Nalezeno  %s dokumentů pro import...</strong><p/>';

// $_lang['import_parent_document'] = 'Parent Document:';
$_lang['import_parent_document'] = 'Nadřazený dokument:';

// $_lang['import_parent_document_message'] = 'Use the document tree presented below to select the parent location to import your files into.';
$_lang['import_parent_document_message'] = 'Použijte strom dokumentů níže k výběru nadřazeného umístění, kam se mají soubory naimportovat.';

// $_lang['import_resource_class'] = 'Select a modResource class for import:<br /><small><em>Use modStaticResource to link to static files, or modDocument to copy the content to the database.</em></small>';
$_lang['import_resource_class'] = 'Vyberte typ třídy modResource pro import:<br /><small><em>Použijte modStaticResource pro nalinkování statických zdrojů nebo modDocument pro zkopírování jejich obsahu do databáze.</em></small>';

// $_lang['import_site_failed'] = '<span style="color:#990000">Failed!</span>';
$_lang['import_site_failed'] = '<span style="color:#990000">Nezdařilo se!</span>';

// $_lang['import_site_html'] = 'Import site from HTML';
$_lang['import_site_html'] = 'Importovat portál z HTML';

// $_lang['import_site_importing_document'] = 'Importing file <strong>%s</strong> ';
$_lang['import_site_importing_document'] = 'Importuji soubor <strong>%s</strong> ';

// $_lang['import_site_maxtime'] = 'Max import time:';
$_lang['import_site_maxtime'] = 'Maximální čas importu:';

// $_lang['import_site_maxtime_message'] = 'Here you can specify the number of seconds the Content Manager can take to import the site (overriding PHP settings). Enter 0 for unlimited time. Please note, setting 0 or a really high number can do weird things to your server and is not recommended.';
$_lang['import_site_maxtime_message'] = 'Na tomto místě můžete určit jakou maximální dobu může trvat import portálu (hodnota je v sekundách a přepíše se tím nastavení PHP). Zadejte 0 pro nekonečno. Pamatujte, že nastavení 0 nebo velké číslo můžete zapříčinit problémy serveru a není doporučeno.';

// $_lang['import_site_message'] = '<p>Using this tool you can import the content from a set of HTML files into the database. <em>Please note that you will need to copy your files and/or folders into the core/import folder.</em></p><p>Please fill out the form options below, optionally select a parent resource for the imported files from the document tree, and press 'Import HTML' to start the import process. The files imported will be saved into the selected location, using, where possible, the files name as the document's alias, the page title as the document's title.</p>';
$_lang['import_site_message'] = '<p>Použitím tohoto nástroje můžete naimportovat obsah portálu ze sady HTML souborů do databáze. <em>Berte na vědomí, že budete muset nakopírovat soubory a složky do složky "core/import".</em></p><p>Vyplňte možnosti níže, případně vyberte nadřazený dokument pro import souborů ze stromu dokumentů a klikněte na "Importovat HTML" . Importované soubory budou uloženy do vybraného umístění, kde budou soubory načteny dle jejich názvu a ten bude použit jako URL alias, zdroje se stejným aliasem budou přepsány.</p>';

// $_lang['import_site_resource'] = 'Import resources from static files';
$_lang['import_site_resource'] = 'Importovat dokumenty ze statických souborů';

// $_lang['import_site_resource_message'] = '<p>Using this tool you can import resources from a set of static files into the database. <em>Please note that you will need to copy your files and/or folders into the core/import folder.</em></p><p>Please fill out the form options below, optionally select a parent resource for the imported files from the document tree, and press 'Import Resources' to start the import process. The files imported will be saved into the selected location, using, where possible, the files name as the document's alias, and, if HTML, the page title as the document's title.</p>';
$_lang['import_site_resource_message'] = '<p>Použitím tohoto nástroje můžete naimportovat dokumenty ze statických souborů do databáze. <em>Berte na vědomí, že budete muset nakopírovat soubory a složky do složky "core/import".</em></p><p>Vyplňte možnosti níže, případně vyberte nadřazený dokument pro import souborů ze stromu dokumentů a klikněte na "Importovat dokumenty". Importované soubory budou uloženy do vybraného umístění, kde budou soubory načteny dle jejich názvu a ten bude použit jako URL alias, zdroje se stejným aliasem budou přepsány.</p>';

// $_lang['import_site_skip'] = '<span style="color:#990000">Skipped!</span>';
$_lang['import_site_skip'] = '<span style="color:#990000">Přeskočeno!</span>';

// $_lang['import_site_start'] = 'Start Import';
$_lang['import_site_start'] = 'Spustit import';

// $_lang['import_site_success'] = '<span style="color:#009900">Success!</span>';
$_lang['import_site_success'] = '<span style="color:#009900">V pořádku!</span>';

// $_lang['import_site_time'] = 'Import finished. Import took %s seconds to complete.';
$_lang['import_site_time'] = 'Import dokončen. Import trval %s sekund.';

// $_lang['import_use_doc_tree'] = 'Use the document tree presented below to select the parent location to import your files into.';
$_lang['import_use_doc_tree'] = 'Použijte strom dokumentů níže pro výběr nadřazeného umístění, kam mají být soubory importovány.';
