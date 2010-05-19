<?php
/**
 * English Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'Added new `%s` column to `%s`.';
$_lang['add_index'] = 'Added new index on `%s` for table `%s`.';
$_lang['add_moduser_classkey'] = 'Added class_key field to support modUser derivatives.';
$_lang['added_cachepwd'] = 'Added cachepwd field missing in early Revolution releases.';
$_lang['added_content_ft_idx'] = 'Added new `content_ft_idx` full-text index on the fields `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
$_lang['allow_null_properties'] = 'Fixing allow null for `%s`.`properties`.';
$_lang['alter_activeuser_action'] = 'Modified modActiveUser `action` field to allow longer action labels.';
$_lang['alter_usermessage_messageread'] = 'Changed modUserMessage `messageread` field to `read`.';
$_lang['alter_usermessage_postdate'] = 'Changed modUserMessage `postdate` field from an INT to a DATETIME and to name `date_sent`.';
$_lang['alter_usermessage_subject'] = 'Changed modUserMessage `subject` field from VARCHAR(60) to VARCHAR(255).';
$_lang['change_column'] = 'Changed `%s` field to `%s` on table `%s`.';
$_lang['change_default_value'] = 'Changed default value for column `%s` to %s on table `%s`.';
$_lang['connector_acls_removed'] = 'Removed connector context ACLs.';
$_lang['connector_acls_not_removed'] = 'Could not remove connector context ACLs.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'Could not remove connector context.';
$_lang['data_remove_error'] = 'Error removing data for class `%s`.';
$_lang['data_remove_success'] = 'Successfully removed data from table for class `%s`.';
$_lang['drop_column'] = 'Dropped column `%s` on table `%s`.';
$_lang['drop_index'] = 'Dropped index `%s` on table `%s`.';
$_lang['lexiconentry_createdon_null'] = 'Changed modLexiconEntry `createdon` to allow NULL.';
$_lang['lexiconentry_focus_alter'] = 'Changed modLexiconEntry `focus` from VARCHAR(100) to INT(10).';
$_lang['lexiconentry_focus_alter_int'] = 'Updated modLexiconEntry `focus` column data from string to new int foreign key from modLexiconTopic.';
$_lang['lexiconfocus_add_id'] = 'Added modLexiconFocus `id` column.';
$_lang['lexiconfocus_add_pk'] = 'Added modLexiconFocus PRIMARY KEY to `id` column.';
$_lang['lexiconfocus_alter_pk'] = 'Changed modLexiconFocus `name` from PRIMARY KEY to UNIQUE KEY';
$_lang['lexiconfocus_drop_pk'] = 'Dropped modLexiconFocus PRIMARY KEY.';
$_lang['rename_column'] = 'Renamed column `%s` to `%s` on table `%s`.';
$_lang['rename_table'] = 'Renamed table `%s` to `%s`.';
$_lang['remove_fulltext_index'] = 'Removed full-text index `%s`.';
$_lang['systemsetting_xtype_fix'] = 'Successfully fixed xtypes for modSystemSettings.';
$_lang['transportpackage_manifest_text'] = 'Modified column `manifest` to TEXT from MEDIUMTEXT on `%s`.';
$_lang['update_closure_table'] = 'Updating closure table data for class `%s`.';