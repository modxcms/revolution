<?php
/**
 * Trash English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['trash_menu'] = 'Trash';
$_lang['trash_menu_desc'] = 'Manage deleted files.';

$_lang['trash.page_title'] = 'Trash - Deleted Files Manager';

$_lang['trash.tab_title'] = 'Recycle Bin';
$_lang['trash.intro_msg'] = '
    Manage the deleted files here. <br>
    <i>Before you restore any files, check the publishing state</i> - you can unpublish files here directly from the grid with a double-click on the published-cell of the resource.';

$_lang['trash.deletedon_title'] = 'Deleted on';
$_lang['trash.deletedby_title'] = 'Deleted by';

$_lang['trash.context_title'] = 'Context';


$_lang['trash.selected_purge'] = 'Purge selected files';
$_lang['trash.selected_restore'] = 'Restore selected files';

$_lang['trash.purge'] = 'Purge resource';
$_lang['trash.purge_confirm_title'] = 'Purge resource(s)?';
$_lang['trash.purge_confirm_message'] = 'Do you really want to finally delete the selected resource(s)? This cannot be undone.';

$_lang['trash.restore'] = 'Restore resource';
$_lang['trash.restore_confirm_title'] = 'Restore resource(s)?';
$_lang['trash.restore_confirm_message'] = 'Do you want to restore the selected resource(s)?';
$_lang['trash.restore_confirm_message_with_publish'] = 'Do you want to restore the selected resource(s)?<br/><br/><strong>Be aware that this will re-publish previously published resources!</strong>';
