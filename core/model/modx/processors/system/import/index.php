<?php
/**
 * @package modx
 * @subpackage processors.system.import
 */
if (!$modx->hasPermission('import_static')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('import');

$importstart= $modx->getMicroTime();

$modx->getService('import', 'import.modStaticImport', '', array ());

$results= '';
$allowedfiles= array ();
if (isset ($scriptProperties['import_allowed_extensions'])) {
    $importExts = trim($scriptProperties['import_allowed_extensions'], ' ,');
    if (!empty($importExts)) {
        $allowedfiles= explode(',', trim($scriptProperties['import_allowed_extensions'], ' ,'));
    }
}
$context= 'web';
$parent= 0;
$class= 'modStaticResource';
if (isset ($scriptProperties['import_resource_class'])) {
    $class= $modx->loadClass($scriptProperties['import_resource_class']);
}
if (isset ($scriptProperties['import_context'])) {
    $context= $scriptProperties['import_context'];
}
if (isset ($scriptProperties['import_parent'])) {
    $parent= intval($scriptProperties['import_parent']);
}
$filepath= $modx->getOption('core_path') . 'import/';
$basefilepath= $filepath;
if (isset ($scriptProperties['import_base_path']) && !empty($scriptProperties['import_base_path'])) {
    $filepath= $scriptProperties['import_base_path'];
    $basefilepath= '';
} else {
    if ($contextObj= $modx->getObject('modContext', $context)) {
        $contextObj->prepare();
        $crsp = $contextObj->getOption('resource_static_path');
        $rsp = $modx->getOption('resource_static_path');
        if (!empty($crsp)) {
            $filepath= $contextObj->getOption('resource_static_path');
            $basefilepath= $contextObj->getOption('resource_static_path');
        } elseif (!empty($rsp)) {
            $filepath= $modx->getOption('resource_static_path');
            $basefilepath= $modx->getOption('resource_static_path');
        }
    }
}
$element= isset ($scriptProperties['content_element']) ? $scriptProperties['content_element'] : 'body';
$filesfound= 0;

$files= $modx->import->getFiles($filesfound, $filepath);

/* no. of files to import */
$results .= sprintf($modx->lexicon('import_files_found'), $filesfound) . '<br />';

/* import files */
@ini_set('max_execution_time', 0);
if (count($files) > 0) {
    $modx->import->importFiles($allowedfiles, $parent, $filepath, $files, $context, $class, $basefilepath);
    $results .= implode('<br />', $modx->import->results);
}

$importend= $modx->getMicroTime();
$totaltime= ($importend - $importstart);
$results .= sprintf("<br />" . $modx->lexicon('import_site_time'), round($totaltime, 3));

return $modx->error->success($results);