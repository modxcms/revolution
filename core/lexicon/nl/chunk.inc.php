<?php
/**
 * Chunk English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

// Entry out of alpha order because it must come before the entry it's used in below
$_lang['example_tag_chunk_name'] = 'ChunkNaam';

$_lang['chunk'] = 'Chunk';
$_lang['chunk_category_desc'] = 'Gebruik om chunks te groeperen in de Elementenboom.';
$_lang['chunk_code'] = 'Chunk Code (HTML)';
$_lang['chunk_description_desc'] = 'Gebruiksinformatie voor deze chunk die in de zoekresultaten wordt weergegeven en als een tooltip in de Elementenboom wordt weergegeven.';
$_lang['chunk_delete_confirm'] = 'Weet je zeker dat je deze chunk wilt verwijderen?';
$_lang['chunk_duplicate_confirm'] = 'Weet je zeker dat je deze chunk wilt dupliceren?';
$_lang['chunk_err_create'] = 'Er is een fout opgetreden tijdens het aanmaken van de chunk.';
$_lang['chunk_err_duplicate'] = 'Fout bij dupliceren chunk.';
$_lang['chunk_err_ae'] = 'Er is reeds een chunk met de naam "[[+name]]".';
$_lang['chunk_err_invalid_name'] = 'Chunk naam is ongeldig.';
$_lang['chunk_err_locked'] = 'Chunk is vergrendeld.';
$_lang['chunk_err_remove'] = 'Er is een fout opgetreden tijdens het verwijderen van de chunk.';
$_lang['chunk_err_save'] = 'Er is een fout opgetreden tijdens het opslaan van de chunk.';
$_lang['chunk_err_nf'] = 'Chunk niet gevonden!';
$_lang['chunk_err_nfs'] = 'Chunk met sleutel [[+id]] niet gevonden';
$_lang['chunk_err_ns'] = 'Chunk niet gespecificeerd.';
$_lang['chunk_err_ns_name'] = 'Vul een naam in.';
$_lang['chunk_lock'] = 'Vergrendel chunk voor bewerken';
$_lang['chunk_lock_desc'] = 'Alleen gebruikers met "edit_locked" rechten kunnen deze chunk bewerken.';
$_lang['chunk_name_desc'] = 'Plaats de inhoud gegenereerd door deze chunk in een document, sjabloon of andere chunk met behulp van de volgende MODX tag: [[+tag]]';
$_lang['chunk_new'] = 'Chunk maken';
$_lang['chunk_properties'] = 'Standaard eigenschappen';
$_lang['chunk_tab_general_desc'] = 'Hier kunt u de basisattributen voor deze <em>Chunk</em> en de inhoud ervan invoeren. De inhoud moet HTML, geplaatst in het <em>Chunk Code</em> veld hieronder of in een statisch extern bestand, en kan MODX tags bevatten. Let er echter op dat PHP-code niet zal worden uitgevoerd in dit element.';
$_lang['chunk_tag_copied'] = 'Chunk tag gekopieerd!';
$_lang['chunks'] = 'Chunks';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['chunk_desc_category'] = $_lang['chunk_category_desc'];
$_lang['chunk_desc_description'] = $_lang['chunk_description_desc'];
$_lang['chunk_desc_name'] = $_lang['chunk_name_desc'];
$_lang['chunk_lock_msg'] = $_lang['chunk_lock_desc'];

// --tabs
$_lang['chunk_msg'] = $_lang['chunk_tab_general_desc'];
