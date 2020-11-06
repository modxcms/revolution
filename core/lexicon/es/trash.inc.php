<?php
/**
 * Trash English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['trash_menu'] = 'Papelera';
$_lang['trash_menu_desc'] = 'Administrar recursos borrados.';
$_lang['trash.page_title'] = 'Papelera - Gestion de Recursos eliminados';
$_lang['trash.tab_title'] = 'Papelera Bin';
$_lang['trash.intro_msg'] = 'Administra los recursos eliminados y los hijos no eliminados de los padres eliminados aquí.<br><i>Comprueba el estado de publicación antes de restaurar cualquier recurso.</i> Puedes (des)publicar recursos directamente de la cuadrícula con un doble clic en la celda <em>publicado</em> del recurso.';
$_lang['trash.manage_recycle_bin_tooltip'] = "Go to the trash bin manager and manage up to [[+count]] deleted resources";
$_lang['trash.deletedon_title'] = 'Deleted on';
$_lang['trash.deletedbyUser_title'] = 'Deleted by';
$_lang['trash.context_title'] = 'Contexto';
$_lang['trash.parent_path'] = 'Resource location';
$_lang['trash.purge_all'] = 'Erase all';
$_lang['trash.restore_all'] = 'Restore all';
$_lang['trash.selected_purge'] = 'Erase selected resources';
$_lang['trash.selected_restore'] = 'Restore selected resources';
$_lang['trash.purge'] = 'Erase resource';
$_lang['trash.purge_confirm_title'] = 'Erase resource(s)?';
$_lang['trash.purge_confirm_message'] = 'Do you really want to finally erase the following resource(s)? This cannot be undone.<hr>[[+list]]';
$_lang['trash.purge_all_confirm_message'] = 'Do you really want to finally erase the listed [[+count]] resource(s)?<br><br><strong>This cannot be undone, and it affects all currently trashed resources in the grid.</strong><hr>[[+list]]';
$_lang['trash.purge_all_empty_status'] = '[[+count]] resource(s) have been erased permanently.';
$_lang['trash.purge_err_delete'] = '[[+count]] resources have not been erased due to errors: [[+list]]';
$_lang['trash.purge_err_nothing'] = 'Nothing was erased, no errors occurred.';
$_lang['trash.purge_success_delete'] = '[[+count]] resource(s) successfully erased permanently.';
$_lang['trash.restore'] = 'Restore resource';
$_lang['trash.restore_confirm_title'] = 'Restore resource(s)?';
$_lang['trash.restore_confirm_message'] = 'Do you want to restore the following resource(s)?<hr>[[+list]]';
$_lang['trash.restore_confirm_message_with_publish'] = 'Do you want to restore the following resource(s)?<br><br><strong>Be aware that this will re-publish previously published resources!</strong><hr>[[+list]]';
$_lang['trash.restore_all_confirm_message'] = 'Do you really want to restore [[+count]] resource(s)? <hr>[[+list]]';
$_lang['trash.restore_success'] = '[[+count_success]] resources have been restored. <hr>[[+list]]';
$_lang['trash.restore_err'] = '[[+count_failures]] resource(s) could not be restored. <hr>[[+list]]';
