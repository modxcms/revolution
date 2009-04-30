<?php
/**
 * Assigns or unassigns TVs to a template. Passed as JSON data.
 *
 * @param integer $id The ID of the TV.
 * @param string $name (optional) The name of the TV.
 * @param string $description (optional) The description of the TV.
 * @param integer $template The ID of the template.
 * @param integer $rank The rank of the TV for the template.
 * @param boolean $access If true, give the TV access to the template. Else,
 * remove access.
 *
 * @package modx
 * @subpackage processors.element.template.tv
 */
$modx->lexicon->load('tv','category');

$_DATA = $modx->fromJSON($_POST['data']);

$tv = $modx->getObject('modTemplateVar',$_DATA['id']);
if ($tv == null) return $modx->error->failure($modx->lexicon('tv_err_not_found'));

$tvt = $modx->getObject('modTemplateVarTemplate',array(
    'templateid' => $_DATA['template'],
    'tmplvarid' => $_DATA['id'],
));

if ($tvt == null && $_DATA['access'] == true) {
    $tvt = $modx->newObject('modTemplateVarTemplate');
    $tvt->set('templateid',$_DATA['template']);
    $tvt->set('tmplvarid',$_DATA['id']);
    $tvt->set('rank',$_DATA['rank']);
    if ($tvt->save() === false) {
        return $modx->error->failure($modx->lexicon('tvt_err_save'));
    }
} elseif ($tvt != null && $_DATA['access'] == false) {
    if ($tvt->remove() === false) {
        return $modx->error->failure($modx->lexicon('tvt_err_remove'));
    }
} elseif ($tvt != null) {
    $tvt->set('rank',$_DATA['rank']);
    if ($tvt->save() === false) {
        return $modx->error->failure($modx->lexicon('tvt_err_save'));
    }
}

if (isset($_DATA['name'])) $tv->set('name',$_DATA['name']);
if (isset($_DATA['description'])) $tv->set('description',$_DATA['description']);
$tv->save();

return $modx->error->success();