<?php
/**
 * Gets packages for a provider
 *
 * @param integer $provider The provider ID
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

if (empty($_REQUEST['provider'])) return $modx->error->failure($modx->lexicon('provider_err_ns'));
$provider = $modx->getObject('transport.modTransportProvider',$_REQUEST['provider']);
if ($provider == null) {
    return $modx->error->failure($modx->lexicon('provider_err_nf'));
}
$payload = $provider->scanForPackages();

/* now remove installed packages and format the payload */
$installed = array ();
if ($installedPkgs = $provider->getMany('Packages')) {
    foreach ($installedPkgs as $iPkg) {
        $installed[] = $iPkg->get('signature');
    }
}

$map = array();
$repositories = array();
foreach ($payload as $repositoryKey => $repository) {
    $categories = array();
    $repoArray = array(
        'id' => $repository['id'],
        'data' => $repository['data'],
        'text' => $repository['text'],
        'type' => 'repository',
        'expanded' => true,
        'leaf' => empty($repository['children']) ? true : false,
        'children' => array(),
    );

    $packList = array();
    if (!empty($repository['children'])) {
    foreach ($repository['children'] as $categoryKey => $category) {
        $packages = array();
        $catArray = array(
            'id' => $category['id'],
            'data' => $category['data'],
            'text' => $category['text'],
            'type' => 'category',
            'leaf' => empty($category['children']) ? true : false,
            'children' => array(),
        );

        if (!empty($category['children'])) {
        foreach ($category['children'] as $packageKey => $package) {
            $versions = array();
            $packArray = array(
                'id' => $package['id'],
                'data' => $package['data'],
                'text' => $package['text'],
                'type' => 'package',
                'leaf' => empty($package['children']),
                'children' => array(),
            );

            if (!empty($package['children'])) {
            foreach ($package['children'] as $versionKey => $version) {
                $versArray = array(
                    'id' => $version['id'],
                    'data' => $version['data'],
                    'text' => $version['text'],
                    'type' => 'version',
                    'iconCls' => 'icon-package',
                    'leaf' => true,
                    'checked' => false,
                );

                if (in_array($version['data']['signature'],$installed)) {
                    $versArray['disabled'] = true;
                    $versArray['cls'] = 'icon-package-installed';
                    $versArray['text'] = $versArray['text'].' (<i>'.$modx->lexicon('installed').'</i>)';
                }

                $packArray['children'][] = $versArray;

            }
            $packArray['text'] = $package['text'].' ('.count($packArray['children']).')';
            }

            /* add package to map if has versions */
            if (!empty($packArray['children'])) {
                $catArray['children'][] = $packArray;
                $packList[] = $package['data']['name'];
            }

        }
        $catArray['text'] = $category['text'].' ('.count($catArray['children']).')';
        }

        /* add category to map if has packages */
        if (!empty($catArray['children'])) {
            $repoArray['children'][] = $catArray;
        }
    }
    /* consolidate packages in multiple categories */
    $packList = array_unique($packList);

    $repoArray['text'] = $repository['text'].' ('.count($packList).')';
    }

    /* add repository to map if has categories */
    if (!empty($repoArray['children'])) {
        $map[] = $repoArray;
    }
}

if (!empty($_REQUEST['debug'])) {
    echo '<pre>';print_r($map);die();
}

return $modx->error->success('',$map);