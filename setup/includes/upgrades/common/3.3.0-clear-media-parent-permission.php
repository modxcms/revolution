<?php

/**
 * Clear file_manager permission from the Media top menu parent.
 *
 * The parent should remain visible when the user can access any child
 * (Media Browser or Media Sources). file_manager stays on media/browser only.
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modMenu;

/** @var modMenu $mediaMenu */
$mediaMenu = $modx->getObject(modMenu::class, [
    'text' => 'media',
    'permissions' => 'file_manager',
]);
if ($mediaMenu instanceof modMenu) {
    $mediaMenu->set('permissions', '');
    if ($mediaMenu->save()) {
        $mediaMenu->rebuildCache('');
    }
}
