<?php
/**
 * Chunk English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

// Entry out of alpha order because it must come before the entry it's used in below
$_lang['example_tag_chunk_name'] = 'NameDesChunks';

$_lang['chunk'] = 'Chunk';
$_lang['chunk_category_desc'] = 'Ermöglicht die Gruppierung von Chunks innerhalb des Elementbaums.';
$_lang['chunk_code'] = 'Chunk-Code (HTML)';
$_lang['chunk_description_desc'] = 'Informationen zum Verwenden dieses Chunks in Suchergebnissen und als Tooltip im Elementbaum anzeigen.';
$_lang['chunk_delete_confirm'] = 'Sind Sie sicher, dass Sie diesen Chunk löschen möchten?';
$_lang['chunk_duplicate_confirm'] = 'Sind Sie sicher, dass Sie diesen Chunk duplizieren möchten?';
$_lang['chunk_err_create'] = 'Beim Versuch, den Chunk zu erstellen, ist ein Fehler aufgetreten.';
$_lang['chunk_err_duplicate'] = 'Beim Duplizieren des Chunks ist ein Fehler aufgetreten.';
$_lang['chunk_err_ae'] = 'Es existiert bereits ein Chunk mit dem Namen "[[+name]]".';
$_lang['chunk_err_invalid_name'] = 'Der Chunk-Name ist ungültig.';
$_lang['chunk_err_locked'] = 'Chunk ist gesperrt.';
$_lang['chunk_err_remove'] = 'Beim Versuch, den Chunk zu löschen, ist ein Fehler aufgetreten.';
$_lang['chunk_err_save'] = 'Beim Speichern des Chunks ist ein Fehler aufgetreten.';
$_lang['chunk_err_nf'] = 'Chunk nicht gefunden!';
$_lang['chunk_err_nfs'] = 'Chunk mit der ID [[+id]] nicht gefunden';
$_lang['chunk_err_ns'] = 'Chunk nicht angegeben.';
$_lang['chunk_err_ns_name'] = 'Bitte geben Sie einen Namen an.';
$_lang['chunk_lock'] = 'Chunk für die Bearbeitung sperren';
$_lang['chunk_lock_desc'] = 'Nur Benutzer mit „edit_locked“ Zugriffsberechtigung können diesen Chunk bearbeiten.';
$_lang['chunk_name_desc'] = 'Platzieren Sie den von diesem Chunk erzeugten Inhalt in einer Ressource, einem Template oder einem anderen Chunk, indem Sie den folgenden MODX-Tag verwenden: [[+tag]]';
$_lang['chunk_new'] = 'Chunk erstellen';
$_lang['chunk_properties'] = 'Standardeigenschaften';
$_lang['chunk_tab_general_desc'] = 'Hier können Sie die grundlegenden Attribute für diesen <em>Chunk</em> sowie dessen Inhalt eingeben. Der Inhalt muss HTML sein, entweder im Feld <em>Chunk Code</em> oder in einer statischen externen Datei. Er kann MODX-Tags enthalten. Beachten Sie jedoch, dass PHP-Code in diesem Element nicht ausgeführt wird.';
$_lang['chunk_tag_copied'] = 'Chunk-Tag kopiert!';
$_lang['chunks'] = 'Chunks';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['chunk_desc_category'] = $_lang['chunk_category_desc'];
$_lang['chunk_desc_description'] = $_lang['chunk_description_desc'];
$_lang['chunk_desc_name'] = $_lang['chunk_name_desc'];
$_lang['chunk_lock_msg'] = $_lang['chunk_lock_desc'];

// --tabs
$_lang['chunk_msg'] = $_lang['chunk_tab_general_desc'];
