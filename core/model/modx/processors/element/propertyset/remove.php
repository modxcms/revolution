<?php
/**
 * Removes a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
if (!$modx->hasPermission('delete')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset');

/* grab the modPropertySet */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
$set = $modx->getObject('modPropertySet',$_POST['id']);
if (!$set) {
    return $modx->error->failure($modx->lexicon('propertyset_err_nfs',array(
        'id' => $_POST['id'],
    )));
}

/* remove set */
if ($set->remove() === false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_remove'));
}

return $modx->error->success('',$set);