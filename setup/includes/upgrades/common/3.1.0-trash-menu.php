<?php

/**
 * Scripts add the new trash menu
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modMenu;

$trashMenu = $modx->getObject(modMenu::class, ['text' => 'trash']);
if (!$trashMenu) {
    $trashMenu = $modx->newObject(modMenu::class);
    $trashMenu->fromArray([
        'menuindex' => 4,
        'text' => 'trash',
        'description' => 'trash_desc',
        'parent' => 'site',
        'permissions' => 'menu_trash',
        'action' => 'resource/trash',
    ], '', true, true);
    $trashMenu->save();
}
