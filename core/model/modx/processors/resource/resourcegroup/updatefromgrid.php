<?php
/**
 * Assign or unassigns a resource group to a resource.
 *
 * @param integer $id The resource group to assign to.
 * @param integer $resource The modResource ID to associate with.
 * @param boolean $access Either true or false whether the resource has access
 * to the group specified.
 *
 * @package modx
 * @subpackage processors.resource.resourcegroup
 */
$modx->lexicon->load('resource');

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['resource'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject('modResource',$_DATA['resource']);
if ($resource == null) return $modx->error->failure($modx->lexicon('resource_err_nf'));

if (!isset($_DATA['id'])) return $modx->error->failure($modx->lexicon('resource_group_err_ns'));
$rg = $modx->getObject('modResourceGroup',$_DATA['id']);
if ($rg == null) return $modx->error->failure($modx->lexicon('resource_group_err_nf'));

$rgr = $modx->getObject('modResourceGroupResource',array(
    'document' => $resource->get('id'),
    'document_group' => $rg->get('id'),
));

if ($_DATA['access'] == true && $rgr != null) {
    return $modx->error->failure($modx->lexicon('resource_group_resource_err_ae'));
}
if ($_DATA['access'] == false && $rgr == null) {
    return $modx->error->failure($modx->lexicon('resource_group_resource_err_nf'));
}
if ($_DATA['access'] == true) {
    $rgr = $modx->newObject('modResourceGroupResource');
    $rgr->set('document',$resource->get('id'));
    $rgr->set('document_group',$rg->get('id'));
    $rgr->save();
} else {
    $rgr->remove();
}

return $modx->error->success();