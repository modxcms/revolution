<?php
/**
 * English Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'فیلد `[[+column]]` به جدول `[[+table]]` افزوده شد.';
$_lang['add_index'] = 'ایندکس `[[+index]]` به جدول `[[+table]]` افزوده شد.';
$_lang['add_moduser_classkey'] = 'برای پشتیبانی از توسعه‌ی modUser، فیلد class_key افزوده شد.';
$_lang['added_cachepwd'] = 'فیلد cachepwd افزوده شد. نسخه‌های پیشین فاقد آن بودند.';
$_lang['added_content_ft_idx'] = 'ایندکس جدیدِ `content_ft_idx` از نوع full-text افزوده شد. این ایندکس روی فیلدهای pagetitle، longtitle، description، introtext و content اعمال شده است.';
$_lang['allow_null_properties'] = 'مشخصه‌ی allow null برای `[[+class]]`.`properties` اصلاح شد.';
$_lang['alter_activeuser_action'] = 'فیلد `action` از کلاس modActiveUser بروز شد تا حجم بیشتری اطلاعات را نگهداری کند.';
$_lang['alter_usermessage_messageread'] = 'فیلد `messageread` از کلاس modUserMessage به `read` تغییر نام پیدا کرد.';
$_lang['alter_usermessage_postdate'] = 'نام فیلد `postdate` از modUserMessage به `date_sent`، و نوع آن از INT به DATETIME تغییر یافت.';
$_lang['alter_usermessage_subject'] = 'نوع فیلد `subject` از modUserMessage، از (60)VARCHAR به (255)VARCHAR تغییر یافت.';
$_lang['change_column'] = 'فیلد `[[+old]]` در جدول `[[+table]]` به `[[+new]]` تغییر یافت.';
$_lang['change_default_value'] = 'مقدار پیشفرض فیلد `[[+column]]` در جدول `[[+table]]` به "[[+value]]" تغییر یافت.';
$_lang['connector_acls_removed'] = 'Removed connector context ACLs.';
$_lang['connector_acls_not_removed'] = 'Could not remove connector context ACLs.';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'Could not remove connector context.';
$_lang['data_remove_error'] = 'Error removing data for class `[[+class]]`.';
$_lang['data_remove_success'] = 'Successfully removed data from table for class `[[+class]]`.';
$_lang['drop_column'] = 'Dropped column `[[+column]]` on table `[[+table]]`.';
$_lang['drop_index'] = 'Dropped index `[[+index]]` on table `[[+table]]`.';
$_lang['lexiconentry_createdon_null'] = 'Changed modLexiconEntry `createdon` to allow NULL.';
$_lang['lexiconentry_focus_alter'] = 'Changed modLexiconEntry `focus` from VARCHAR(100) to INT(10).';
$_lang['lexiconentry_focus_alter_int'] = 'Updated modLexiconEntry `focus` column data from string to new int foreign key from modLexiconTopic.';
$_lang['lexiconfocus_add_id'] = 'Added modLexiconFocus `id` column.';
$_lang['lexiconfocus_add_pk'] = 'Added modLexiconFocus PRIMARY KEY to `id` column.';
$_lang['lexiconfocus_alter_pk'] = 'Changed modLexiconFocus `name` from PRIMARY KEY to UNIQUE KEY';
$_lang['lexiconfocus_drop_pk'] = 'Dropped modLexiconFocus PRIMARY KEY.';
$_lang['modify_column'] = 'Modified column `[[+column]]` from `[[+old]]` to `[[+new]]` on table `[[+table]]`';
$_lang['rename_column'] = 'Renamed column `[[+old]]` to `[[+new]]` on table `[[+table]]`.';
$_lang['rename_table'] = 'Renamed table `[[+old]]` to `[[+new]]`.';
$_lang['remove_fulltext_index'] = 'Removed full-text index `[[+index]]`.';
$_lang['systemsetting_xtype_fix'] = 'Successfully fixed xtypes for modSystemSettings.';
$_lang['transportpackage_manifest_text'] = 'Modified column `manifest` to TEXT from MEDIUMTEXT on `[[+class]]`.';
$_lang['update_closure_table'] = 'Updating closure table data for class `[[+class]]`.';
$_lang['update_table_column_data'] = 'Updated data in column [[+column]] of table [[+table]] ( [[+class]] )';
$_lang['iso_country_code_converted'] = 'Successfully converted user profile country names to ISO codes.';
$_lang['legacy_cleanup_complete'] = 'Legacy file clean up complete.';
$_lang['legacy_cleanup_count'] = 'Removed [[+files]] file(s) and [[+folders]] folder(s).';
