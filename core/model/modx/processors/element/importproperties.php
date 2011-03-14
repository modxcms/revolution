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
if (empty($scriptProperties['file'])) return $modx->error->failure($modx->lexicon('properties_import_err_upload'));
$_FILE = $scriptProperties['file'];
if (empty($_FILE) || !empty($_FILE['error'])) return $modx->error->failure($modx->lexicon('properties_import_err_upload'));

$o = @file_get_contents($_FILE['tmp_name']);
if (empty($o)) {
    return $modx->error->failure($modx->lexicon('properties_import_err_upload'));
}

$properties = $modx->fromJSON($o);
if (empty($properties) || !is_array($properties)) {
    return $modx->error->failure($modx->lexicon('properties_import_err_invalid'));
}

$data = array();
foreach ($properties as $property) {
    $desc = empty($property['desc']) ? '' : $property['desc'];

    /* backwards compat */
    if (empty($desc)) { $desc = empty($property['description']) ? '' : $property['description']; }

    $desc = str_replace(array("\\n",'\"',"'",'<','>','[',']'),array('','&quot;','"',"&lt;","&gt;",'&#91;','&#93;'),$desc);
    $value = str_replace(array('<','>'),array("&lt;","&gt;"),$property['value']);
    $data[] = array(
        $property['name'],
        $desc,
        $property['xtype'],
        $property['options'],
        $value,
        false, /* overridden set to false */
    );
}
//return $modx->error->failure('test');
return $modx->error->success('',$data);