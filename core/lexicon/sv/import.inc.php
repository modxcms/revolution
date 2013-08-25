<?php
/**
 * Import Swedish lexicon entries
 *
 * @language sv
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Ange en lista med fil-suffix som får importeras (separera med kommatecken)<br /><small><em>Lämna fältet tomt för att importera alla filer enligt de innehållstyper som finns på din webbplats. Okända typer kommer att hanteras som vanlig text.</em></small>';
$_lang['import_base_path'] = 'Ange bassökvägen för de filer som ska importeras<br /><small><em>Lämna fältet tomt för att använda målkontextens inställning för statisk sökväg.</em></small>';
$_lang['import_duplicate_alias_found'] = 'Resursen [[+id]] använder redan aliaset [[+alias]]. Ange ett unikt alias.';
$_lang['import_enter_root_element'] = 'Ange rot-elementet som ska importeras';
$_lang['import_element'] = 'Ange det rot-HTML-element som ska importeras';
$_lang['import_files_found'] = '<strong>Hittade %s dokument för import...</strong><p/>';
$_lang['import_parent_document'] = 'Föräldradokument';
$_lang['import_parent_document_message'] = 'Använd dokumentträdet nedan för att välja den förälder du vill importera filerna till.';
$_lang['import_resource_class'] = 'Välj en modResource-klass för import<br /><small><em>Använd modStaticResource för att länka till statiska filer, eller modDocument för att kopiera innehållet till databasen.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Misslyckades!</span>';
$_lang['import_site_html'] = 'Importera webbplats från HTML';
$_lang['import_site_importing_document'] = 'Importerar fil <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Max importtid';
$_lang['import_site_maxtime_message'] = 'Här kan du specificera antal sekunder som innehållshanteraren får ta i anspråk när den importerar sidan (åsidosätter PHPs inställningar). Skriv 0 för obegränsad tid. Notera att om 0 eller ett väldigt stort nummer används, kan skumma saker hända din server, och rekommenderas därför inte.';
$_lang['import_site_message'] = '<p>Med detta verktyg kan du importera en uppsättning HTML-filer till databasen. <em>Notera att du måste kopiera dina filer och/eller kataloger till core/import-katalogen.</em></p><p>Fyll i formuläret nedan. Om du vill kan du ange en föräldraresurs för de importerade filerna i dokumentträdet. Klicka på "Starta import" för att starta importprocessen. Filerna som importeras kommer att sparas till den valda platsen, och där det är möjligt kommer filnamnet att användas som dokumentets alias, och sidtiteln som dokumentets titel.</p>';
$_lang['import_site_resource'] = 'Importera resurser från statiska filer';
$_lang['import_site_resource_message'] = '<p>Med det här verktyget kan du importera resurser från en uppsättning statiska filer till databasen. <em>Notera att du behöver kopiera dina filer och/eller kataloger till katalogen core/import.</em></p><p>Fyll i inställningarna i formuläret nedan. Om du vill kan du ange en föräldraresurs för de importerade filerna i dokumentträdet. Klicka på "Importera resurser" för att startat importprocessen. Filerna som importeras kommer att sparas på den valda platsen med, om möjligt, filens namn som dokumentets alias och, om det är HTML, sidans titel som dokumentets titel.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">Överhoppad!</span>';
$_lang['import_site_start'] = 'Starta import';
$_lang['import_site_success'] = '<span style="color:#009900">Klart!</span>';
$_lang['import_site_time'] = 'Importen avklarad. Den tog %s sekunder att utföra.';
$_lang['import_use_doc_tree'] = 'Använd dokumentträdet som visas nedan för att ange den plats som dina filer ska importeras till.';
