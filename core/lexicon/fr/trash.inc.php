<?php
/**
 * Trash English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['trash_menu'] = 'Corbeille';
$_lang['trash_menu_desc'] = 'Gérer les ressources supprimées.';
$_lang['trash.page_title'] = 'Corbeille - Gestionnaire de ressources supprimées';
$_lang['trash.tab_title'] = 'Corbeille';
$_lang['trash.intro_msg'] = 'Gérez ici les ressources supprimées et les enfants non supprimés des parents supprimés.<br><i>Veuillez vérifier l\'état de publication avant de restaurer une ressource.</i> Vous pouvez (dé-)publier des ressources directement à partir de la grille en double-cliquant sur la cellule publiée de la ressource.';
$_lang['trash.manage_recycle_bin_tooltip'] = "Allez dans le gestionnaire de corbeille et gérez jusqu'à [[+count]] ressources supprimées";
$_lang['trash.deletedon_title'] = 'Supprimé le';
$_lang['trash.deletedbyUser_title'] = 'Supprimé par';
$_lang['trash.context_title'] = 'Contexte';
$_lang['trash.parent_path'] = 'Emplacement des ressources';
$_lang['trash.purge_all'] = 'Erase all';
$_lang['trash.restore_all'] = 'Tout rétablir';
$_lang['trash.selected_purge'] = 'Erase selected resources';
$_lang['trash.selected_restore'] = 'Restaurer les ressources sélectionnées';
$_lang['trash.purge'] = 'Erase resource';
$_lang['trash.purge_confirm_title'] = 'Erase resource(s)?';
$_lang['trash.purge_confirm_message'] = 'Do you really want to finally erase the following resource(s)? This cannot be undone.<hr>[[+list]]';
$_lang['trash.purge_all_confirm_message'] = 'Do you really want to finally erase the listed [[+count]] resource(s)?<br><br><strong>This cannot be undone, and it affects all currently trashed resources in the grid.</strong><hr>[[+list]]';
$_lang['trash.purge_all_empty_status'] = '[[+count]] resource(s) have been erased permanently.';
$_lang['trash.purge_err_delete'] = '[[+count]] resources have not been erased due to errors: [[+list]]';
$_lang['trash.purge_err_nothing'] = 'Nothing was erased, no errors occurred.';
$_lang['trash.purge_success_delete'] = '[[+count]] resource(s) successfully erased permanently.';
$_lang['trash.restore'] = 'Restaurer la ressource';
$_lang['trash.restore_confirm_title'] = 'Restaurer le(s) ressource(s) ?';
$_lang['trash.restore_confirm_message'] = 'Voulez-vous restaurer les ressources suivantes ?<hr>[[+list]]';
$_lang['trash.restore_confirm_message_with_publish'] = 'Voulez-vous restaurer la(les) ressource(s) suivante(s)?<br><br><strong>Attention, ceci va republier les ressources précédemment publiées !</strong><hr>[[+list]]';
$_lang['trash.restore_all_confirm_message'] = 'Voulez-vous vraiment restaurer [[+count]] ressource(s)? <hr>[[+list]]';
$_lang['trash.restore_success'] = '[[+count_success]] ressources ont été restaurées. <hr>[[+list]]';
$_lang['trash.restore_err'] = '[[+count_failures]] ressource(s) n\'ont pas pu être restaurée.(s) <hr>[[+list]]';
