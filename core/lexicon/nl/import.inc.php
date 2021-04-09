<?php
/**
 * Import English lexicon entries
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Specificeer een komma-gescheiden lijst van te importeren bestandsextenties.<br /><small><em>Laat leeg om alle bestandstypen te importeren welke gelijk zijn aan de contenttypes van jouw site. Onbekende typen worden naar platte tekst omgezet.</em></small>';
$_lang['import_base_path'] = 'Vul het bestandspad in waar de bestanden gevonden kunnen worden.<br /><small><em>Laat leeg om het context\'s statische bestandspad instelling te gebruiken</em></small>';
$_lang['import_duplicate_alias_found'] = 'Resource [[+id]] gebruikt reeds het alias [[+alias]]. Vul een unieke alias in.';
$_lang['import_element'] = 'Vul het root HTML element in om te importeren:';
$_lang['import_element_help'] = 'Voorzie JSON  van de samenhang "veld": "waarde". Als de waarde begint met $ is het een jQuery-achtige selector. Veld kan een resourceveld of de naam van de TV zijn.';
$_lang['import_enter_root_element'] = 'Vul het root element in om te importeren:';
$_lang['import_files_found'] = '<strong>%s documenten gevonden om te importeren...</strong><p/>';
$_lang['import_parent_document'] = 'Bovenliggend document:';
$_lang['import_parent_document_message'] = 'Selecteer uit de hieronder getoonde documentstructuur om de bovenliggende locatie om de bestanden hierin te importeren.';
$_lang['import_resource_class'] = 'Selecteer een modResource class om te importeren:<br /><small><em>Gebruik modStaticResource om te koppelen aan statische bestanden, of modDocument om de inhoud naar de database te kopiëren.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Mislukt!</span>';
$_lang['import_site_html'] = 'Importeer site van HTML';
$_lang['import_site_importing_document'] = 'Importeer bestand <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Max importeer tijd:';
$_lang['import_site_maxtime_message'] = 'Specificeer hier het aantal seconden dat de Content Manager erover mag doen om de site te importeren (overschrijft PHP instellingen). Vul 0 in voor onbeperkte tijd. Let op, indien je 0 invult kan dit nare consequenties voor de server tot gevolg hebben en het is niet aanbevolen.';
$_lang['import_site_message'] = '<p>Met behulp van deze tool kunt u de inhoud van een set HTML-bestanden in de database importeren. <em>Houd er rekening mee dat je bestanden en/of mappen in de core/import map moet kopiëren.</em></p><p>Vul de onderstaande formulieropties in, selecteer optioneel een bovenliggende map voor de geïmporteerde bestanden uit de documentstructuur en klik op \'Import HTML\' om het importproces te starten. De geïmporteerde bestanden zullen op de geselecteerde locatie worden opgeslagen, waar mogelijk worden gebruikt de naam van het bestand als de alias van het document, de paginatitel als de titel van het document.</p>';
$_lang['import_site_resource'] = 'Importeer resources vanuit statische bestanden';
$_lang['import_site_resource_message'] = '<p>Using this tool you can import resources from a set of static files into the database. <em>Please note that you will need to copy your files and/or folders into the core/import folder.</em></p><p>Please fill out the form options below, optionally select a parent resource for the imported files from the document tree, and press \'Import Resources\' to start the import process. The files imported will be saved into the selected location, using, where possible, the file\'s name as the document\'s alias, and, if HTML, the page title as the document\'s title.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">Overgeslagen!</span>';
$_lang['import_site_start'] = 'Start importeren';
$_lang['import_site_success'] = '<span style="color:#009900">Succes!</span>';
$_lang['import_site_time'] = 'Import afgerond. Import nam %s seconden in beslag.';
$_lang['import_use_doc_tree'] = 'Selecteer uit de hieronder getoonde documentstructuur om de bovenliggende locatie om de bestanden hierin te importeren.';