<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder
 */
$modx->lexicon->load('workspace','package_builder');

if (!$modx->hasPermission('package_builder')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_PACKAGE =& $_SESSION['modx.pb'];

/* load the modPackageBuilder class and get an instance */
$modx->log(MODX_LOG_LEVEL_INFO,'Loading package builder.');
$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);

/* create a new package */
$modx->log(MODX_LOG_LEVEL_INFO,'Creating a new package: '.$_PACKAGE['name'].'-'.$_PACKAGE['version'].'-'.$_PACKAGE['release']);
$builder->createPackage($_PACKAGE['name'], $_PACKAGE['version'], $_PACKAGE['release']);
$builder->registerNamespace($_PACKAGE['namespace'],$_PACKAGE['autoselects']);

/* define some locations for file resources */
$sources= array (
    'root' => dirname(dirname(__FILE__)) . '/',
    'assets' => dirname(dirname(__FILE__)) . '/assets/'
);
/* set up some default attributes that define install behavior */
$attributes= array(
    xPDOTransport::UNIQUE_KEY => 'name',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RESOLVE_FILES => true,
    xPDOTransport::RESOLVE_PHP => true,
);

$modx->log(modX::LOG_LEVEL_INFO,'Loading vehicles into package.');
foreach ($_PACKAGE['vehicles'] as $vehicle) {
    $c = $modx->getObject($vehicle['class_key'],$vehicle['object']);
    if ($c == null) continue;

    if (!isset($vehicle['attributes'])) $vehicle['attributes'] = array();
    $attr = array_merge($attributes,$vehicle['attributes']);

    $v = $builder->createVehicle($c,$attr);
    if (isset($vehicle['resolvers']) && !empty($vehicle['resolvers'])) {
        foreach ($vehicle['resolvers'] as $resolver) {
            $v->resolve($resolver['type'],$resolver);
        }
    }
    $builder->putVehicle($v);
}

/* zip up the package */
$modx->log(modX::LOG_LEVEL_INFO,'Attempting to pack package.');
$builder->pack();

$filename = $modx->getOption('core_path').'packages/'.$_PACKAGE['name'].'-'.$_PACKAGE['version'].'-'.$_PACKAGE['release'].'.transport.zip';

$modx->log(modX::LOG_LEVEL_WARN,$modx->lexicon('package_built').' - '.$filename);
return $modx->error->success($modx->lexicon('package_built').' - '.$filename);