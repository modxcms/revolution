<?php
/**
 * Gets a node list of ACLs
 *
 * @param string $id The parent ID.
 *
 * @package modx
 * @subpackage processors.security.access.target
 */
$modx->lexicon->load('access');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('access_type_err_ns'));

$targetAttr = explode('_', $scriptProperties['id']);
$targetClass = count($targetAttr) == 3 ? $targetAttr[1] : '';
$targetId = count($targetAttr) >= 2 ? intval($targetAttr[count($targetAttr)-1]) : 0;

$da = array();
if (empty($targetClass)) {
    $da[] = array(
        'text' => $modx->lexicon('contexts'),
        'id' => 'n_modContext_0',
        'cls' => 'icon-context',
        'target' => '0',
        'target_cls' => 'modContext',
        'leaf' => 0,
        'type' => 'modAccessContext',
        'cls' => 'folder',
    );
    $da[] = array(
        'text' => $modx->lexicon('resource_groups'),
        'id' => 'n_modResourceGroup_0',
        'cls' => 'icon-resourcegroup',
        'target' => '0',
        'target_cls' => 'modResourceGroup',
        'leaf' => 0,
        'type' => 'modAccessResourceGroup',
        'cls' => 'folder',
    );
} else {
    $targets = $modx->getCollection($targetClass);
    foreach ($targets as $targetKey => $target) {
        switch ($targetClass) {
            case 'modContext' :
                $nodeText = $target->get('key');
                break;
            default :
                $nodeText = $target->get('name');
                break;
        }
    	$da[] = array(
    		'text' => $nodeText,
    		'id' => 'n_'.$targetClass.'_'.$targetKey,
    		'leaf' => true,
    		'type' => 'modAccess' . substr($targetClass, 3),
    		'cls' => 'file',
    	);
    }
}
return $modx->toJSON($da);