<?php
/**
 * Retrieves a resource by its ID.
 *
 * @param integer $id The ID of the resource to grab
 * @return modResource
 *
 * @package modx
 * @subpackage processors.resource
 */
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

if (!isset($_REQUEST['id'])) {
    return $modx->error->failure($modx->lexicon('document_not_specified'));
}
$resource = $modx->getObject('modResource', $_REQUEST['id']);
if ($resource == null) {
    return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_REQUEST['id'])));
}

if (!$resource->checkPolicy('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

if ($modx->getOption('use_editor') == 1) {
    /* replace image path */
    $htmlcontent = $resource->get('content');
    if (!empty ($htmlcontent)) {
        if (substr($modx->getOption('rb_base_url'), -1) != '/') {
            $im_base_url = $modx->getOption('rb_base_url') . '/';
        } else {
            $im_base_url = $modx->getOption('rb_base_url');
        }
        $elements = parse_url($im_base_url);
        $image_path = $elements['path'];
        /* make sure image path ends with a /  */
        if (substr($image_path, -1) != '/') {
            $image_path .= '/';
        }
        $modx_root = $modx->getOption('base_path');
        $image_prefix = substr($image_path, strlen($modx_root));
        if (substr($image_prefix, -1) != '/') {
            $image_prefix .= '/';
        }
        /* escape / in path */
        $image_prefix = str_replace('/', '\/', $image_prefix);
        $newcontent = preg_replace("/(<img[^>]+src=['\"])($image_prefix)([^'\"]+['\"][^>]*>)/", "\${1}$im_base_url\${3}", $resource->get('content'));
        $htmlcontent = $newcontent;
    }
    $resource->set('content',$htmlcontent);
}


$ra = $resource->toArray();
if ($ra['pub_date'] != '0' && $ra['pub_date'] != '' && $ra['pub_date'] != '0000-00-00 00:00:00') {
    $ra['pub_date'] = strftime('%Y-%m-%d %H:%M:%S',strtotime($ra['pub_date']));
} else $ra['pub_date'] = '';
if ($ra['unpub_date'] != '0' && $ra['unpub_date'] != '' && $ra['unpub_date'] != '0000-00-00 00:00:00') {
    $ra['unpub_date'] = strftime('%Y-%m-%d %H:%M:%S',strtotime($ra['unpub_date']));
} else $ra['unpub_date'] = '';
if ($ra['publishedon'] != '0' && $ra['publishedon'] != '' && $ra['publishedon'] != '0000-00-00 00:00:00') {
    $ra['publishedon'] = strftime('%Y-%m-%d %H:%M:%S',strtotime($ra['publishedon']));
} else $ra['publishedon'] = '';


return $modx->error->success('',$ra);