<?php
$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tstart= $mtime;

$properties = array();
include_once (dirname(dirname(dirname(__FILE__))) . '/xpdo/xpdo.class.php');
require_once (dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php');
require_once (dirname(dirname(dirname(dirname(__FILE__)))) . '/_build/build.properties.php');

foreach (array('mysql', 'sqlsrv') as $driver) {
    $xpdo= new xPDO(
        $properties["{$driver}_string_dsn_nodb"],
        $properties["{$driver}_string_username"],
        $properties["{$driver}_string_password"],
        $properties["{$driver}_array_options"],
        $properties["{$driver}_array_driverOptions"]
    );
    $xpdo->setPackage('modx', dirname(XPDO_CORE_PATH) . '/model/');
    $xpdo->setDebug(true);

    $manager= $xpdo->getManager();
    $generator= $manager->getGenerator();

    $generator->classTemplate= <<<EOD
<?php
/**
 * [+phpdoc-package+]
 * [+phpdoc-subpackage+]
 */
/**
 * [+phpdoc-package+]
 * [+phpdoc-subpackage+]
 */
class [+class+] extends [+extends+] {
}
EOD;
    $generator->platformTemplate= <<<EOD
<?php
/**
 * [+phpdoc-package+]
 * [+phpdoc-subpackage+]
 */
require_once (dirname(dirname(__FILE__)) . '/[+class-lowercase+].class.php');
/**
 * [+phpdoc-package+]
 * [+phpdoc-subpackage+]
 */
class [+class+]_[+platform+] extends [+class+] {
}
EOD;
    $generator->mapHeader= <<<EOD
<?php
/**
 * [+phpdoc-package+]
 * [+phpdoc-subpackage+]
 */
EOD;

    //Use this to create a schema from an existing database
    //$xml= $generator->writeSchema(XPDO_CORE_PATH . '../model/schema/modx.mysql.schema.xml', 'modx', 'xPDOObject', 'modx_');

    //Use this to generate classes and maps from a schema
    // NOTE: by default, only maps are overwritten; delete class files if you want to regenerate classes
    $package= 'modx';
    $generator->parseSchema(dirname(XPDO_CORE_PATH) . "/model/schema/modx.{$driver}.schema.xml", dirname(XPDO_CORE_PATH) . '/model/');
    $package= 'modx.transport';
    $generator->parseSchema(dirname(XPDO_CORE_PATH) . "/model/schema/modx.transport.{$driver}.schema.xml", dirname(XPDO_CORE_PATH) . '/model/');
    $package= 'modx.registry.db';
    $generator->parseSchema(dirname(XPDO_CORE_PATH) . "/model/schema/modx.registry.db.{$driver}.schema.xml", dirname(XPDO_CORE_PATH) . '/model/');
    $package= 'modx.sources';
    $generator->parseSchema(dirname(XPDO_CORE_PATH) . "/model/schema/modx.sources.{$driver}.schema.xml", dirname(XPDO_CORE_PATH) . '/model/');
}

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";

exit ();
