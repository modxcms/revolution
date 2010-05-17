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

/* load smarty */
if (!isset($modx->smarty)) {
    $modx->getService('smarty', 'smarty.modSmarty', '', array(
        'template_dir' => $modx->getOption('manager_path') . 'templates/' . $modx->getOption('manager_theme') . '/',
    ));
}
/* get default display properties for specific tv */
$settings = array();
if (!empty($scriptProperties['tv'])) {
    $tv = $modx->getObject('modTemplateVar',$scriptProperties['tv']);
    if (is_object($tv) && $tv instanceof modTemplateVar) {
        $params = $tv->get('display_params');
        $ps = explode('&',$params);
        foreach ($ps as $p) {
            $param = explode('=',$p);
            if (!empty($p[0])) {
                $settings[$param[0]] = $param[1];
            }
        }
    }
    $modx->smarty->assign('tv',$scriptProperties['tv']);
}
$modx->smarty->assign('params',$settings);

/* handle dynamic paths */
$renderDirectories = array(
    dirname(__FILE__).'/'.$context.'/properties/',
);

/* allow for custom directories */
$pluginResult = $modx->invokeEvent('OnTVOutputRenderPropertiesList',array(
    'context' => $context,
));
if (!is_array($pluginResult) && !empty($pluginResult)) { $pluginResult = array($pluginResult); }
if (!empty($pluginResult)) {
    $renderDirectories = array_merge($renderDirectories,$pluginResult);
}

/* get controller */
$o = '';
foreach ($renderDirectories as $renderDirectory) {
    if (empty($renderDirectory)) continue;
    
    $renderFile = $renderDirectory.$scriptProperties['type'].'.php';
    if (file_exists($renderFile)) {
        $o = include $renderFile;
        break;
    }
}

echo $o;
die();