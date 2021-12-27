<?php
/**
 * Common upgrade script for modify modMenu entries
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modMenu;

$menu = [
    'site' => '<i class="icon-file-text-o icon icon-large"></i>',
    'media' => '<i class="icon-file-image-o icon icon-large"></i>',
    'components' => '<i class="icon-cube icon icon-large"></i>',
    'manage' => '<i class="icon-sliders icon icon-large"></i>',
    'user' => '<span id="user-avatar">{$userImage}</span><span id="user-username">{$username}</span>',
    'admin' => '<i class="icon-gear icon icon-large"></i>',
    'about' => '<i class="icon-question-circle icon icon-large"></i>',
];

foreach ($menu as $key => $value) {
    /** @var modMenu $menu_item */

    $menu_item = $modx->getObject(modMenu::class, ['text' => $key]);
    if ($menu_item instanceof modMenu) {
        $menu_item->set('description', '');
        $menu_item->set('icon', $value);
        $menu_item->save();
    }
}

$removed = [
    'import_site',
    'import_resources',
];

foreach ($removed as $key) {
    $menu_item = $modx->getObject(modMenu::class, ['text' => $key]);
    if ($menu_item instanceof modMenu) {
        $menu_item->remove();
    }
}
