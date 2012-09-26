<?php
/**
* Access Policy Japanese lexicon topic
*
* @language ja
* @package modx
* @subpackage lexicon
* @author honda http://kogus.org 2012-09-21
* @author Nick http://smallworld.west-tokyo.com
* @author shimojo http://www.priqia.com/
* @author yamamoto http://kyms.jp
*/
$_lang['active_of'] = '[[+active]] of [[+total]]';
$_lang['active_permissions'] = '有効な権限';
$_lang['no_policy_option'] = ' (ポリシーなし) ';
$_lang['permission'] = '権限';
$_lang['permission_add'] = '権限を追加';
$_lang['permission_add_template'] = 'テンプレートに権限を付加';
$_lang['permission_err_ae'] = 'すでにこのポリシーに登録されている権限です。';
$_lang['permission_err_nf'] = '不明な権限です。';
$_lang['permission_err_ns'] = '権限が指定されていません。';
$_lang['permission_err_remove'] = '権限を削除しようとしてエラーが発生しました。';
$_lang['permission_err_save'] = '権限を保存しようとしてエラーが発生しました。';
$_lang['permission_new'] = '新しい権限';
$_lang['permission_remove'] = '権限を削除';
$_lang['permission_remove_confirm'] = 'この権限を本当に削除してよいですか？';
$_lang['permission_update'] = '権限を編集';
$_lang['permissions'] = '権限';
$_lang['permissions_desc'] = 'さまざまな権限を束ねた「アクセスポリシー」をここで管理します。アクセスポリシーはユーザーグループに割り当てることができます。';
$_lang['policies'] = 'アクセスポリシー';
$_lang['policy'] = 'アクセスポリシー';
$_lang['policy_create'] = 'アクセスポリシーを作成';
$_lang['policy_data'] = 'ポリシーデータ';
$_lang['policy_desc'] = 'アクセスポリシーは、MODXの特定のアクションの許可や制限を行います。';
$_lang['policy_desc_name'] = 'アクセスポリシーの名前';
$_lang['policy_desc_description'] = '省略可。このアクセスポリシーの簡単な説明。';
$_lang['policy_desc_template'] = 'このアクセスポリシーで使用する、ポリシーテンプレートを選択してください。ポリシーで設定可能な権限は、ここで選択したひな型から読み込まれます。';
$_lang['policy_desc_lexicon'] = '省略可。このアクセスポリシーの権限を翻訳する際に使用するレキシコントピック。';
$_lang['policy_duplicate'] = 'ポリシーの複製';
$_lang['policy_duplicate_confirm'] = 'ポリシーと全てのデータを複製しますか？';
$_lang['policy_err_ae'] = '`[[+name]]`という名前のアクセスポリシーはすでに存在しています。別の名前を指定してください。';
$_lang['policy_err_nf'] = '不明なポリシーです。';
$_lang['policy_err_ns'] = 'ポリシーが指定されていません。';
$_lang['policy_err_remove'] = 'ポリシーの削除時に、エラーが発生しました。';
$_lang['policy_err_save'] = 'ポリシーの保存時に、エラーが発生しました。';
$_lang['policy_export'] = 'ポリシーのエクスポート';
$_lang['policy_import'] = 'ポリシーのインポート';
$_lang['policy_import_msg'] = 'ポリシーとしてインポートするXMLファイルを選択してください。ファイルは有効なポリシーXMLフォーマットである必要があります。';
$_lang['policy_management'] = 'アクセスポリシー';
$_lang['policy_management_msg'] = 'アクセスポリシーは、MODXの様々なアクションに対する権限を管理します。';
$_lang['policy_name'] = 'ポリシー名';
$_lang['policy_property_create'] = 'アクセスポリシープロパティを作成';
$_lang['policy_property_new'] = '新しいポリシープロパティ';
$_lang['policy_property_remove'] = 'アクセスポリシープロパティの削除';
$_lang['policy_property_specify_name'] = 'ポリシープロパティ名を指定してください。:';
$_lang['policy_remove'] = 'アクセスポリシーを削除';
$_lang['policy_remove_confirm'] = 'アクセスポリシーを削除しますか？';
$_lang['policy_remove_multiple'] = 'ポリシーを削除';
$_lang['policy_remove_multiple_confirm'] = 'このアクセスポリシーを削除してよろしいですか？　この処理は取り消しできません。';
$_lang['policy_update'] = 'アクセスポリシーを編集';
$_lang['policy_template'] = 'ポリシーテンプレート';
$_lang['policy_template.desc'] = 'ポリシーテンプレートは、個々のアクセスポリシーで設定可能なアクセス許可のひな型を定義します。ここでは、テンプレートに特定の権限を追加や削除が行えます。なお、テンプレートから削除された権限は、そのテンプレートを使用する全てのアクセスポリシーからも削除されるため、注意してください。';
$_lang['policy_template_create'] = 'ポリシーテンプレートを作成';
$_lang['policy_template_desc_name'] = 'このポリシーテンプレートの名称';
$_lang['policy_template_desc_description'] = '省略可。このポリシーテンプレートの簡単な説明。';
$_lang['policy_template_desc_lexicon'] = '省略可。このポリシーテンプレートの権限を翻訳する際に使用するレキシコントピック。';
$_lang['policy_template_desc_template_group'] = 'ポリシーテンプレートを使用するグループ。';
$_lang['policy_template_duplicate'] = 'ポリシーテンプレートを複製する';
$_lang['policy_template_duplicate_confirm'] = 'このポリシーテンプレートを複製してよろしいですか？';
$_lang['policy_template_err_ae'] = '`[[+name]]`という名前のポリシーテンプレートはすでに存在します。他の名前を指定してください。';
$_lang['policy_template_err_nf'] = '不明なポリシーテンプレートです。';
$_lang['policy_template_err_ns'] = 'ポリシーテンプレートを指定してください。';
$_lang['policy_template_err_remove'] = 'ポリシーテンプレートの削除時、エラーが発生しました。';
$_lang['policy_template_err_save'] = 'ポリシーテンプレートの保存時、エラーが発生しました。';
$_lang['policy_template_export'] = 'ポリシーテンプレートをエクスポート';
$_lang['policy_template_import'] = 'ポリシーテンプレートをインポート';
$_lang['policy_template_import_msg'] = 'インポートするひな型のファイルを選択してください。ファイルは、';
$_lang['policy_template_remove'] = 'ポリシーテンプレートを削除';
$_lang['policy_template_remove_confirm'] = 'このポリシーテンプレート削除してよろしいですか？　ポリシーテンプレートを削除すると、そのテンプレートを使用するアクセスポリシーも同時に削除されます。全ての有効なアクセスポリシーがこのテンプレートを使用している場合、MODXが動作しなくなる可能性があります。';
$_lang['policy_template_remove_multiple'] = '選択しているポリシーテンプレートを削除';
$_lang['policy_template_remove_multiple_confirm'] = 'これらのポリシーテンプレート削除してよろしいですか？　ポリシーテンプレートを削除すると、そのテンプレートを使用するアクセスポリシーも同時に削除されます。全ての有効なアクセスポリシーがこれらのテンプレートを使用している場合、MODXが動作しなくなる可能性があります。';
$_lang['policy_template_update'] = 'ポリシーテンプレートを編集';
$_lang['policy_templates'] = 'ポリシーテンプレート';
$_lang['policy_templates.intro_msg'] = '個々のアクセスポリシーで指定可能な権限は、このポリシーテンプレートから読み込まれます。';
$_lang['template_group'] = 'テンプレートグループ';