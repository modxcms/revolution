<?php
/**
 * Menu English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['action'] = 'Action';
$_lang['action_desc'] = 'The controller path to use for this menu item. The path to the controller is built by prefixing the Namespace path, controllers, and manager theme with this value. (Ex: user/update for core Namespace goes to [core_namespace_path]controllers/ [mgr_theme]/user/update.class.php )';
$_lang['description_desc'] = 'The text, or lexicon key, that will be used for rendering the description text of this page in the menu.';
$_lang['handler'] = 'Handler';
$_lang['handler_desc'] = '(Optional) If set, will not use the action field, but instead run this JavaScript instead when the menu item is clicked.';
$_lang['icon'] = 'Icon';
$_lang['icon_desc'] = 'An optional icon/markup.';
$_lang['lexicon_key'] = 'Lexicon Key';
$_lang['lexicon_key_desc'] = 'The text, or lexicon key, that will be used for rendering the title text of this page in the menu.';
$_lang['menu_confirm_remove'] = 'Are you sure you want to delete the menu: "[[+menu]]"?<br />NOTE: Any nested menus will also be deleted!';
$_lang['menu_err_ae'] = 'A menu already exists with that name. Please specify a different text value.';
$_lang['menu_err_nf'] = 'Menu not found!';
$_lang['menu_err_ns'] = 'No menu specified!';
$_lang['menu_err_remove'] = 'An error occurred while trying to delete the menu.';
$_lang['menu_err_save'] = 'An error occurred while trying to save the menu.';
$_lang['menu_parent'] = 'Parent Menu';
$_lang['menu_parent_err_ns'] = 'Parent menu not specified!';
$_lang['menu_parent_err_nf'] = 'Parent menu not found!';
$_lang['menu_top'] = 'Main Menu';
$_lang['menus'] = 'Menus';
$_lang['namespace'] = 'Namespace';
$_lang['namespace_desc'] = 'The Namespace which this menu item is based on. This will determine the path for the controller that is loaded.';
$_lang['parameters'] = 'Parameters';
$_lang['parameters_desc'] = 'Any request parameters you want appended to the result URL when clicking this menu. (Ex: &expire=1)';
$_lang['permissions'] = 'Permission';
$_lang['permissions_desc'] = 'A permission key required to load this menu item.';
$_lang['topmenu'] = 'Main Menu';
$_lang['topmenu_desc'] = 'This allows you to associate actions with menu items in the main menu bar of the MODX manager. Simply place menus where you would like them in their respective positions.';
