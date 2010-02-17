<?php
/**
 * Removes a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('delete_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset');

/* grab the modPropertySet */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
$set = $modx->getObject('modPropertySet',$_POST['id']);
if (empty($set)) return $modx->error->failure($modx->lexicon('propertyset_err_nf'));

/* remove set */
if ($set->remove() === false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_remove'));
}

return $modx->error->success('',$set);