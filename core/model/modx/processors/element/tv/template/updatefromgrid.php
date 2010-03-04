<?php
/**
 * Assigns or unassigns a template to a TV. Passed in JSON data.
 *
 * @param integer $id The ID of the template
 * @param integer $tv The ID of the tv
 * @param integer $rank The rank of the tv-template relationship
 * @param boolean $access If true, the TV has access to the template.
 *
 * @package modx
 * @subpackage processors.element.template.tv
 */
if (!$modx->hasPermission('save_tv')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('tv');

$_DATA = $modx->fromJSON($scriptProperties['data']);
if ($_DATA['rank'] == '') $_DATA['rank'] = 0;

$tvt = $modx->getObject('modTemplateVarTemplate',array(
    'templateid' => $_DATA['id'],
    'tmplvarid' => $_DATA['tv'],
));

if ($_DATA['access']) {
    /* adding access or updating rank */
    if ($tvt == null) {
        $tvt = $modx->newObject('modTemplateVarTemplate');
    }
    $tvt->set('templateid',$_DATA['id']);
    $tvt->set('tmplvarid',$_DATA['tv']);
    $tvt->set('rank',$_DATA['rank']);

    if ($tvt->save() == false) {
        return $modx->error->failure($modx->lexicon('tvt_err_save'));
    }
} else {
    /* removing access */
    if ($tvt == null) {
        return $modx->error->failure($modx->lexicon('tvt_err_nf'));
    }

    if ($tvt->remove() == false) {
        return $modx->error->failure($modx->lexicon('tvt_err_remove'));
    }
}

return $modx->error->success();