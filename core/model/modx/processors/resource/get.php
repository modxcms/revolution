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
$modx->lexicon->load('resource');
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject('modResource', $scriptProperties['id']);
if (empty($resource)) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $scriptProperties['id'])));

if (!$resource->checkPolicy('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* TODO: audit this and see if it is still applicable for Revo */
if ($modx->getOption('use_editor')) {
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


$resourceArray = $resource->toArray();
if (!empty($resourceArray['pub_date']) && $resourceArray['pub_date'] != '0000-00-00 00:00:00') {
    $resourceArray['pub_date'] = strftime('%Y-%m-%d %H:%M:%S',strtotime($resourceArray['pub_date']));
} else $resourceArray['pub_date'] = '';
if (!empty($resourceArray['unpub_date']) && $resourceArray['unpub_date'] != '0000-00-00 00:00:00') {
    $resourceArray['unpub_date'] = strftime('%Y-%m-%d %H:%M:%S',strtotime($resourceArray['unpub_date']));
} else $resourceArray['unpub_date'] = '';
if (!empty($resourceArray) && $resourceArray['publishedon'] != '0000-00-00 00:00:00') {
    $resourceArray['publishedon'] = strftime('%Y-%m-%d %H:%M:%S',strtotime($resourceArray['publishedon']));
} else $resourceArray['publishedon'] = '';


return $modx->error->success('',$resourceArray);