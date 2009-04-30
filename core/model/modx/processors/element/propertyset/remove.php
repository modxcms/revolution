<?php
/**
 * Removes a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
$modx->lexicon->load('propertyset');

/* if no id specified */
if (!isset($_POST['id']) || $_POST['id'] == '') {
    return $modx->error->failure($modx->lexicon('propertyset_err_ns'));
}
/* grab the modPropertySet */
$set = $modx->getObject('modPropertySet',$_POST['id']);
if ($set == null) {
    return $modx->error->failure($modx->lexicon('propertyset_err_nfs',array(
        'id' => $_POST['id'],
    )));
}
if (!isset($set) || $set == null) {
    return $modx->error->failure($modx->lexicon('propertyset_err_nfs',array('id' => $_REQUEST['id'])));
}

/* remove set */
if ($set->remove() === false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_remove'));
}

return $modx->error->success();