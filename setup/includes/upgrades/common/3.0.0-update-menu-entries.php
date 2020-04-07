<?php
/**
 * Common upgrade script for modify modMenu entries
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modMenu;

/** @var modMenu $formCustomization */
$formCustomization = $modx->getObject(modMenu::class, ['text' => 'bespoke_manager']);
if ($formCustomization) {
    $formCustomization->set('text', 'form_customization');
    $formCustomization->set('description', 'form_customization_desc');
    $formCustomization->save();
}
