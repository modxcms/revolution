<?php
/**
 * Common upgrade script for modify modMenu entries
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modMenu;

// Update menu icons
$menuItemsUpdateIcons = [
    'site' => '<i class="icon-file-text-o icon"></i>',
    'media' => '<i class="icon-file-image-o icon"></i>',
    'components' => '<i class="icon-cube icon"></i>',
    'manage' => '<i class="icon-sliders icon"></i>',
    'user' => '<span id="user-avatar">{$userImage}</span><span id="user-username">{$username}</span>',
    'admin' => '<i class="icon-gear icon"></i>',
    'about' => '<i class="icon-question-circle icon"></i>',
];

foreach ($menuItemsUpdateIcons as $key => $value) {
    /** @var modMenu $menuItem */

    $menuItem = $modx->getObject(modMenu::class, ['text' => $key]);
    if ($menuItem instanceof modMenu) {
        $menuItem->set('description', '');
        $menuItem->set('icon', $value);

        if ($menuItem->save()) {
            $this->runner->addResult(
                modInstallRunner::RESULT_SUCCESS,
                sprintf($messageTemplate, 'ok', $this->install->lexicon('menu_update_success', ['text' => $key]))
            );
        } else {
            $this->runner->addResult(
                modInstallRunner::RESULT_WARNING,
                sprintf($messageTemplate, 'warning', $this->install->lexicon('menu_update_failed', ['text' => $key]))
            );
        }
    }
}

// Remove menu items
$menuItemsRemove = [
    'import_site',
    'import_resources',
];

foreach ($menuItemsRemove as $key) {
    /** @var modMenu $menuItem */

    $menuItem = $modx->getObject(modMenu::class, ['text' => $key]);
    if ($menuItem instanceof modMenu) {
        if ($menuItem->remove()) {
            $this->runner->addResult(
                modInstallRunner::RESULT_SUCCESS,
                sprintf($messageTemplate, 'ok', $this->install->lexicon('menu_remove_success', ['text' => $key]))
            );
        } else {
            $this->runner->addResult(
                modInstallRunner::RESULT_WARNING,
                sprintf($messageTemplate, 'warning', $this->install->lexicon('menu_remove_failed', ['text' => $key]))
            );
        }
    }
}
