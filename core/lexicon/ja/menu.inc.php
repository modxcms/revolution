<?php
/**
 * Menu English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['action'] = 'アクション';
$_lang['action_desc'] = 'The controller path to use for this menu item. The path to the controller is built by prefixing the Namespace path, controllers, and manager theme with this value. （Ex: user/update for core Namespace goes to [core_namespace_path]controllers/ [mgr_theme]/user/update.class.php ）';
$_lang['description_desc'] = 'The text, or lexicon key, that will be used for rendering the description text of this page in the menu.';
$_lang['handler'] = 'ハンドラー';
$_lang['handler_desc'] = '（Optional） If set, will not use the action field, but instead run this Javascript instead when the menu item is clicked.';
$_lang['icon'] = 'アイコン';
$_lang['icon_desc'] = 'An optional icon/markup.';
$_lang['lexicon_key'] = 'レキシコンキー';
$_lang['lexicon_key_desc'] = 'The text, or lexicon key, that will be used for rendering the title text of this page in the menu.';
$_lang['menu_confirm_remove'] = 'Are you sure you want to delete the menu: "[[+menu]]"?<br />NOTE: Any nested menus will also be deleted!';
$_lang['menu_err_ae'] = 'この名前は既に使用されています。他の名前を指定してください。';
$_lang['menu_err_nf'] = 'メニューが見つかりませんでした。';
$_lang['menu_err_ns'] = 'メニューが指定されていません。';
$_lang['menu_err_remove'] = 'An error occurred while trying to delete the menu.';
$_lang['menu_err_save'] = 'メニューにアクションを定義中にエラーが発生しました。';
$_lang['menu_parent'] = '親メニュー';
$_lang['menu_parent_err_ns'] = '親メニューを指定してください';
$_lang['menu_parent_err_nf'] = '親メニューが見つかりません。';
$_lang['menu_top'] = 'Main Menu';
$_lang['menus'] = 'Menus';
$_lang['namespace'] = 'ネームスペース';
$_lang['namespace_desc'] = 'The Namespace which this menu item is based on. This will determine the path for the controller that is loaded.';
$_lang['parameters'] = 'パラメータ';
$_lang['parameters_desc'] = 'Any request parameters you want appended to the result URL when clicking this menu. （Ex: &expire=1）';
$_lang['permissions'] = 'Permission';
$_lang['permissions_desc'] = 'A permission key required to load this menu item.';
$_lang['topmenu'] = 'Main Menu';
$_lang['topmenu_desc'] = 'This allows you to associate actions with menu items in the main menu bar of the MODX manager. Simply place menus where you would like them in their respective positions.';
