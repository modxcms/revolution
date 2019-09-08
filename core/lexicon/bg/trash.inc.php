<?php
/**
 * Trash English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['trash_menu'] = 'Trash';
$_lang['trash_menu_desc'] = 'Manage deleted resources.';
$_lang['trash.page_title'] = 'Trash - Deleted Resources Manager';
$_lang['trash.tab_title'] = 'Trash Bin';
$_lang['trash.intro_msg'] = 'Manage the deleted resources and the not deleted children of deleted parents here.<br><i>Please check the publishing state, before you restore any resource.</i> You can (un-)publish resources directly from the grid with a double click on the published cell of the resource.';
$_lang['trash.manage_recycle_bin_tooltip'] = "Go to the trash bin manager and manage up to [[+count]] deleted resources";
$_lang['trash.deletedon_title'] = 'Deleted on';
$_lang['trash.deletedbyUser_title'] = 'Deleted by';
$_lang['trash.context_title'] = 'Контекст';
$_lang['trash.parent_path'] = 'Resource location';
$_lang['trash.purge_all'] = 'Purge all';
$_lang['trash.restore_all'] = 'Restore all';
$_lang['trash.selected_purge'] = 'Purge selected resources';
$_lang['trash.selected_restore'] = 'Restore selected resources';
$_lang['trash.purge'] = 'Purge resource';
$_lang['trash.purge_confirm_title'] = 'Purge resource(s)?';
$_lang['trash.purge_confirm_message'] = 'Do you really want to finally delete the following resource(s)? This cannot be undone.<hr>[[+list]]';
$_lang['trash.purge_all_confirm_message'] = 'Do you really want to finally delete the listed [[+count]] resource(s)?<br><br><strong>This cannot be undone, and it affects all currently trashed resources in the grid.</strong><hr>[[+list]]';
$_lang['trash.purge_all_empty_status'] = '[[+count]] resource(s) have been purged permanently.';
$_lang['trash.purge_err_delete'] = '[[+count]] resources have not been purged due to errors: [[+list]]';
$_lang['trash.purge_err_nothing'] = 'Nothing was purged, no errors occurred.';
$_lang['trash.purge_success_delete'] = '[[+count]] resource(s) successfully purged permanently.';
$_lang['trash.restore'] = 'Restore resource';
$_lang['trash.restore_confirm_title'] = 'Restore resource(s)?';
$_lang['trash.restore_confirm_message'] = 'Do you want to restore the following resource(s)?<hr>[[+list]]';
$_lang['trash.restore_confirm_message_with_publish'] = 'Do you want to restore the following resource(s)?<br><br><strong>Be aware that this will re-publish previously published resources!</strong><hr>[[+list]]';
$_lang['trash.restore_all_confirm_message'] = 'Do you really want to restore [[+count]] resource(s)? <hr>[[+list]]';
$_lang['trash.restore_success'] = '[[+count_success]] resources have been restored. <hr>[[+list]]';
$_lang['trash.restore_err'] = '[[+count_failures]] resource(s) could not be restored. <hr>[[+list]]';
