<?php
$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tstart= $mtime;
include_once (dirname(dirname(dirname(__FILE__))) . '/xpdo/xpdo.class.php');
require_once (dirname(dirname(dirname(dirname(__FILE__)))) . '/_build/build.config.php');

//Set some valid connection properties here
$xpdo= new xPDO(
    XPDO_DSN,
    XPDO_DB_USER,
    XPDO_DB_PASS,
    XPDO_TABLE_PREFIX,
    array (
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        PDO::ATTR_PERSISTENT => false,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
    )
);
$xpdo->setPackage('modx', dirname(XPDO_CORE_PATH) . '/model/');
$xpdo->setDebug(true);

$manager= $xpdo->getManager();
$generator= $manager->getGenerator();

//Use this to create a schema from an existing database
//$xml= $generator->writeSchema(XPDO_CORE_PATH . '../model/schema/modx.mysql.schema.xml', 'modx', 'xPDOObject', 'modx_');

//Use this to generate classes and maps from a schema

// NOTE: by default, only maps are overwritten; delete class files if you want to regenerate classes
$generator->classTemplate= <<<EOD
<?php
/**
 * [+phpdoc-package+]
 * [+phpdoc-subpackage+]
 */
class [+class+] extends [+extends+] {
}
?>
EOD;
$generator->platformTemplate= <<<EOD
<?php
/**
 * [+phpdoc-package+]
 * [+phpdoc-subpackage+]
 */
require_once (dirname(dirname(__FILE__)) . '/[+class-lowercase+].class.php');
class [+class+]_[+platform+] extends [+class+] {
}
?>
EOD;
$generator->mapHeader= <<<EOD
<?php
/**
 * [+phpdoc-package+]
 * [+phpdoc-subpackage+]
 */
EOD;
$package= 'modx';
$generator->parseSchema(dirname(XPDO_CORE_PATH) . '/model/schema/modx.mysql.schema.xml', dirname(XPDO_CORE_PATH) . '/model/');
$package= 'modx.transport';
$generator->parseSchema(dirname(XPDO_CORE_PATH) . '/model/schema/modx.transport.mysql.schema.xml', dirname(XPDO_CORE_PATH) . '/model/');
$package= 'modx.registry.db';
$generator->parseSchema(dirname(XPDO_CORE_PATH) . '/model/schema/modx.registry.db.mysql.schema.xml', dirname(XPDO_CORE_PATH) . '/model/');

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";

exit ();
