<?php
/**
 * Gets a list of resource groups associated to a TV.
 *
 * @param integer $tv The ID of the TV
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.template.tv.resourcegroup
 */
$modx->lexicon->load('tv');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

if (isset($_REQUEST['tv']) && $_REQUEST['tv'] != 0) {
    $tv = $modx->getObject('modTemplateVar',$_REQUEST['tv']);
    if ($tv == null) return $modx->error->failure($modx->lexicon('tv_err_nf'));
}

$c = $modx->newQuery('modResourceGroup');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if (isset($_REQUEST['limit'])) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$groups = $modx->getCollection('modResourceGroup',$c);
$count = $modx->getCount('modResourceGroup');

$ts = array();
foreach ($groups as $group) {
    if (isset($_REQUEST['tv']) && $_REQUEST['tv'] != 0) {
        $rgtv = $modx->getObject('modTemplateVarResourceGroup',array(
            'tmplvarid' => $tv->get('id'),
            'documentgroup' => $group->get('id'),
        ));
    } else $rgtv = null;

    if ($rgtv != null) {
        $group->set('access',true);
    } else {
        $group->set('access',false);
    }
    $ta = $group->toArray();
    $ta['menu'] = array(
    );
    $ts[] = $ta;
}

return $this->outputArray($ts,$count);