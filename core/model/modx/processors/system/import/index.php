<?php
/**
 * @package modx
 * @subpackage processors.system.import
 */
$modx->lexicon->load('import');

if (!$modx->hasPermission('import_static')) return $modx->error->failure($modx->lexicon('permission_denied'));

$importstart= $modx->getMicroTime();

$modx->getService('import', 'import.modStaticImport', '', array ());

$results= '';
$allowedfiles= array ();
if (isset ($_POST['import_allowed_extensions'])) {
    $importExts = trim($_POST['import_allowed_extensions'], ' ,');
    if (!empty($importExts)) {
        $allowedfiles= explode(',', trim($_POST['import_allowed_extensions'], ' ,'));
    }
}
$context= 'web';
$parent= 0;
$class= 'modStaticResource';
if (isset ($_POST['import_resource_class'])) {
    $class= $modx->loadClass($_POST['import_resource_class']);
}
if (isset ($_POST['import_context'])) {
    $context= $_POST['import_context'];
}
if (isset ($_POST['import_parent'])) {
    $parent= intval($_POST['import_parent']);
}
$filepath= MODX_CORE_PATH . 'import/';
$basefilepath= $filepath;
if (isset ($_POST['import_base_path']) && !empty($_POST['import_base_path'])) {
    $filepath= $_POST['import_base_path'];
    $basefilepath= '';
} else {
    if ($contextObj= $modx->getObject('modContext', $context)) {
        $contextObj->prepare();
        if (isset ($contextObj->config['resource_static_path'])) {
            $filepath= $contextObj->config['resource_static_path'];
            $basefilepath= $contextObj->config['resource_static_path'];
        } elseif (isset ($modx->config['resource_static_path'])) {
            $filepath= $modx->config['resource_static_path'];
            $basefilepath= $modx->config['resource_static_path'];
        }
    }
}
$element= isset ($_POST['content_element']) ? $_POST['content_element'] : 'body';
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