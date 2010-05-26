<?php
/**
 * Import properties from a file
 *
 * @package modx
 * @subpackage processors.element
 */
if (!$modx->hasPermission('view_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset','element');

/* verify file exists */
if (!isset($scriptProperties['file'])) return $modx->error->failure($modx->lexicon('properties_import_err_upload'));
$_FILE = $scriptProperties['file'];
if ($_FILE['error'] != 0) return $modx->error->failure($modx->lexicon('properties_import_err_upload'));

$o = file_get_contents($_FILE['tmp_name']);

$properties = $modx->fromJSON($o);
if (!is_array($properties)) {
    return $modx->error->failure($modx->lexicon('properties_import_err_invalid'));
}

$data = array();
foreach ($properties as $property) {
    $desc = empty($property['desc']) ? '' : $property['desc'];
    /* backwards compat */
    if (empty($desc)) { $desc = empty($property['description']) ? '' : $property['description']; }
    $desc = htmlspecialchars(str_replace("'",'"',$desc));
    $data[] = array(
        $property['name'],
        $desc,
        $property['xtype'],
        $property['options'],
        $property['value'],
        false, /* overridden set to false */
    );
}

return $modx->error->success('',$data);