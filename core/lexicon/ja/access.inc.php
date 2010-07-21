<?php
/**
 * Access Japanese lexicon topic
 *
 * @language ja
 * @package modx
 * @subpackage lexicon
 * @author Nick http://smallworld.west-tokyo.com
 * @author shimojo http://www.priqia.com/
 * @author yamamoto http://kyms.jp
 */
$_lang['access_category_management_msg'] = 'Manage User Group member access to Elements via Categories and optionally apply access policies.';
$_lang['access_category_err_ae'] = 'An ACL for that Category already exists';
$_lang['access_category_err_nf'] = 'Category ACL not found.';
$_lang['access_category_err_ns'] = 'Category ACL not specified.';
$_lang['access_category_err_remove'] = 'An error occurred while trying to remove the Category ACL.';
$_lang['access_category_remove'] = 'Remove Category Access';
$_lang['access_category_update'] = 'Update Category Access';
$_lang['access_confirm_remove'] = '本当にこのセキュリティアクセスコントロールコードを削除しますか？';
$_lang['access_context_management_msg'] = 'ユーザーグループメンバーをコンテキストとオプションアクセスポリシーへアクセス可能にする';
$_lang['access_context_err_ae'] = 'An ACL for that Context already exists';
$_lang['access_context_err_nf'] = 'Context ACL not found.';
$_lang['access_context_err_ns'] = 'Context ACL not specified.';
$_lang['access_context_err_remove'] = 'An error occurred while trying to remove the Context ACL.';
$_lang['access_context_remove'] = 'Remove Context Access';
$_lang['access_context_update'] = 'Update Context Access';
$_lang['access_err_ae'] = 'ACLは既に存在しています';
$_lang['access_err_create_md'] = 'ACLを生成できません。データが消失しています';
$_lang['access_err_nf'] = 'ACLを特定できませんでした';
$_lang['access_err_remove'] = 'ACL削除中のエラー';
$_lang['access_err_save'] = 'ACL保存中のエラー';
$_lang['access_grid_empty'] = '表示するACLが存在しません';
$_lang['access_grid_paginate'] = '{0} - {1} 中 {2}のACLを表示';
$_lang['access_permissions'] = 'アクセス許可';
$_lang['access_permissions_add_document_group'] = 'ドキュメントグループの作成';
$_lang['access_permissions_add_user_group'] = 'ユーザーグループの作成';
$_lang['access_permissions_documents_in_group'] = '<strong>グループ内ドキュメント:</strong> ';
$_lang['access_permissions_documents_tab'] = 'ドキュメントグループの設定を一覧することができます。グループの作成・リネーム・削除もここで操作します。 また、ドキュメントがどのグループに属しているかを閲覧することもできます(ドキュメント名を表示するには、idの上にマウスポインタを乗せてください)。 ドキュメントをグループに追加したり、グループから削除するには、ドキュメントを直接編集してください。';
$_lang['access_permissions_document_groups'] = 'ドキュメントグループ';
$_lang['access_permissions_introtext'] = 'ユーザーグループとドキュメントグループを管理します。ここでユーザーグループまたはドキュメントグループを作ると、ユーザ編集画面またはドキュメント編集画面で所属グループを選択できるようになります。また、ユーザーグループとドキュメントグループをここで関連付けることができます。つまり誰がどのドキュメントをという関連付けをここで行ないます。利用できる機能を制限・管理する「ロール」と違い、当機能ではコンテンツ(ドキュメント)対象の権限を管理します。<br />ロール管理とは区別されているため、同じユーザーグループ内に「強い権限を持つユーザー」や「限定された権限を持つユーザー」を自由に混在させることができます。<br />※「ツール」→「グローバル設定」→「詳細設定」の「アクセス権限設定の使用」を「いいえ」にすると、グループ設定を経由しない運用になります。信用できる少数のメンバーでシンプルにサイトを管理運用したい場合は参考にしてください。';
$_lang['access_permissions_links'] = 'グループリンク';
$_lang['access_permissions_links_tab'] = 'ドキュメントグループとユーザーグループを関連付けます。';
$_lang['access_permissions_no_documents_in_group'] = '無し';
$_lang['access_permissions_no_users_in_group'] = '無し';
$_lang['access_permissions_off'] = '<span class="warning">アクセス権限設定(グローバル設定→詳細設定)が無効になっています。</span> アクセス権限を有効にしない限り、全ての変更作業は無視されます。。';
$_lang['access_permissions_users_in_group'] = '<strong>グループ内ユーザー:</strong> ';
$_lang['access_permissions_users_tab'] = 'ユーザーグループの設定一覧を表示します。グループの作成・リネーム・削除もここで操作します。また、ユーザーがどのグループのメンバーになっているかを閲覧することができます。ユーザーをグループに追加したり、グループから削除するには、個々のユーザー設定を直接編集してください。<br />※管理者(ロールID 1が割り当てられているユーザー)は常に全てのドキュメントにアクセスすることができるため、管理者をグループに追加する必要はありません。';
$_lang['access_permissions_user_group'] = 'ユーザーグループ:';
$_lang['access_permissions_user_groups'] = 'ユーザーグループ:';
$_lang['access_permissions_user_group_access'] = 'Document groups this user group has access to:';
$_lang['access_permissions_user_message'] = 'このユーザーを所属させたいユーザーグループを選択してください:';
$_lang['access_permission_denied'] = 'このドキュメントにアクセスする権限がありません。';
$_lang['access_permission_parent_denied'] = 'ここにドキュメントを作成する権限がありません';
$_lang['access_policy_err_nf'] = 'アクセスポリシーが見つかりませんでした。.';
$_lang['access_policy_err_ns'] = 'アクセスポリシーが指定されていません。';
$_lang['access_policy_grid_empty'] = '表示するポリシーがありません。';
$_lang['access_policy_grid_paginate'] = '{0} - {1} 中 {2}のポリシーを表示';
$_lang['access_resourcegroup_management_msg'] = 'ユーザーグループに対して、リソースグループへのアクセス権および設定してください。';
$_lang['access_rgroup_err_ae'] = 'An ACL for that Resource Group already exists';
$_lang['access_rgroup_err_nf'] = 'Resource Group ACL not found.';
$_lang['access_rgroup_err_ns'] = 'Resource Group ACL not specified.';
$_lang['access_rgroup_err_remove'] = 'An error occurred while trying to remove the Resource Group ACL.';
$_lang['access_rgroup_remove'] = 'Remove Resource Group Access';
$_lang['access_rgroup_update'] = 'Update Resource Group Access';
$_lang['access_to_contexts'] = 'コンテキストへアクセス';
$_lang['access_to_resource_groups'] = 'リソースグループへアクセス';
$_lang['access_type_err_ns'] = 'ターゲットタイプもしくはIDタイプが特定されていません';
$_lang['acl_add'] = 'Add Access Control';
$_lang['authority'] = '特権レベル';
$_lang['authority_err_ns'] = 'Please specify a Minimum Role.';
$_lang['category'] = 'カテゴリー';
$_lang['category_add'] = 'Add Category';
$_lang['filter_by_context'] = 'コンテキストで絞り込む';
$_lang['filter_by_policy'] = 'ポリシーで絞り込む';
$_lang['filter_by_resource_group'] = 'リソースグループで絞り込む';
$_lang['filter_by_category'] = 'カテゴリーで絞り込む';
$_lang['resource_group'] = 'リソースグループ';
$_lang['resource_group_add'] = 'リソースグループの追加';
$_lang['resource_group_access_remove'] = 'Remove Resource from Group';
$_lang['resource_group_access_remove_confirm'] = 'リソースグループからリソースを削除しますか？';
$_lang['resource_group_create'] = 'リソースグループの作成';
$_lang['resource_group_err_ae'] = '同名のリソースグループが存在します。';
$_lang['resource_group_err_create'] = 'リソースグループの作成中にエラーが発生しました。';
$_lang['resource_group_err_nf'] = 'リソースグループが見つかりませんでした。';
$_lang['resource_group_err_ns'] = 'リソースグループが指定されていません。';
$_lang['resource_group_err_remove'] = 'リソースグループの削除中にエラーが発生しました。';
$_lang['resource_group_remove'] = 'リソースグループの削除';
$_lang['resource_group_remove_confirm'] = 'このリソースグループを削除しますか？';
$_lang['resource_group_resource_err_ae'] = 'The resource is already a part of that resource group.';
$_lang['resource_group_resource_err_create'] = 'リソースをリソースグループへ配置中にエラーが発生しました。';
$_lang['resource_group_resource_err_nf'] = 'The resource is not a part of that resource group.';
$_lang['resource_group_resource_err_remove'] = 'リソースをリソースグループから削除中にエラーが発生しました。';
$_lang['resource_group_untitled'] = '無名のリソースグループ';
$_lang['roles_msg'] = 'A role is, by definition, a position or status one holds within a certain situation. They can be used to group Users into a position or status within a User Group. Roles in MODx also have what is called "Authority". This is a number value that can be any valid integer. Authority levels are "inheritable downward", in the sense that a Role with Authority 1 will inherit any and all Group Policies assigned to itself, and to any Roles with higher Authority level than 1.';
$_lang['user_group_category_access'] = 'Element Category Access';
$_lang['user_group_category_access_msg'] = 'Here you can set which Elements this User Group can access by the Categories the Elements are in.';
$_lang['user_group_category_err_ae'] = 'User Group already has access to that Category.';
$_lang['user_group_category_remove_confirm'] = 'Are you sure you want to remove this Category from this User Group?';
$_lang['user_group_context_access'] = 'コンテキストアクセス';
$_lang['user_group_context_access_msg'] = 'ユーザーグループにコンテキストへのアクセス権を加えることができます。';
$_lang['user_group_context_err_ae'] = 'ユーザーグループは既にコンテキストへのアクセス権を持っています。';
$_lang['user_group_context_remove_confirm'] = 'ユーザーグループからコンテキストを削除しますか？';
$_lang['user_group_resourcegroup_access'] = 'リソースグループのアクセス';
$_lang['user_group_resourcegroup_access_msg'] = 'ユーザーグループにリソースグループへのアクセス権を加えることができます。';
$_lang['user_group_resourcegroup_err_ae'] = 'ユーザーグループは既にそのリソースグループへのアクセス権があります。';
$_lang['user_group_resourcegroup_remove_confirm'] = 'ユーザーグループからこのリソースグループを削除しますか？';
$_lang['user_group_user_access_msg'] = 'ユーザーグループへ加えるユーザーを選択してください。';