<?php
/**
 * Export English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['export_site_cacheable'] = 'Inkludér filer der ikke kan caches:';
$_lang['export_site_exporting_document'] = 'Exporterer fil <strong>%s</strong> af <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Mislykket!</span>';
$_lang['export_site_html'] = 'Eksportér websted til HTML';
$_lang['export_site_maxtime'] = 'Maksimum eksporttid:';
$_lang['export_site_maxtime_message'] = 'Her kan du angive antallet af sekunder MODX kan bruge på at eksportere websitet (overskriver PHP indstillingerne). Indtast 0 for ubegrænset tid. Vær opmærksom på, at en indstilling på 0 eller et rigtigt højt nummer kan gøre underlige ting på din server og ikke er anbefalet.';
$_lang['export_site_message'] = '<p>Ved hjælp af denne funktion kan du eksportere hele webstedet til HTML-filer. Bemærk dog, at du vil miste en del af MODX funktionaliteten hvis du gør det:</p><ul><li>Sidevisninger på eksporterede filer vil ikke blive registreret.</li><li>Interaktive snippets vil ikke fungerere på eksporterede filer</li><li>Kun regulære dokumenter vil blive eksporteret, weblinks vil ikke blive eksporteret.</li><li>Eksport-processen kan mislykkes, hvis dine dokumenter indeholder snippets, som laver omdirigeringer.</li><li>Afhængigt af på hvordan du har udformet dine dokumenter, style sheets og billeder, kan designet af dit websted kan blive ødelagt. For at rette dette kan du gemme/flytte dine eksporterede filer til det samme bibliotek hvor den overordnede MODX index.php fil er placeret.</li></ul><p>Udfyld venligst formularen og tryk \'Eksportér\' for at starte eksporteringsprocessen. Oprettede filer gemmes på den placering du angiver, hvor det er muligt bruge dokumentets alias som filnavn. Mens du eksporterer dit site er det bedst at have sat MODX indstillingen "venlige aliaser" til "Ja". Eksportering kan tage et stykke tid afhængigt af størrelsen af dit websted,.</p><p><em>Enhver eksisterende fil vil blive overskrevet af de nye filer hvis deres navne er identiske!</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>Har fundet %s dokumenter at eksportere...</strong></p>';
$_lang['export_site_prefix'] = 'Filpræfiks:';
$_lang['export_site_start'] = 'Start eksportering';
$_lang['export_site_success'] = '<span style="color:#009900">Succes!</span>';
$_lang['export_site_suffix'] = 'Filsuffiks:';
$_lang['export_site_target_unwritable'] = 'Destinationsmappen er ikke skrivbar. Sørg venligst for, at mappen er skrivbar og prøv igen.';
$_lang['export_site_time'] = 'Eksportering færdig. Eksporteringen tog %s sekunder at færdiggøre.';