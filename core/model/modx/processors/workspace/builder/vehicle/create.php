<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder.vehicle
 */
$modx->lexicon->load('workspace','package_builder');

if (!$modx->hasPermission('package_builder')) return $modx->error->failure($modx->lexicon('permission_denied'));

$class_key = isset($scriptProperties['classKeyOther']) && $scriptProperties['classKeyOther'] != ''
    ? $scriptProperties['classKeyOther']
    : $scriptProperties['classKey'];

/* needs to be dynamic */
$pk = $scriptProperties['object'];
switch ($class_key) {
    case 'modDocument':
    case 'modResource':
        $name = 'pagetitle'; break;
    case 'modTemplate':
        $name = 'templatename'; break;
    case 'modCategory':
        $name = 'category'; break;
    case 'modContext':
        $name = 'key';
        break;
    case 'modAction':
        $name = 'controller'; break;
    case 'modMenu':
        $name = 'text'; break;
    default:
        $name = 'name';
        $pk = $scriptProperties['object'];
        break;
}

$c = $modx->getObject($class_key,$pk);
if ($c == null) return $modx->error->failure('Object not found!');

$resolvers = array();
if (isset($scriptProperties['resolvers'])) {
    $rs = $modx->fromJSON($scriptProperties['resolvers']);
    foreach ($rs as $resolver) {
        array_push($resolvers,$resolver);
    }
}

$vehicle = array(
    'class_key' => $class_key,
    'object' => $scriptProperties['object'],
    'name' => $c->get($name),
    'resolvers' => $resolvers,
    'attributes' => array(
        'unique_key' => $scriptProperties['unique_key'] == '' ? 'name' : $scriptProperties['unique_key'],
        'update_object' => isset($scriptProperties['update_object']) && $scriptProperties['update_object'] == true,
        'resolve_files' => isset($scriptProperties['resolve_files']) && $scriptProperties['resolve_files'] == true,
        'resolve_php' => isset($scriptProperties['resolve_php']) && $scriptProperties['resolve_php'] == true,
        'preserve_keys' => isset($scriptProperties['preserve_keys']) && $scriptProperties['preserve_keys'] == true,
    ),
);

array_push($_SESSION['modx.pb']['vehicles'],$vehicle);

return $modx->error->success();