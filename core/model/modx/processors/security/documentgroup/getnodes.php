<?php
/**
 * Get the resource groups as nodes
 *
 * @param string $id The ID of the parent node
 *
 * @package modx
 * @subpackage processors.security.documentgroup
 */
$modx->lexicon->load('access');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_REQUEST['id'] = !isset($_REQUEST['id']) ? 0 : str_replace('n_dg_','',$_REQUEST['id']);

$resourceGroup = $modx->getObject('modResourceGroup',$_REQUEST['id']);
$groups = $modx->getCollection('modResourceGroup');

$da = array();

if ($g == null) {
	foreach ($groups as $group) {
		$da[] = array(
			'text' => $group->get('name'),
			'id' => 'n_dg_'.$group->get('id'),
			'leaf' => 0,
			'type' => 'modResourceGroup',
			'cls' => 'icon-resourcegroup',
            'menu' => array(
                'items' => array(
                    array(
                        'text' => $modx->lexicon('resource_group_create'),
                        'handler' => 'function(itm,e) {
                            this.create(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('resource_group_remove'),
                        'handler' => 'function(itm,e) {
                            this.remove(itm,e);
                        }',
                    ),
                ),
            ),
		);
	}
} else {
	$resources = $resourceGroup->getResources();
	foreach ($resources as $resource) {
		$da[] = array(
			'text' => $resource->get('pagetitle'),
			'id' => 'n_'.$resource->get('id'),
			'leaf' => 1,
			'type' => 'modResource',
			'cls' => 'icon-'.$resource->get('class_key'),
            'menu' => array(
                'items' => array(
                    array(
                        'text' => $modx->lexicon('resource_group_access_remove'),
                        'handler' => 'function(itm,e) {
                            this.removeResource(itm,e);
                        }',
                    )
                ),
            ),
		);
	}
}

return $this->toJSON($da);