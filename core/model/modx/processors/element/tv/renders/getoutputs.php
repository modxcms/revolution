<?php
/**
 * Grabs a list of output renders for the tv.
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to web.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */
if (!$modx->hasPermission('view_tv')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('tv_widget');

$context = (isset($scriptProperties['context']) && !empty($scriptProperties['context'])) ? $scriptProperties['context'] : 'web';

$renderDirectories = array(
    dirname(__FILE__).'/'.$context.'/output/',
);

/* allow for custom directories */
$pluginResult = $modx->invokeEvent('OnTVOutputRenderList',array(
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
            $type = str_replace(array('.php','.class','.class.php'),'',$file->getFilename());
            $types[$type] = array(
                'name' => $modx->lexicon($type),
                'value' => $type,
            );
        }
    } catch (UnexpectedValueException $e) {}
}

/* sort types */
ksort($types);
$otypes = array();
foreach ($types as $type) {
    $otypes[] = $type;
}

return $this->outputArray($otypes);