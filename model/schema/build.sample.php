<?php
/*
 * OpenExpedio (xPDO)
 * Copyright (C) 2006, 2007, 2008, 2009 by Jason Coward <xpdo@opengeek.com>
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
include_once (dirname(dirname(dirname(dirname(__FILE__))))) . '/xpdo/xpdo.class.php');

$xpdo= new xPDO('mysql:host=localhost;dbname=xpdotest', 'dbusername', 'dbpassword', '');
$xpdo->setPackage('sample', dirname(dirname(__FILE__)) . '/model/');
$xpdo->setDebug(true);

$manager= $xpdo->getManager();
$generator= $manager->getGenerator();

//Use this to create a schema from an existing database
//$xml= $generator->writeSchema(XPDO_CORE_PATH . '../model/schema/sample.mysql.schema.xml', 'sample', 'xPDOObject', '');

//Use this to generate classes and maps from a schema
// NOTE: by default, only maps are overwritten; delete class files if you want to regenerate classes
$generator->parseSchema(dirname(__FILE__) . '/sample.mysql.schema.xml', dirname(dirname(__FILE__)) . '/model/');

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";

exit ();
