<?php
/**
 * Grabs a list of input properties for a TV type
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
$modx->lexicon->load('tv_widget','tv_input_types');

$context = (isset($scriptProperties['context']) && !empty($scriptProperties['context'])) ? $scriptProperties['context'] : $modx->context->get('key');
if (!isset($scriptProperties['type'])) $scriptProperties['type'] = 'default';

/* simulate controller to allow controller methods in TV Input Properties controllers */
$modx->getService('smarty', 'smarty.modSmarty','');
require_once MODX_CORE_PATH.'model/modx/modmanagercontroller.class.php';
class TvInputPropertiesManagerController extends modManagerController {
    public $loadFooter = false;
    public $loadHeader = false;
    public function checkPermissions() {
        return $this->modx->hasPermission('view_tv');
    }
    public function loadCustomCssJs() {}
    public function process(array $scriptProperties = array()) {}
    public function getPageTitle() {return '';}
    public function getTemplateFile() {
        return 'empty.tpl';
    }
    public function getLanguageTopics() {return array();}
}

/* simulate controller with the faux class above */
$c = new TvInputPropertiesManagerController($this->modx,$this->action);
$modx->controller = call_user_func_array(array($c,'getInstance'),array($this->modx,'TvInputPropertiesManagerController',$this->action));
$modx->controller->render();

/* get default display properties for specific tv */
$settings = array();
if (!empty($scriptProperties['tv'])) {
    $tv = $modx->getObject('modTemplateVar',$scriptProperties['tv']);
    if (is_object($tv) && $tv instanceof modTemplateVar) {
        $settings = $tv->get('input_properties');

    }
    $modx->controller->setPlaceholder('tv',$scriptProperties['tv']);
}
if (!isset($settings['allowBlank'])) $settings['allowBlank'] = true;
$modx->controller->setPlaceholder('params',$settings);

/* handle dynamic paths */
$renderDirectories = array(
    dirname(__FILE__).'/'.$context.'/inputproperties/',
);

/* allow for custom directories */
$pluginResult = $modx->invokeEvent('OnTVInputPropertiesList',array(
    'context' => $context,
));
if (!is_array($pluginResult) && !empty($pluginResult)) { $pluginResult = array($pluginResult); }
if (!empty($pluginResult)) {
    $renderDirectories = array_merge($renderDirectories,$pluginResult);
}

/* get controller */
$o = '';
foreach ($renderDirectories as $renderDirectory) {
    if (empty($renderDirectory) || !is_dir($renderDirectory)) continue;

    $renderFile = $renderDirectory.$scriptProperties['type'].'.php';
    if (file_exists($renderFile)) {
        $o = include $renderFile;
        break;
    }
}

echo $o;
@session_write_close();
die();
