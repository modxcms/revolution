<?php
/**
 * Export Swedish lexicon topic
 *
 * @language sv
 * @package modx
 * @subpackage lexicon
 */
$_lang['export_site_cacheable'] = 'Inkludera icke cachebara filer';
$_lang['export_site_exporting_document'] = 'Exporterar fil <strong>%s</strong> av <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Misslyckades!</span>';
$_lang['export_site_html'] = 'Exportera webbplatsen till HTML';
$_lang['export_site_maxtime'] = 'Max exporttid';
$_lang['export_site_maxtime_message'] = 'Här kan du specificera antal sekunder som MODX har på sig att exportera webbplatsen (åsidosätter PHPs inställningar). Skriv 0 för obegränsad tid. Notera att om 0 eller ett väldigt högt nummer skrivs, kan det få din server att göra konstiga saker och rekommenderas därför inte.';
$_lang['export_site_message'] = '<p>Med denna funktion kan du exportera hela webbplatsen till HTML-filer. Kom ihåg att du förlorar stora delar av funktionaliteten i MODX om du gör det:</p><ul><li>Sidläsningar på exporterade filer kommer inte att lagras.</li><li>Interaktiva snippets kommer inte att fungera i exporterade filer</li><li>Endast vanliga dokument kommer att exporteras. Webblänkar blir alltså inte exporterade.</li><li>Exportprocessen kan misslyckas om dina dokument innehåller snippets som skickar omdirigeringsanvisningar.</li><li>Beroende på hur du skrivit dina dokument, kan bilder och stilmallar få fel sökvägar. För att fixa detta, kan du spara eller flytta dina exporterade filer till samma katalog som index.php för MODX befinner sig i.</li></ul><p>Fyll i formuläret och tryck på "Starta export" för att starta exportprocessen. Filerna som skapas kommer att sparas där du specificerat, och när det är möjligt, används dokumentets alias som filnamn. När du ska exporterar din webbplats är det bäst att ha MODX  konfigurationsinställning "Vänliga alias" satt till "Ja". Beroende på storleken på din webbplats, kan exporten ta en del tid.</p><p><em>Redan existerande filer kommer att skrivas över om de har samma namn som en exporterad fil!</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>Hittade %s dokument att exportera...</strong><p/>';
$_lang['export_site_prefix'] = 'Filprefix';
$_lang['export_site_start'] = 'Starta export';
$_lang['export_site_success'] = '<span style="color:#009900">Klart!</span>';
$_lang['export_site_suffix'] = 'Filsuffix';
$_lang['export_site_target_unwritable'] = 'Målkatalogen är inte skrivbar. Se till att katalogen är skrivbar och försök igen.';
$_lang['export_site_time'] = 'Exporten är klar. Den tog %s sekunder att utföra.';
