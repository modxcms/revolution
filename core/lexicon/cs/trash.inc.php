<?php
/**
 * Trash English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['trash_menu'] = 'Koš';
$_lang['trash_menu_desc'] = 'Spravujte odstraněné prostředky.';
$_lang['trash.page_title'] = 'Koš - manažer odstraněných zdrojů';
$_lang['trash.tab_title'] = 'Odpadkový koš';
$_lang['trash.intro_msg'] = 'Manage the deleted resources and the not deleted children of deleted parents here.<br><i>Please check the publishing state, before you restore any resource.</i> You can (un-)publish resources directly from the grid with a double click on the published cell of the resource.';
$_lang['trash.manage_recycle_bin_tooltip'] = "Go to the trash bin manager and manage up to [[+count]] deleted resources";
$_lang['trash.deletedon_title'] = 'Odstraněno';
$_lang['trash.deletedbyUser_title'] = 'Odstraněno';
$_lang['trash.context_title'] = 'Kontext';
$_lang['trash.parent_path'] = 'Původní umístění';
$_lang['trash.purge_all'] = 'Erase all';
$_lang['trash.restore_all'] = 'Obnovit vše';
$_lang['trash.selected_purge'] = 'Erase selected resources';
$_lang['trash.selected_restore'] = 'Obnovit vybrané zdroje';
$_lang['trash.purge'] = 'Erase resource';
$_lang['trash.purge_confirm_title'] = 'Erase resource(s)?';
$_lang['trash.purge_confirm_message'] = 'Do you really want to finally erase the following resource(s)? This cannot be undone.<hr>[[+list]]';
$_lang['trash.purge_all_confirm_message'] = 'Do you really want to finally erase the listed [[+count]] resource(s)?<br><br><strong>This cannot be undone, and it affects all currently trashed resources in the grid.</strong><hr>[[+list]]';
$_lang['trash.purge_all_empty_status'] = '[[+count]] resource(s) have been erased permanently.';
$_lang['trash.purge_err_delete'] = '[[+count]] resources have not been erased due to errors: [[+list]]';
$_lang['trash.purge_err_nothing'] = 'Nothing was erased, no errors occurred.';
$_lang['trash.purge_success_delete'] = '[[+count]] resource(s) successfully erased permanently.';
$_lang['trash.restore'] = 'Obnovit zdroj';
$_lang['trash.restore_confirm_title'] = 'Obnovit zdroj(e)?';
$_lang['trash.restore_confirm_message'] = 'Chcete obnovit následující zdroj(e)?<hr>[[+list]]';
$_lang['trash.restore_confirm_message_with_publish'] = 'Do you want to restore the following resource(s)?<br><br><strong>Be aware that this will re-publish previously published resources!</strong><hr>[[+list]]';
$_lang['trash.restore_all_confirm_message'] = 'Do you really want to restore [[+count]] resource(s)? <hr>[[+list]]';
$_lang['trash.restore_success'] = '[[+count_success]] resources have been restored. <hr>[[+list]]';
$_lang['trash.restore_err'] = '[[+count_failures]] resource(s) could not be restored. <hr>[[+list]]';
