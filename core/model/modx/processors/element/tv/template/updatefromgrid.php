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
if (empty($_DATA['rank'])) $_DATA['rank'] = 0;

$templateVarTemplate = $modx->getObject('modTemplateVarTemplate',array(
    'templateid' => $_DATA['id'],
    'tmplvarid' => $_DATA['tv'],
));

if ($_DATA['access']) {
    /* adding access or updating rank */
    if (empty($templateVarTemplate)) {
        $templateVarTemplate = $modx->newObject('modTemplateVarTemplate');
    }
    $templateVarTemplate->set('templateid',$_DATA['id']);
    $templateVarTemplate->set('tmplvarid',$_DATA['tv']);

    if ($templateVarTemplate->save() == false) {
        return $modx->error->failure($modx->lexicon('tvt_err_save'));
    }
} else {
    /* removing access */
    if (empty($templateVarTemplate)) {
        return $modx->error->failure($modx->lexicon('tvt_err_nf'));
    }

    if ($templateVarTemplate->remove() == false) {
        return $modx->error->failure($modx->lexicon('tvt_err_remove'));
    }
}

return $modx->error->success('',$templateVarTemplate);