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

$_lang['chunk'] = 'Yığın';
$_lang['chunk_category_desc'] = 'Use to group Chunks within the Elements tree.';
$_lang['chunk_code'] = 'Chunk Code (HTML)';
$_lang['chunk_description_desc'] = 'Usage information for this Chunk shown in search results and as a tooltip in the Elements tree.';
$_lang['chunk_delete_confirm'] = 'Bu yığını silmek istediğinizden emin misiniz?';
$_lang['chunk_duplicate_confirm'] = 'Bu yığını çoğaltmak istediğinizden emin misiniz?';
$_lang['chunk_err_create'] = 'An error occurred while trying to create the chunk.';
$_lang['chunk_err_duplicate'] = 'Yığın kopyalanırken hata oluştu.';
$_lang['chunk_err_ae'] = 'Zaten "[[+ name]]" adında bir yığın var. "';
$_lang['chunk_err_invalid_name'] = 'Yığın adı geçersiz.';
$_lang['chunk_err_locked'] = 'Yığın kilitli.';
$_lang['chunk_err_remove'] = 'An error occurred while trying to delete the chunk.';
$_lang['chunk_err_save'] = 'Yığın kaydedilirken bir hata oluştu.';
$_lang['chunk_err_nf'] = 'Yığın bulunamadı!';
$_lang['chunk_err_nfs'] = 'Yığının kimliği bulunamadı.';
$_lang['chunk_err_ns'] = 'Yığın belirtilmedi.';
$_lang['chunk_err_ns_name'] = 'Lütfen bir ad belirtin.';
$_lang['chunk_lock'] = 'Düzenleme için yığını kilitleyin';
$_lang['chunk_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Chunk.';
$_lang['chunk_name_desc'] = 'Place the content generated by this Chunk in a Resource, Template, or other Chunk using the following MODX tag: [[+tag]]';
$_lang['chunk_new'] = 'Create Chunk';
$_lang['chunk_properties'] = 'Varsayılan Özellikler';
$_lang['chunk_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Chunk</em> as well as its content. The content must be HTML, either placed in the <em>Chunk Code</em> field below or in a static external file, and may include MODX tags. Note, however, that PHP code will not run in this element.';
$_lang['chunk_tag_copied'] = 'Chunk tag copied!';
$_lang['chunk_title'] = 'Yığın oluştur/düzenle';
$_lang['chunk_untitled'] = 'İsimsiz Yığın';
$_lang['chunks'] = 'Yığınlar';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['chunk_desc_category'] = $_lang['chunk_category_desc'];
$_lang['chunk_desc_description'] = $_lang['chunk_description_desc'];
$_lang['chunk_desc_name'] = $_lang['chunk_name_desc'];
$_lang['chunk_lock_msg'] = $_lang['chunk_lock_desc'];

// --tabs
$_lang['chunk_msg'] = $_lang['chunk_tab_general_desc'];
