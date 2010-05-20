<?php
/**
 * Deletes a template.
 *
 * @param integer $id The ID of the template
 *
 * @package modx
 * @subpackage processors.element.template
 */
if (!$modx->hasPermission('delete_template')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('template','tv');

/* get template and related tables */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('template_err_ns'));
$template = $modx->getObject('modTemplate',$scriptProperties['id']);
if ($template == null) return $modx->error->failure($modx->lexicon('template_err_nf'));

if (!$template->checkPolicy('remove')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* check to make sure it doesn't have any resources using it */
$resources = $modx->getCollection('modResource',array(
    'deleted' => 0,
    'template' => $template->get('id'),
));
if (count($resources) > 0) {
    $ds = '';
    foreach ($resources as $resource) {
        $ds .= $resource->get('id').' - '.$resource->get('pagetitle')." <br />\n";
    }
    return $modx->error->failure($modx->lexicon('template_err_in_use').$ds);
}

/* make sure isn't default template */
if ($template->get('id') == $modx->getOption('default_template',null,1)) {
    return $modx->error->failure($modx->lexicon('template_err_default_template'));
}

/* invoke OnBeforeTempFormDelete event */
$modx->invokeEvent('OnBeforeTempFormDelete',array(
    'id' => $template->get('id'),
    'template' => &$template,
));

/* remove template var maps */
$templateTVs = $template->getMany('TemplateVarTemplates');
foreach ($templateTVs as $ttv) {
    if ($ttv->remove() == false) {
        $modx->log(modX::LOG_LEVEL_ERROR,$modx->lexicon('tvt_err_remove'));
    }
}

/* delete template */
if ($template->remove() == false) {
    return $modx->error->failure($modx->lexicon('template_err_delete'));
}

/* invoke OnTempFormDelete event */
$modx->invokeEvent('OnTempFormDelete',array(
    'id' => $template->get('id'),
    'template' => &$template,
));

/* log manager action */
$modx->logManagerAction('template_delete','modTemplate',$template->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success('',$template->get(array_diff(array_keys($template->_fields), array('content'))));