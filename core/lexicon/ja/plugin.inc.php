<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'イベント';
$_lang['events'] = 'イベント';
$_lang['plugin'] = 'プラグイン';
$_lang['plugin_add'] = 'プラグインを追加';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'プラグイン設定';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'プラグインを削除しますか？';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'プラグインを複製しますか？';
$_lang['plugin_err_create'] = 'プラグインの作成中にエラーが発生しました。';
$_lang['plugin_err_ae'] = '[[+name]]という名前のプラグインがすでに存在します。';
$_lang['plugin_err_invalid_name'] = 'プラグイン名は無効です。';
$_lang['plugin_err_duplicate'] = 'An error occurred while trying to duplicate the plugin.';
$_lang['plugin_err_nf'] = 'プラグインが見つかりません。';
$_lang['plugin_err_ns'] = 'プラグインが指定されていません。';
$_lang['plugin_err_ns_name'] = 'プラグインの名前を指定してください。';
$_lang['plugin_err_remove'] = 'An error occurred while trying to delete the plugin.';
$_lang['plugin_err_save'] = 'プラグインの保存中にエラーが発生しました。';
$_lang['plugin_event_err_duplicate'] = 'プラグインのイベントを複製中にエラーが発生しました。';
$_lang['plugin_event_err_nf'] = 'プラグインのイベントが見つかりませでした。';
$_lang['plugin_event_err_ns'] = 'プラグインのイベントを特定できませんでした。';
$_lang['plugin_event_err_remove'] = 'An error occurred while trying to delete the plugin event.';
$_lang['plugin_event_err_save'] = 'プラグインのイベントを保存中にエラーが発生しました。';
$_lang['plugin_event_msg'] = 'このプラグインが使用するイベントを選択してください。';
$_lang['plugin_event_plugin_remove_confirm'] = 'Are you sure you want to delete this plugin from this event?';
$_lang['plugin_lock'] = 'Plugin locked for editing';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'このプラグインはロックされてます。';
$_lang['plugin_management_msg'] = '<h3 style="font-weight:bold;">プラグインの管理</h3><p>編集したいプラグインを選択します。</p>';
$_lang['plugin_name_desc'] = 'プラグインの名前を設定します。';
$_lang['plugin_new'] = 'Create Plugin';
$_lang['plugin_priority'] = 'イベント発生時のプラグインの実行順を編集';
$_lang['plugin_properties'] = 'プラグインプロパティ';
$_lang['plugin_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Plugin</em> as well as its content. The content must be PHP, either placed in the <em>Plugin Code</em> field below or in a static external file. The PHP code entered runs in response to one or more MODX System Events that you specify.';
$_lang['plugins'] = 'プラグイン';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];
