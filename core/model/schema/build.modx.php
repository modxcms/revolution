<?php
$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tstart= $mtime;

$properties = array();
require_once (dirname(dirname(dirname(__DIR__))) . '/config.core.php');
require_once (MODX_CORE_PATH . 'vendor/autoload.php');
require_once (dirname(dirname(dirname(__DIR__))) . '/_build/build.properties.php');

use \xPDO\xPDO;

foreach (['mysql', 'sqlsrv'] as $driver) {
    try {
        $xpdo = new xPDO(
            $properties["{$driver}_string_dsn_nodb"],
            $properties["{$driver}_string_username"],
            $properties["{$driver}_string_password"],
            $properties["{$driver}_array_options"],
            $properties["{$driver}_array_driverOptions"]
        );
    } catch (Exception $e) {
        exit($e->getMessage());
    }
    //$xpdo->setPackage('modx', MODX_CORE_PATH . '/model/');
    $xpdo->setDebug(true);

    $manager= $xpdo->getManager();
    $generator= $manager->getGenerator();
    //Use this to create a schema from an existing database
    //$xml= $generator->writeSchema(XPDO_CORE_PATH . '../model/schema/modx.mysql.schema.xml', 'modx', 'xPDOObject', 'modx_');

    //Use this to generate classes and maps from a schema
    // NOTE: by default, only maps are overwritten; delete class files if you want to regenerate classes
    $schemas = ['modx', 'modx.transport', 'modx.registry.db', 'modx.sources'];
    foreach ($schemas as $schema) {
        try {
            $generator->parseSchema(MODX_CORE_PATH . "model/schema/{$schema}.{$driver}.schema.xml", MODX_CORE_PATH . 'model/');
        } catch (Exception $e) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage());
        }
    }
}

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

exit("\nExecution time: {$totalTime}\n");