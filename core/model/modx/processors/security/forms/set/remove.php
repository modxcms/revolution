<?php
/**
 * Remove a FC Set
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('set_err_ns'));
$set = $modx->getObject('modFormCustomizationSet',$scriptProperties['id']);
if ($set == null) return $modx->error->failure($modx->lexicon('set_err_nf'));

if ($set->remove() === false) {
    return $modx->error->failure($modx->lexicon('set_err_remove'));
}

return $modx->error->success('',$set);
