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
    $newData = $formCustomization->toArray();
    $newData['text'] = 'form_customization';
    $newData['description'] = 'form_customization_desc';

    $formCustomization->remove();

    $newFormCustomization = $modx->newObject(modMenu::class);
    $newFormCustomization->fromArray($newData, '', true, true);
    $newFormCustomization->save();
}

