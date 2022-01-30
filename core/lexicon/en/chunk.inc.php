<?php
/**
 * Chunk English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

// Entry out of alpha order because it must come before the entry it's used in below
$_lang['example_tag_chunk_name'] = 'NameOfChunk';

$_lang['chunk'] = 'Chunk';
$_lang['chunk_category_desc'] = 'Use to group Chunks within the Elements tree.';
$_lang['chunk_code'] = 'Chunk Code (HTML)';
$_lang['chunk_description_desc'] = 'Usage information for this Chunk shown in search results and as a tooltip in the Elements tree.';
$_lang['chunk_delete_confirm'] = 'Are you sure you want to delete this chunk?';
$_lang['chunk_duplicate_confirm'] = 'Are you sure you want to duplicate this chunk?';
$_lang['chunk_err_create'] = 'An error occurred while trying to create the chunk.';
$_lang['chunk_err_duplicate'] = 'Error duplicating chunk.';
$_lang['chunk_err_ae'] = 'There is already a chunk with the name "[[+name]]".';
$_lang['chunk_err_invalid_name'] = 'Chunk name is invalid.';
$_lang['chunk_err_locked'] = 'Chunk is locked.';
$_lang['chunk_err_remove'] = 'An error occurred while trying to delete the chunk.';
$_lang['chunk_err_save'] = 'An error occurred while saving the chunk.';
$_lang['chunk_err_nf'] = 'Chunk not found!';
$_lang['chunk_err_nfs'] = 'Chunk not found with id: [[+id]]';
$_lang['chunk_err_ns'] = 'Chunk not specified.';
$_lang['chunk_err_ns_name'] = 'Please specify a name.';
$_lang['chunk_lock'] = 'Lock chunk for editing';
$_lang['chunk_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Chunk.';
$_lang['chunk_name_desc'] = 'Place the content generated by this Chunk in a Resource, Template, or other Chunk using the following MODX tag: <span class="copy-this">[[$<span class="example-replace-name">'.$_lang['example_tag_chunk_name'].'</span>]]</span>';
$_lang['chunk_new'] = 'Create Chunk';
$_lang['chunk_properties'] = 'Default Properties';
$_lang['chunk_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Chunk</em> as well as its content. The content must be HTML, either placed in the <a href="#x-form-el-modx-chunk-snippet">Chunk Code</a> field below or in a static external file, and may include MODX tags. Note, however, that PHP code will not run in this element.';
$_lang['chunk_tag_copied'] = 'Chunk tag copied!';
$_lang['chunk_title'] = 'Create/edit chunk';
$_lang['chunk_untitled'] = 'Untitled Chunk';
$_lang['chunks'] = 'Chunks';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['chunk_desc_category'] = $_lang['chunk_category_desc'];
$_lang['chunk_desc_description'] = $_lang['chunk_description_desc'];
$_lang['chunk_desc_name'] = $_lang['chunk_name_desc'];
$_lang['chunk_lock_msg'] = $_lang['chunk_lock_desc'];

// --tabs
$_lang['chunk_msg'] = $_lang['chunk_tab_general_desc'];
