<?php
/**
 * Get the resource groups as nodes
 *
 * @param string $id The ID of the parent node
 *
 * @package modx
 * @subpackage processors.security.documentgroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_REQUEST['id'] = !isset($_REQUEST['id']) ? 0 : str_replace('n_dg_','',$_REQUEST['id']);

$g = $modx->getObject('modResourceGroup',$_REQUEST['id']);
$groups = $modx->getCollection('modResourceGroup');

$da = array();

if ($g == null) {
	foreach ($groups as $group) {
		$da[] = array(
			'text' => $group->get('name'),
			'id' => 'n_dg_'.$group->get('id'),
			'leaf' => 0,
			'type' => 'modResourceGroup',
			'cls' => 'folder',
            'menu' => array(
                'items' => array(
                    array(
                        'text' => $modx->lexicon('create_document_group'),
                        'handler' => 'function(itm,e) {
                            this.create(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('delete_document_group'),
                        'handler' => 'function(itm,e) {
                            this.remove(itm,e);
                        }',
                    ),
                ),
            ),
		);
	}
} else {
	$resources = $g->getDocumentsIn();
	foreach ($resources as $resource) {
		$da[] = array(
			'text' => $resource->get('pagetitle'),
			'id' => 'n_'.$resource->get('id'),
			'leaf' => 1,
			'type' => 'modResource',
			'cls' => '',
            'menu' => array(
                'items' => array(
                    array(
                        'text' => $modx->lexicon('delete_document_group_document'),
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