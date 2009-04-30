<?php
/**
 * Grabs a list of templates associated with the TV
 *
 * @param integer $tv The ID of the TV
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.template.tv
 */
$modx->lexicon->load('tv');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'templatename';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

if (isset($_REQUEST['tv']) && $_REQUEST['tv'] != 0) {
    $tv = $modx->getObject('modTemplateVar',$_REQUEST['tv']);
    if ($tv == null) return $modx->error->failure($modx->lexicon('tv_err_nf'));
}

$c = $modx->newQuery('modTemplate');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if (isset($_REQUEST['limit'])) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$templates = $modx->getCollection('modTemplate',$c);
$count = $modx->getCount('modTemplate');

$ts = array();
foreach ($templates as $template) {
    if (isset($_REQUEST['tv']) && $_REQUEST['tv'] != 0) {
        $tvt = $modx->getObject('modTemplateVarTemplate',array(
            'tmplvarid' => $tv->get('id'),
            'templateid' => $template->get('id'),
        ));
    } else $tvt = null;

    if ($tvt != null) {
        $template->set('access',true);
        $template->set('rank',$tvt->get('rank'));
    } else {
        $template->set('access',false);
        $template->set('rank','');
    }
    $ta = $template->toArray();
    unset($ta['content']);

    $ta['menu'] = array();

    $ts[] = $ta;
}

return $this->outputArray($ts,$count);