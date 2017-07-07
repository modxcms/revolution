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
$_lang['trash.intro_msg'] = '
    Manage the deleted resources here. <br>
    <i>Before you restore any resource, check the publishing state</i> - you can unpublish resources here directly from the grid with a double-click on the published-cell of the resource.';

$_lang['trash.manage_recycle_bin_tooltip'] = "Go to the trash bin manager";

$_lang['trash.deletedon_title'] = 'Deleted on';
$_lang['trash.deletedbyUser_title'] = 'Deleted by User';

$_lang['trash.context_title'] = 'Context';

$_lang['trash.purge_all'] = 'Purge everything';
$_lang['trash.restore_all'] = 'Restore everything';

$_lang['trash.selected_purge'] = 'Purge selected resources';
$_lang['trash.selected_restore'] = 'Restore selected resources';

$_lang['trash.purge'] = 'Purge resource';
$_lang['trash.purge_confirm_title'] = 'Purge resource(s)?';
$_lang['trash.purge_confirm_message'] = 'Do you really want to finally delete the following resource(s)? This cannot be undone.<hr/>[[+list]]';

$_lang['trash.restore'] = 'Restore resource';
$_lang['trash.restore_confirm_title'] = 'Restore resource(s)?';
$_lang['trash.restore_confirm_message'] = 'Do you want to restore the following resource(s)?<hr/>[[+list]]';
$_lang['trash.restore_confirm_message_with_publish'] = 'Do you want to restore the following resource(s)?<br/><br/><strong>Be aware that this will re-publish previously published resources!</strong><hr/>[[+list]]';
