<?php
/**
 * Set the service to 1 for OnInitCulture
 */

/** @var modEvent $onInitCulture */
$onInitCulture = $modx->getObject('modEvent', array('name' => 'OnInitCulture'));
if ($onInitCulture) {
    $onInitCulture->set('service', 1);
    $onInitCulture->save();
}
