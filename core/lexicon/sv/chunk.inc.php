<?php
/**
 * Chunk English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

// Entry out of alpha order because it must come before the entry it's used in below
$_lang['example_tag_chunk_name'] = 'NamnPåChunk';

$_lang['chunk'] = 'Chunk';
$_lang['chunk_category_desc'] = 'Använd för att gruppera chunks i elementträdet.';
$_lang['chunk_code'] = 'Chunk-kod (html)';
$_lang['chunk_description_desc'] = 'Användningsinformation för denna chunk som visas i sökresultat och som ett verktygstips i elementträdet.';
$_lang['chunk_delete_confirm'] = 'Är du säker på att du vill ta bort denna chunk?';
$_lang['chunk_duplicate_confirm'] = 'Är du säker på att du vill duplicera denna chunk?';
$_lang['chunk_err_create'] = 'Ett fel inträffade när chunken skulle skapas.';
$_lang['chunk_err_duplicate'] = 'Ett fel inträffade när chunken skulle dupliceras.';
$_lang['chunk_err_ae'] = 'Det finns redan en chunk med namnet "[[+name]]".';
$_lang['chunk_err_invalid_name'] = 'Chunkens namn är ogiltigt.';
$_lang['chunk_err_locked'] = 'Chunken är låst.';
$_lang['chunk_err_remove'] = 'Ett fel inträffade när chunken skulle tas bort.';
$_lang['chunk_err_save'] = 'Ett fel inträffade när chunken skulle sparas.';
$_lang['chunk_err_nf'] = 'Chunken kunde inte hittas!';
$_lang['chunk_err_nfs'] = 'Kunde inte hitta chunken med id: [[+id]]';
$_lang['chunk_err_ns'] = 'Ingen chunk angiven.';
$_lang['chunk_err_ns_name'] = 'Ange ett namn.';
$_lang['chunk_lock'] = 'Lås chunk för redigering';
$_lang['chunk_lock_desc'] = 'Endast användare med “edit_locked”-behörighet kan redigera denna chunk.';
$_lang['chunk_name_desc'] = 'Placera innehållet som genereras av denna chunk i en resurs, mall eller annan chunk med hjälp av följande MODX-tagg: [[+tag]]';
$_lang['chunk_new'] = 'Skapa chunk';
$_lang['chunk_properties'] = 'Standardegenskaper';
$_lang['chunk_tab_general_desc'] = 'Här kan du ange grundläggande attribut för denna <em>chunk</em> samt dess innehåll. Innehållet måste vara HTML, antingen placerat i fältet <em>Chunk-kod</em> nedan eller i en statisk extern fil, och kan inkludera MODX-taggar. Observera att PHP-kod inte kan köras i detta element.';
$_lang['chunk_tag_copied'] = 'Chunk-taggen kopierades!';
$_lang['chunks'] = 'Chunks';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['chunk_desc_category'] = $_lang['chunk_category_desc'];
$_lang['chunk_desc_description'] = $_lang['chunk_description_desc'];
$_lang['chunk_desc_name'] = $_lang['chunk_name_desc'];
$_lang['chunk_lock_msg'] = $_lang['chunk_lock_desc'];

// --tabs
$_lang['chunk_msg'] = $_lang['chunk_tab_general_desc'];
