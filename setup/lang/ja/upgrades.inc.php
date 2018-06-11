<?php
/**
 * English Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['add_column'] = 'Added new `[[+column]]` column to [[+table]].';
$_lang['add_index'] = 'Added new index on `[[+index]]` for table [[+table]].';
$_lang['alter_column'] = 'Modified column `[[+column]]` in table [[+table]].';
$_lang['add_moduser_classkey'] = 'modUserの派生クラスをサポートするためにclass_keyフィールドを追加しました。';
$_lang['added_cachepwd'] = 'cachepwdフィールドが発見できなかったため新たに追加しました。';
$_lang['added_content_ft_idx'] = '新しいフルテキストインデックス content_ft_idx を追加しました。このインデックスは pagetitle, longtitle, description, introtext, contentのソートを補助します。';
$_lang['allow_null_properties'] = '[[+class]].properties をNull許容フィールドに調整しました。';
$_lang['alter_activeuser_action'] = 'modActiveUserの action フィールドのサイズを拡大しました。';
$_lang['alter_usermessage_messageread'] = 'modUserMessageの messageread フィールドを read に変更しました。';
$_lang['alter_usermessage_postdate'] = 'modUserMessageの postdate フィールドをDATETIME型の date_sent に変更しました。';
$_lang['alter_usermessage_subject'] = 'modUserMessageの subject フィールドサイズを最大255文字に拡大しました。';
$_lang['change_column'] = 'Changed `[[+old]]` field to `[[+new]]` on table [[+table]].';
$_lang['change_default_value'] = 'Changed default value for column `[[+column]]` to "[[+value]]" on table [[+table]].';
$_lang['connector_acls_removed'] = 'connectorコンテキストのアクセス制御リストを削除しました。';
$_lang['connector_acls_not_removed'] = 'connectorコンテキストのアクセス制御リストを削除できませんでした。';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'connectorコンテキストを削除できませんでした。';
$_lang['data_remove_error'] = '[[+class]]クラスのインスタンス削除中にエラーが発生しました。';
$_lang['data_remove_success'] = '[[+class]]クラスのインスタンスは正常に削除されました。';
$_lang['drop_column'] = 'Dropped column `[[+column]]` on table [[+table]].';
$_lang['drop_index'] = 'Dropped index `[[+index]]` on table [[+table]].';
$_lang['lexiconentry_createdon_null'] = 'modLexiconEntryの createdon をNull許容フィールドに調整しました。';
$_lang['lexiconentry_focus_alter'] = 'modLexiconEntryの focus フィールドのデータ型をVARCHAR(100)からINT(10)に変更しました。';
$_lang['lexiconentry_focus_alter_int'] = 'modLexiconEntryの focus フィールドは文字列型から整数型に変更され、modLexiconTopicクラスのインスタンスの外部キーとなりました。';
$_lang['lexiconfocus_add_id'] = 'modLexiconFocusに id フィールドが追加されました。';
$_lang['lexiconfocus_add_pk'] = 'modLexiconFocusに主キー(id)を追加しました。';
$_lang['lexiconfocus_alter_pk'] = 'modLexiconFocusの name フィールドを主キーから一意キーに変更しました。';
$_lang['lexiconfocus_drop_pk'] = 'modLexiconFocusの主キーを削除しました。';
$_lang['modify_column'] = 'Modified column `[[+column]]` from `[[+old]]` to `[[+new]]` on table [[+table]]';
$_lang['rename_column'] = 'Renamed column `[[+old]]` to `[[+new]]` on table [[+table]].';
$_lang['rename_table'] = 'テーブル[[+old]]を新しい名前[[+new]]にリネームしました。';
$_lang['remove_fulltext_index'] = 'フルテキストインデックス[[+index]]を削除しました。';
$_lang['systemsetting_xtype_fix'] = 'modSystemSettingsのxtype（Ext.js用）の修正に成功しました。';
$_lang['transportpackage_manifest_text'] = '[[+class]]テーブルの manifest フィールドのデータ型をMEDIUMTEXTからTEXTに変更しました。';
$_lang['update_closure_table'] = 'クラス[[+class]]向けのクロージャーテーブル（closure table）データをアップデートしています。';
$_lang['update_table_column_data'] = 'テーブル[[+table]]の列[[+column]]をアップデートしました(クラス[[+class]]）。';
$_lang['iso_country_code_converted'] = 'Successfully converted user profile country names to ISO codes.';
$_lang['legacy_cleanup_complete'] = 'Legacy file clean up complete.';
$_lang['legacy_cleanup_count'] = 'Removed [[+files]] file(s) and [[+folders]] folder(s).';
