<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages.rest
 */
$response = $provider->request('repository/'.$pk,'GET',array(
    'supports' => $productVersion,
));
$tags = $provider->handleResponse($response);
if (!$tags) return $modx->error->failure($modx->lexicon('provider_err_connect'));

$list = array();
foreach ($tags as $tag) {
    if ((string)$tag->name == '') continue;
    $list[] = array(
        'id' => 'n_tag_'.(string)$tag->id.'_'.$pk,
        'text' => (string)$tag->name,
        'leaf' => true,
        'data' => $tag,
        'type' => 'tag',
    );
}

return $list;