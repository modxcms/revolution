<?php
/**
 * Grabs a list of inputs for a TV.
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to
 * executing context.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */
if (!$modx->hasPermission('view_tv')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('tv_widget','tv_input_types');

$context = (isset($scriptProperties['context']) && !empty($scriptProperties['context'])) ? $scriptProperties['context'] : $modx->context->get('key');

/* simulate controller to allow controller methods in TV Input Properties controllers */
$modx->getService('smarty', 'smarty.modSmarty','');
require_once MODX_CORE_PATH.'model/modx/modmanagercontroller.class.php';
class TvInputManagerController extends modManagerController {
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
$c = new TvInputManagerController($this->modx);
$modx->controller = call_user_func_array(array($c,'getInstance'),array($this->modx,'TvInputManagerController',$this->action));
$modx->controller->render();

$renderDirectories = array(
    dirname(__FILE__).'/'.$context.'/input/',
);

/* allow for custom directories */
$pluginResult = $modx->invokeEvent('OnTVInputRenderList',array(
    'context' => $context,
));
if (!is_array($pluginResult) && !empty($pluginResult)) { $pluginResult = array($pluginResult); }
if (!empty($pluginResult)) {
    $renderDirectories = array_merge($renderDirectories,$pluginResult);
}

/* search directories */
$types = array();
foreach ($renderDirectories as $renderDirectory) {
    if (empty($renderDirectory) || !is_dir($renderDirectory)) continue;
    try {
        $dirIterator = new DirectoryIterator($renderDirectory);
        foreach ($dirIterator as $file) {
            if (!$file->isReadable() || !$file->isFile()) continue;
            $type = str_replace('.php','',$file->getFilename());
            $types[$type] = array(
                'name' => $modx->lexicon($type),
                'value' => $type,
            );
        }
    } catch (UnexpectedValueException $e) {}
}

/* sort types */
asort($types);
$otypes = array();
foreach ($types as $type) {
    $otypes[] = $type;
}

return $this->outputArray($otypes);