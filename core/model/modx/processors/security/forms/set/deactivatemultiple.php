<?php
/**
 * Deactivate multiple FC Sets
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

    $set->set('active',false);

    if ($set->save() === false) {
        return $modx->error->failure($modx->lexicon('set_err_save'));
    }
}

return $modx->error->success();
