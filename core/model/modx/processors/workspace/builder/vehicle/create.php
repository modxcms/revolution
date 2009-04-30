<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder.vehicle
 */
$modx->lexicon->load('workspace','package_builder');

if (!$modx->hasPermission('package_builder')) return $modx->error->failure($modx->lexicon('permission_denied'));

$class_key = isset($_POST['classKeyOther']) && $_POST['classKeyOther'] != ''
    ? $_POST['classKeyOther']
    : $_POST['classKey'];

/* needs to be dynamic */
$pk = $_POST['object'];
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
        $pk = $_POST['object'];
        break;
}

$c = $modx->getObject($class_key,$pk);
if ($c == null) return $modx->error->failure('Object not found!');

$resolvers = array();
if (isset($_POST['resolvers'])) {
    $rs = $modx->fromJSON($_POST['resolvers']);
    foreach ($rs as $resolver) {
        array_push($resolvers,$resolver);
    }
}

$vehicle = array(
    'class_key' => $class_key,
    'object' => $_POST['object'],
    'name' => $c->get($name),
    'resolvers' => $resolvers,
    'attributes' => array(
        'unique_key' => $_POST['unique_key'] == '' ? 'name' : $_POST['unique_key'],
        'update_object' => isset($_POST['update_object']) && $_POST['update_object'] == true,
        'resolve_files' => isset($_POST['resolve_files']) && $_POST['resolve_files'] == true,
        'resolve_php' => isset($_POST['resolve_php']) && $_POST['resolve_php'] == true,
        'preserve_keys' => isset($_POST['preserve_keys']) && $_POST['preserve_keys'] == true,
    ),
);

array_push($_SESSION['modx.pb']['vehicles'],$vehicle);

return $modx->error->success();