<?php
/**
 * Grabs a list of render properties for a TV render
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to
 * executing context.
 * @param string $type (optional) The type of render to grab properties for.
 * Defaults to default.
 * @param integer $tv (optional) The TV to prefill property values from.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */
if (!$modx->hasPermission('view_tv')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('tv_widget');

$context = (isset($scriptProperties['context']) && !empty($scriptProperties['context'])) ? $scriptProperties['context'] : $modx->context->get('key');
if (!isset($scriptProperties['type'])) $scriptProperties['type'] = 'default';

if (!isset($modx->smarty)) {
    $modx->getService('smarty', 'smarty.modSmarty', '', array(
        'template_dir' => $modx->getOption('manager_path') . 'templates/' . $modx->getOption('manager_theme') . '/',
    ));
}
$settings = array();
if (!empty($scriptProperties['tv'])) {
	$tv = $modx->getObject('modTemplateVar',$scriptProperties['tv']);
    if ($tv != null) {
        $params = $tv->get('display_params');
        $ps = explode('&',$params);
        foreach ($ps as $p) {
        	$param = explode('=',$p);
            if ($p[0] != '') $settings[$param[0]] = $param[1];
        }
    }
    $modx->smarty->assign('tv',$scriptProperties['tv']);
}
$modx->smarty->assign('params',$settings);

$renderPath = dirname(__FILE__).'/'.$context.'/properties/';
$renderFile = $renderPath.$scriptProperties['type'].'.php';

if (file_exists($renderFile)) {
    $o = require_once $renderFile;
} else {
	return $modx->error->failure($modx->lexicon('error'));
}

echo $o;
die();