<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages.rest
 */
$response = $provider->request('repository');
if ($response->isError()) {
    return $modx->error->failure($modx->lexicon('provider_err_connect',array('error' => $response->getError())));
}
$repositories = $response->toXml();

$list = array();
foreach ($repositories as $repository) {

    $repositoryArray = array();
    foreach ($repository->children() as $k => $v) {
        $repositoryArray[$k] = (string)$v;
    }
    $list[] = array(
        'id' => 'n_repository_'.(string)$repository->id,
        'text' => (string)$repository->name,
        'leaf' => $repository->packages > 0 ? false : true,
        'data' => $repositoryArray,
        'type' => 'repository',
    );
}

return $list;