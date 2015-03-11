<?php
/**
 * Import English lexicon entries
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Angiv en kommasepareret liste over filtypenavne der skal importeres.<br/><small><em>Lad feltet være tommt for at importere alle filer i henhold til de indholdstyper, der er tilgængelige i dit websted. Ukendt typer tilknyttes som klartekst.</em></small>';
$_lang['import_base_path'] = 'Angiv grund-stien der indeholder de filer der skal importeres.<br/><small><em>Lad feltet være tomt for at bruge kontekstens sti for statiske filer.</em></small>';
$_lang['import_duplicate_alias_found'] = 'Ressource [[+id]] bruger allerede alias\'et [[+alias]]. Angiv venligst et unikt alias.';
$_lang['import_element'] = 'Indtast HTML-rodelementet der skal importeres:';
$_lang['import_element_help'] = 'Provide JSON with associations "field":"value". If value starts with $ it is jQuery-like selector. Field can be a Resource field or TV name.';
$_lang['import_enter_root_element'] = 'Indtast rodelementet der skal importeres:';
$_lang['import_files_found'] = '<strong>Fandt %s dokumenter til import...</strong><p/>';
$_lang['import_parent_document'] = 'Overordnet dokument:';
$_lang['import_parent_document_message'] = 'Brug dokumenttræet nedenfor til at vælge den overordnede placering at importere dine filer til.';
$_lang['import_resource_class'] = 'Vælg en modResource-klasse til import:<br/><small><em>Brug modStaticResource for at linke til statiske filer eller modDocument for at kopiere indholdet til databasen.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Mislykket!</span>';
$_lang['import_site_html'] = 'Importér site fra HTML';
$_lang['import_site_importing_document'] = 'Importerer filen <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Maksimum import tid:';
$_lang['import_site_maxtime_message'] = 'Her kan du angive antallet af sekunder MODX kan bruge på at importere websitet (overskriver PHP indstillingerne). Indtast 0 for ubegrænset tid. Vær opmærksom på, at en indstilling på 0 eller et rigtigt højt nummer kan gøre underlige ting på din server og ikke er anbefalet.';
$_lang['import_site_message'] = '<p>Med dette værktøj kan du importere indholdet fra et sæt HTML-filer til databasen.<em>Bemærk venligst at du skal kopierer dine filer og/eller mapper ind i core/import mappen.</em></p><p>Udfyld venligst mulighederne i nedenstående formular og vælg eventuelt en overordnet ressource til de importerede filer fra dokumenttræet, og tryk på \'Importér HTML\' for at starte importeringsprocessen. De importerede filer vil blive gemt i den valgte lokation, med (så vidt muligt) filnavnet som dokumentets alias og sidetitlen som dokumentets titel.</p>';
$_lang['import_site_resource'] = 'Importér ressourcer fra statiske filer';
$_lang['import_site_resource_message'] = '<p>Med dette værktøj kan du importere ressourcer fra et sæt statiske HTML-filer til databasen.<em>Bemærk venligst at du skal kopierer dine filer og/eller mapper ind i core/import mappen.</em></p><p>Udfyld venligst mulighederne i nedenstående formular og vælg eventuelt en overordnet ressource til de importerede filer fra dokumenttræet, og tryk på "Importér HTML" for at starte importeringsprocessen. De importerede filer vil blive gemt i den valgte lokation, med (så vidt muligt) filnavnet som dokumentets alias og sidetitlen som dokumentets titel.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">Sprunget over!</span>';
$_lang['import_site_start'] = 'Begynd importering';
$_lang['import_site_success'] = '<span style="color:#009900">Succes!</span>';
$_lang['import_site_time'] = 'Importering færdig. Importeringen tog %s sekunder at færdiggøre.';
$_lang['import_use_doc_tree'] = 'Brug dokumenttræet nedenfor til at vælge den overordnede placering at importere dine filer til.';