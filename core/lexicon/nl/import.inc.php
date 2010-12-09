<?php
/**
 * Import Dutch lexicon entries
 *
 * @language nl
 * @package modx
 * @subpackage lexicon
 * 
 * @author Bert Oost, <bertoost85@gmail.com>
 */
$_lang['import_allowed_extensions'] = 'Specificeer een komma-gescheiden lijst van te importeren bestandsextenties.<br /><small><em>Laat leeg om alle bestandstypen te importeren welke gelijk zijn aan de contenttypes van jouw site. Onbekende typen worden naar platte tekst omgezet.</em></small>';
$_lang['import_base_path'] = 'Vul het bestandspad in waar de bestanden gevonden kunnen worden.<br /><small><em>Laat leeg om het context\'s statische bestandspad instelling te gebruiken</em></small>';
$_lang['import_duplicate_alias_found'] = 'Resource [[+id]] gebruikt reeds het alias [[+alias]]. Vul een unieke alias in.';
$_lang['import_element'] = 'Vul het root HTML element in om te importeren:';
$_lang['import_enter_root_element'] = 'Vul het root element in om te importeren:';
$_lang['import_files_found'] = '<strong>%s documenten gevonden om te importeren...</strong><p/>';
$_lang['import_parent_document'] = 'Bovenliggend document:';
$_lang['import_parent_document_message'] = 'Selecteer uit de hieronder getoonde documentstructuur om de bovenliggende locatie om de bestanden hierin te importeren.';
$_lang['import_resource_class'] = 'Selecteer een modResource klasse om te importeren:<br /><small><em>Gebruik modStaticResource om naar statische bestanden te linken, of modDocument om de inhoud naar de database te kopiëren.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Mislukt!</span>';
$_lang['import_site_html'] = 'Importeer site van HTML';
$_lang['import_site_importing_document'] = 'Importeer bestand <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Max importeer tijd:';
$_lang['import_site_maxtime_message'] = 'Specificeer hier het aantal seconden dat de Content Manager erover mag doen om de site te importeren (overschrijft PHP instellingen). Vul 0 in voor onbeperkte tijd. Let op, indien je 0 invult kan dit nare consequenties voor de server tot gevolg hebben en het is niet aanbevolen.';
$_lang['import_site_message'] = '<p>Doormiddel van deze tool kun je content van een set aan HTML bestanden importeren in de database. <em>Let op dat je de bestanden in de core/import map moet kopiëren.</em></p><p>Vul het formulier hieronder in, optioneel selecteer een bovenliggende resource van de documentstructuur voor de geïmporteerde bestanden en klik op \'Importeer HTML\' om het importeren te starten. De geïmporteerde bestanden worden opgeslagen binnen de geselecteerde locatie, en daar waar mogelijk worden de bestandsnamen gebruikt als alias van het document en de pagina titel als de titel van het document.</p>';
$_lang['import_site_resource'] = 'Importeer resources vanuit statische bestanden';
$_lang['import_site_resource_message'] = '<p>Middels deze tool kun je statische bestanden importeren in de database. <em>Let op dat je de bestanden in de core/import map moet kopiëren.</em></p><p>Vul het onderstaande formulier in en optioneel selecteer een bovenliggende resource van de documentstructuur voor de geimporteerde bestanden en klik op \'Importeer Resources\' om het importeren te starten. De geïmporteerde bestanden worden opgeslagen binnen de geselecteerde locatie, en daar waar mogelijk worden de bestandsnamen gebruikt als alias van het document en, indien HTML, de pagina titel als de titel van het document.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">Overgeslagen!</span>';
$_lang['import_site_start'] = 'Start importeren';
$_lang['import_site_success'] = '<span style="color:#009900">Succes!</span>';
$_lang['import_site_time'] = 'Import afgerond. Import nam %s seconden in beslag.';
$_lang['import_use_doc_tree'] = 'Gebruik de documentstructuur hieronder om de bovenliggende locatie te selecteren om je bestanden in te importeren.';