<?php
/*
 * OpenExpedio (xPDO)
 * Copyright 2010-2013 by MODX, LLC.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */
$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tstart= $mtime;

$properties = array();
include(dirname(dirname(dirname(dirname(__FILE__)))) . '/xpdo/xpdo.class.php');
include(dirname(dirname(dirname(dirname(__FILE__)))) . '/test/properties.inc.php');

$dbtypes = array('mysql', 'sqlite', 'sqlsrv');

foreach ($dbtypes as $dbtype) {
    $xpdo= new xPDO($properties["{$dbtype}_string_dsn_test"], $properties["{$dbtype}_string_username"], $properties["{$dbtype}_string_password"], $properties["{$dbtype}_array_driverOptions"]);
    $xpdo->setPackage('sample', $properties['xpdo_test_path'] . 'model/');
    $xpdo->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');
    $xpdo->setLogLevel(xPDO::LOG_LEVEL_INFO);
//    $xpdo->setDebug(true);

    $xpdo->getManager();
    $xpdo->manager->getGenerator();

    //Use this to create a schema from an existing database
    #$xml= $xpdo->manager->generator->writeSchema(XPDO_CORE_PATH . '../model/schema/sample.' . $dbtype . '.schema.xml', 'sample', 'xPDOObject', '');

    //Use this to generate classes and maps from a schema
    // NOTE: by default, only maps are overwritten; delete class files if you want to regenerate classes
    $xpdo->manager->generator->parseSchema($properties['xpdo_test_path'] . 'model/schema/sample.' . $dbtype . '.schema.xml', $properties['xpdo_test_path'] . 'model/');

    $xpdo->manager->generator->parseSchema($properties['xpdo_test_path'] . 'model/schema/sample.sti.' . $dbtype . '.schema.xml', $properties['xpdo_test_path'] . 'model/');

    unset($xpdo);
}

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";

exit ();
