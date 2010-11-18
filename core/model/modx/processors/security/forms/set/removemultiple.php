<?php
/**
 * Remove multiple FC sets
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

if (empty($scriptProperties['sets'])) {
    return $modx->error->failure($modx->lexicon('set_err_ns'));
}

$setIds = explode(',',$scriptProperties['sets']);

foreach ($setIds as $setId) {
    $set = $modx->getObject('modFormCustomizationSet',$setId);
    if ($set == null) continue;

    if ($set->remove() === false) {
        return $modx->error->failure($modx->lexicon('set_err_remove'));
    }
}

return $modx->error->success();
