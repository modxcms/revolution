<?php
/**
 * Japanese Upgrades Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 * @author KUROI Enogu http://twitter.com/enogu
 */
$_lang['add_column'] = '新しい列`%s`を`%s`テーブルに追加しました。';
$_lang['add_index'] = '新しいインデックス`%s`を`%s`テーブルに追加しました。';
$_lang['add_moduser_classkey'] = 'modUserの派生クラスをサポートするためにclass_keyフィールドを追加しました。';
$_lang['added_cachepwd'] = 'cachepwdフィールドが発見できなかったため新たに追加しました。';
$_lang['added_content_ft_idx'] = '新しいフルテキストインデックス`content_ft_idx`を追加しました。このインデックスは`pagetitle`, `longtitle`, `description`, `introtext`, `content`のソートを補助します。';
$_lang['allow_null_properties'] = '`%s`.`properties`をNull許容フィールドに調整しました。';
$_lang['alter_activeuser_action'] = 'modActiveUserの`action`フィールドのサイズを拡大しました。';
$_lang['alter_usermessage_messageread'] = 'modUserMessageの`messageread`フィールドを`read`に変更しました。';
$_lang['alter_usermessage_postdate'] = 'modUserMessageの`postdate`フィールドをDATETIME型の`date_sent`に変更しました。';
$_lang['alter_usermessage_subject'] = 'modUserMessageの`subject`フィールドサイズを最大255文字に拡大しました。';
$_lang['change_column'] = '`%s`フィールドから`%s`に`%s`テーブルを変更しました。';
$_lang['change_default_value'] = 'Changed default value for column `%s` to %s on table `%s`.';
$_lang['connector_acls_removed'] = 'connectorコンテキストのアクセス制御リストを削除しました。';
$_lang['connector_acls_not_removed'] = 'connectorコンテキストのアクセス制御リストを削除できませんでした。';
$_lang['connector_ctx_removed'] = '';
$_lang['connector_ctx_not_removed'] = 'connectorコンテキストを削除できませんでした。';
$_lang['data_remove_error'] = '`%s`クラスのインスタンス削除中にエラーが発生しました。';
$_lang['data_remove_success'] = '`%s`クラスのインスタンスは正常に削除されました。';
$_lang['drop_column'] = 'Dropped column `%s` on table `%s`.';
$_lang['drop_index'] = 'Dropped index `%s` on table `%s`.';
$_lang['lexiconentry_createdon_null'] = 'modLexiconEntryの`createdon`をNull許容フィールドに調整しました。';
$_lang['lexiconentry_focus_alter'] = 'modLexiconEntryの`focus`フィールドのデータ型をVARCHAR(100)からINT(10)に変更しました。';
$_lang['lexiconentry_focus_alter_int'] = 'modLexiconEntryの`focus`フィールドは文字列型から整数型に変更され、modLexiconTopicクラスのインスタンスの外部キーとなりました。';
$_lang['lexiconfocus_add_id'] = 'modLexiconFocusに`id`フィールドが追加されました。';
$_lang['lexiconfocus_add_pk'] = 'modLexiconFocusに主キー(id)を追加しました。';
$_lang['lexiconfocus_alter_pk'] = 'modLexiconFocusの`name`フィールドを主キーから一意キーに変更しました。';
$_lang['lexiconfocus_drop_pk'] = 'modLexiconFocusの主キーを削除しました。';
$_lang['rename_column'] = 'Renamed column `%s` to `%s` on table `%s`.';
$_lang['rename_table'] = 'Renamed table `%s` to `%s`.';
$_lang['remove_fulltext_index'] = 'フルテキストインデックス`%s`を削除しました。';
$_lang['systemsetting_xtype_fix'] = 'Successfully fixed xtypes for modSystemSettings.';
$_lang['transportpackage_manifest_text'] = '`%s`テーブルの`manifest`フィールドのデータ型をMEDIUMTEXTからTEXTに変更しました。';
$_lang['update_closure_table'] = 'Updating closure table data for class `%s`.';
