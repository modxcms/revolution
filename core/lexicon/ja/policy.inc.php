<?php
/**
 * Access Policy Japanese lexicon topic
 *
 * @language ja
 * @package modx
 * @subpackage lexicon
 * @author Nick http://smallworld.west-tokyo.com
 * @author shimojo http://www.priqia.com/
 * @author yamamoto http://kyms.jp
 */
$_lang['active_of'] = '[[+active]] of [[+total]]';
$_lang['active_permissions'] = 'Active Permissions';
$_lang['no_policy_option'] = ' (no policy) ';
$_lang['permission'] = '権限';
$_lang['permission_add'] = 'Add Permission';
$_lang['permission_add_template'] = 'Add Permission to Template';
$_lang['permission_err_ae'] = 'すでにこのポリシーに登録されている権限です';
$_lang['permission_err_nf'] = '不明な権限です';
$_lang['permission_err_ns'] = 'Permission not specified.';
$_lang['permission_err_remove'] = '権限を削除しようとしてエラーが発生しました';
$_lang['permission_err_save'] = '権限を保存しようとしてエラーが発生しました';
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
$_lang['policy_desc'] = 'Access policies are generic policies that restrict or enable certain actions with MODX.';
$_lang['policy_desc_name'] = 'The name of the Access Policy';
$_lang['policy_desc_description'] = 'Optional. A short description of the Access Policy';
$_lang['policy_desc_template'] = 'The Policy Template used for this Policy. Policies get their Permission lists from their Template.';
$_lang['policy_desc_lexicon'] = 'Optional. The Lexicon Topic that this Policy uses to translate the Permissions it owns.';
$_lang['policy_duplicate'] = 'ポリシーの複製';
$_lang['policy_duplicate_confirm'] = 'ポリシーと全てのデータを複製しますか？';
$_lang['policy_err_ae'] = 'A Policy already exists with the name `[[+name]]`. Please select another name.';
$_lang['policy_err_nf'] = 'Policy not found.';
$_lang['policy_err_ns'] = 'Policy not specified.';
$_lang['policy_err_remove'] = 'An error occurred while trying to remove the Policy.';
$_lang['policy_err_save'] = 'An error occurred while trying to save the Policy.';
$_lang['policy_management'] = 'アクセスポリシー';
$_lang['policy_management_msg'] = 'Access Policies manage how MODX handles permissions for specified actions.';
$_lang['policy_name'] = 'ポリシー名';
$_lang['policy_property_create'] = 'アクセスポリシープロパティを作成';
$_lang['policy_property_new'] = '新しいポリシープロパティ';
$_lang['policy_property_remove'] = 'アクセスポリシープロパティの削除';
$_lang['policy_property_specify_name'] = 'ポリシープロパティ名が空欄です。:';
$_lang['policy_remove'] = 'アクセスポリシーを削除';
$_lang['policy_remove_confirm'] = 'アクセスポリシーを削除しますか？';
$_lang['policy_remove_multiple'] = 'ポリシーを削除';
$_lang['policy_remove_multiple_confirm'] = 'Are you sure you want to remove these Access Policies? This is irreversible.';
$_lang['policy_update'] = 'アクセスポリシーを編集';
$_lang['policy_template'] = 'ポリシーのひな型';
$_lang['policy_template.desc'] = 'A Policy Template defines what Permissions will show up in the Permissions grid when editing a specific Policy. You can add or remove specific Permissions from this template below. Note that removing a Permission from a Template will remove it from any Policies that use this Template.';
$_lang['policy_template_create'] = 'ポリシーのひな型を作成';
$_lang['policy_template_desc_name'] = 'The name of the Access Policy Template';
$_lang['policy_template_desc_description'] = 'Optional. A short description of the Access Policy Template';
$_lang['policy_template_desc_lexicon'] = 'Optional. The Lexicon Topic that this Policy Template uses to translate the Permissions it owns.';
$_lang['policy_template_desc_template_group'] = 'The Policy Template Group to use. This is used when selecting Policies from a dropdown menu; usually they are filtered by template group. Select an appropriate group for your Policy Template.';
$_lang['policy_template_duplicate'] = 'ポリシーのひな型を複製する';
$_lang['policy_template_duplicate_confirm'] = 'Are you sure you want to duplicate this Policy Template?';
$_lang['policy_template_err_ae'] = 'A Policy Template already exists with the name `[[+name]]`. Please select another name.';
$_lang['policy_template_err_nf'] = 'Policy Template not found.';
$_lang['policy_template_err_ns'] = 'Policy Template not specified.';
$_lang['policy_template_err_remove'] = 'An error occurred while trying to remove the Policy Template.';
$_lang['policy_template_err_save'] = 'An error occurred while trying to save the Policy Template.';
$_lang['policy_template_remove'] = 'Remove Policy Template';
$_lang['policy_template_remove_confirm'] = 'Are you sure you want to remove this Policy Template? It will remove all Policies attached to this Template as well - this could break your MODX installation if any active Policies are attached to this Template.';
$_lang['policy_template_remove_multiple'] = 'Remove Selected Policy Templates';
$_lang['policy_template_remove_multiple_confirm'] = 'Are you sure you want to remove these Policy Templates? It will remove all Policies attached to these Templates as well - this could break your MODX installation if any active Policies are attached to these Templates.';
$_lang['policy_template_update'] = 'ポリシーのひな型を編集';
$_lang['policy_templates'] = 'ポリシーのひな型';
$_lang['policy_templates.intro_msg'] = 'This is a list of Policy Templates, which define lists of Permissions that are checked or unchecked in specific Policies.';
$_lang['template_group'] = 'Template Group';