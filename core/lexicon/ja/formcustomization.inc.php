<?php
/**
 * Form Customization Japanese lexicon topic
 *
 * @language ja
 * @package modx
 * @subpackage lexicon
 * @author shimojo http://www.priqia.com/
 * @author yamamoto http://kyms.jp
 */
$_lang['action'] = '操作内容';
$_lang['action_desc'] = 'このルールはアクションに適用されます。';
$_lang['activate'] = '有効化';
$_lang['constraint'] = '条件';
$_lang['constraint_class'] = 'このルールの対象';
$_lang['constraint_class_desc'] = 'オプション。「テンプレートIDが4の場合にこのルールを有効にする」などの条件をここでセットします。投稿画面の場合「modResource」でよいみたいです(※注・日本チーム) Optional. If set, along with the Constraint Field and Constraint options, will restrict this rule to the constraints applied.';
$_lang['constraint_desc'] = 'オプション。有効条件と見なす値をセットします。たとえば「テンプレートIDが4の場合にこのルールを有効にする」の場合「4」をここにセットします。Optional. The value of the Constraint Field that should be checked against.';
$_lang['constraint_field'] = 'フィールド名';
$_lang['constraint_field_desc'] = 'オプション。対象フィールドの名前を記述します。たとえば「template」など。Optional. The field by which this constraint should be applied.';
$_lang['containing_panel'] = 'これを含む領域';
$_lang['containing_panel_desc'] = '対象オブジェクト(フィールドやタブ)を含むDIV領域のID名。modx-panel-resource・modx-page-settings・modx-resource-tabsのうちのいずれか。This is sometimes necessary for certain rules, so that the system can know what form or panel the field is in.';
$_lang['deactivate'] = '無効化';
$_lang['field'] = 'フィールド';
$_lang['field_desc'] = 'This is the field to affect. This may also be a tab, or TV. If it is a TV, please specify in this format: "tv#", where # is the ID of the TV.';
$_lang['field_default'] = 'フィールドのデフォルト値';
$_lang['field_label'] = 'フィールドのラベル';
$_lang['field_visible'] = 'フィールドの表示／非表示';
$_lang['form_customization_msg'] = 'Here is a list of currently applied Rules. More information on Rules and Form Customization can be found <a href="http://svn.modxcms.com/docs/display/revolution/Form+Customization" target="_blank">here</a>. Please note that improper Rules might cause problems with your MODx Revolution installation. Inactive Rules are faded gray.';
$_lang['form_rules'] = 'フォームのルール';
$_lang['rule'] = 'ルール';
$_lang['rule_create'] = 'ルールを作成';
$_lang['rule_desc'] = 'このフィールドに適用されるルールの種類';
$_lang['rule_description_desc'] = 'オプション。ルールの詳細';
$_lang['rule_err_ae'] = 'そのフィールドにはすでにルールが設定されています';
$_lang['rule_err_duplicate'] = 'ルールの複製中にエラーが発生しました。';
$_lang['rule_err_nf'] = 'ルールが見つかりません。';
$_lang['rule_err_ns'] = 'ルールが指定されていません。';
$_lang['rule_err_remove'] = 'ルールの削除中にエラーが発生しました。';
$_lang['rule_err_save'] = 'ルールの保存中にエラーが発生しました。';
$_lang['rule_remove'] = 'ルールを削除';
$_lang['rule_remove_confirm'] = '本当にこのルールを削除しますか？';
$_lang['rule_remove_multiple'] = '複数のルールを削除';
$_lang['rule_remove_multiple_confirm'] = '本当にこれらのルールを削除しますか？元には戻せません。';
$_lang['rule_update'] = 'ルールを編集';
$_lang['rule_value_desc'] = 'ルールに値を設定します。';
$_lang['rules'] = 'ルール';
$_lang['tab_title'] = 'タブのタイトル';
$_lang['tab_new'] = '新しいタブ';
$_lang['tab_visible'] = 'タブの表示／非表示';
$_lang['tv_default'] = '規定値';
$_lang['tv_label'] = 'テンプレート変数のラベル';
$_lang['tv_move'] = 'テンプレート変数をタブに移動';
$_lang['tv_visible'] = 'テンプレート変数の表示／非表示';
$_lang['usergroup'] = 'ユーザーグループ';
$_lang['usergroup_desc'] = 'オプション。設定すると、このルールをユーザーグループで指定されたユーザーのみに制限します。';