<?php
/**
 * Snippet English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['example_tag_snippet_name'] = 'NamnPåSnippet';
$_lang['snippet'] = 'Snippet';
$_lang['snippets_available'] = 'Snippets som är tillgängliga för dig att inkludera i dina sidor';
$_lang['snippet_category_desc'] = 'Använd för att gruppera snippets i elementträdet.';
$_lang['snippet_code'] = 'Snippet-kod (php)';
$_lang['snippet_delete_confirm'] = 'Är du säker på att du vill ta bort denna snippet?';
$_lang['snippet_description_desc'] = 'Användningsinformation för denna snippet som visas i sökresultat och som ett verktygstips i elementträdet.';
$_lang['snippet_duplicate_confirm'] = 'Är du säker på att du vill duplicera denna snippet?';
$_lang['snippet_duplicate_error'] = 'Ett fel inträffade när snippeten skulle dupliceras.';
$_lang['snippet_err_create'] = 'Ett fel inträffade när snippeten skulle skapas.';
$_lang['snippet_err_delete'] = 'Ett fel inträffade när snippeten skulle tas bort.';
$_lang['snippet_err_duplicate'] = 'Ett fel inträffade när snippeten skulle dupliceras.';
$_lang['snippet_err_ae'] = 'Det finns redan en snippet med namnet "[[+name]]".';
$_lang['snippet_err_invalid_name'] = 'Snippetens namn är ogiltigt.';
$_lang['snippet_err_locked'] = 'Denna snippet är låst för redigering.';
$_lang['snippet_err_nf'] = 'Snippeten kunde inte hittas!';
$_lang['snippet_err_ns'] = 'Ingen snippet angiven.';
$_lang['snippet_err_ns_name'] = 'Ange ett namn på snippeten.';
$_lang['snippet_err_remove'] = 'Ett fel inträffade när snippeten skulle tas bort.';
$_lang['snippet_err_save'] = 'Ett fel inträffade när snippeten skulle sparas.';
$_lang['snippet_execonsave'] = 'Kör snippeten efter att den sparats.';
$_lang['snippet_lock'] = 'Lås snippet för redigering';
$_lang['snippet_lock_desc'] = 'Endast användare med “edit_locked”-behörighet kan redigera denna snippet.';
$_lang['snippet_management_msg'] = 'Här kan du skapa nya snippets eller välja en redan befintlig för redigering.';
$_lang['snippet_name_desc'] = 'Placera innehållet som genereras av denna snippet i en resurs, mall eller chunk med hjälp av följande MODX-tagg: [[+tag]]';
$_lang['snippet_new'] = 'Skapa snippet';
$_lang['snippet_properties'] = 'Standardegenskaper';
$_lang['snippet_tab_general_desc'] = 'Här kan du ange grundläggande attribut för denna <em>snippet</em> samt dess innehåll. Innehållet måste vara PHP, antingen placerat i fältet <em>Snippet-kod</em> nedan eller i en statisk extern fil. För att ta emot utdata från snippeten vid den punkt där den kallas (i en mall eller chunk) så måste ett värde returneras från koden.';
$_lang['snippet_tag_copied'] = 'Snippet-tagg kopierad!';
$_lang['snippets'] = 'Snippets';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['snippet_desc_category'] = $_lang['snippet_category_desc'];
$_lang['snippet_desc_description'] = $_lang['snippet_description_desc'];
$_lang['snippet_desc_name'] = $_lang['snippet_name_desc'];
$_lang['snippet_lock_msg'] = $_lang['snippet_lock_desc'];

// --tabs
$_lang['snippet_msg'] = $_lang['snippet_tab_general_desc'];
