<?php
/*
 * Copyright 2006, 2007, 2008, 2009 by  Jason Coward <xpdo@opengeek.com>
 *
 * This file is part of xPDO.
 *
 * xPDO is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * xPDO is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * xPDO; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * MySQL classes for generating xPDOObject classes and maps from an xPDO schema.
 *
 * @package xpdo
 * @subpackage om.mysql
 */

/**
 * Include the parent {@link xPDOGenerator} class.
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../xpdogenerator.class.php');

/**
 * An extension for generating {@link xPDOObject} class and map files for MySQL.
 *
 * A MySQL-specific extension to an {@link xPDOManager} instance that can
 * generate class stub and meta-data map files from a provided XML schema of a
 * database structure.
 *
 * @package xpdo
 * @subpackage om.mysql
 */
class xPDOGenerator_mysql extends xPDOGenerator {
    public function __construct(& $manager) {
        parent :: __construct($manager);
    }

    /**
     * Write an XPDO XML Schema from your database.
     *
     * @param string $schemaFile The name (including path) of the schemaFile you
     * want to write.
     * @param string $package Name of the package to generate the classes in.
     * @param string $baseClass The class which all classes in the package will
     * extend; by default this is set to {@link xPDOObject} and any
     * auto_increment fields with the column name 'id' will extend {@link
     * xPDOSimpleObject} automatically.
     * @param string $tablePrefix The table prefix for the current connection,
     * which will be removed from all of the generated class and table names.
     * Specify a prefix when creating a new {@link xPDO} instance to recreate
     * the tables with the same prefix, but still use the generic class names.
     * @param boolean $restrictPrefix Only reverse-engineer tables that have the
     * specified tablePrefix; if tablePrefix is empty, this is ignored.
     * @return boolean True on success, false on failure.
     */
    public function writeSchema($schemaFile, $package= '', $baseClass= '', $tablePrefix= '', $restrictPrefix= false) {
        if (empty ($package))
            $package= $this->manager->xpdo->package;
        if (empty ($baseClass))
            $baseClass= 'xPDOObject';
        if (empty ($tablePrefix))
            $tablePrefix= $this->manager->xpdo->config[xPDO::OPT_TABLE_PREFIX];
        $xmlContent= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xmlContent .= "<model package=\"{$package}\" baseClass=\"{$baseClass}\" platform=\"mysql\" defaultEngine=\"MyISAM\">\n";
        //read list of tables
        $dbname= $this->manager->xpdo->config['dbname'];
        $tableLike= ($tablePrefix && $restrictPrefix) ? " LIKE '{$tablePrefix}%'" : '';
        $tablesStmt= $this->manager->xpdo->prepare("SHOW TABLES FROM {$dbname}{$tableLike}");
        $tablesStmt->execute();
        $tables= $tablesStmt->fetchAll(PDO::FETCH_NUM);
        if ($this->manager->xpdo->getDebug() === true) $this->manager->xpdo->log(xPDO::LOG_LEVEL_DEBUG, print_r($tables, true));
        foreach ($tables as $table) {
            $xmlObject= '';
            $xmlFields= '';
            if (!$tableName= $this->getTableName($table[0], $tablePrefix, $restrictPrefix)) {
                continue;
            }
            $class= $this->getClassName($tableName);
            $extends= $baseClass;
            $fieldsStmt= $this->manager->xpdo->prepare("SHOW COLUMNS FROM `{$table[0]}`");
            $fieldsStmt->execute();
            $fields= $fieldsStmt->fetchAll(PDO::FETCH_ASSOC);
            if ($this->manager->xpdo->getDebug() === true) $this->manager->xpdo->log(xPDO::LOG_LEVEL_DEBUG, print_r($fields, true));
            foreach ($fields as $field) {
                $Field= '';
                $Type= '';
                $Null= '';
                $Key= '';
                $Default= '';
                $Extra= '';
                extract($field, EXTR_OVERWRITE);
                $Type= xPDO :: escSplit(' ', $Type, "'", 2);
                $precisionPos= strpos($Type[0], '(');
                $dbType= $precisionPos? substr($Type[0], 0, $precisionPos): $Type[0];
                $dbType= strtolower($dbType);
                $Precision= $precisionPos? substr($Type[0], $precisionPos + 1, strrpos($Type[0], ')') - ($precisionPos + 1)): '';
                if (!empty ($Precision)) {
                    $Precision= ' precision="' . trim($Precision) . '"';
                }
                $attributes= '';
                if (isset ($Type[1]) && !empty ($Type[1])) {
                    $attributes= ' attributes="' . trim($Type[1]) . '"';
                }
                $PhpType= $this->getPhpType($dbType);
                $Null= ' null="' . (($Null === 'NO') ? 'false' : 'true') . '"';
                $Key= $this->getIndex($Key);
                $Default= $this->getDefault($Default);
                if (!empty ($Extra)) {
                    if ($Extra === 'auto_increment') {
                        if ($baseClass === 'xPDOObject' && $Field === 'id') {
                            $extends= 'xPDOSimpleObject';
                            continue;
                        } else {
                            $Extra= ' generated="native"';
                        }
                    } else {
                        $Extra= ' extra="' . strtolower($Extra) . '"';
                    }
                    $Extra= ' ' . $Extra;
                }
                $xmlFields .= "\t\t<field key=\"{$Field}\" dbtype=\"{$dbType}\"{$Precision}{$attributes} phptype=\"{$PhpType}\"{$Null}{$Default}{$Key}{$Extra} />\n";
                //                echo $xmlContent . "\n";
            }
            $xmlObject .= "\t<object class=\"{$class}\" table=\"{$tableName}\" extends=\"{$extends}\">\n";
            $xmlObject .= $xmlFields;
            $xmlObject .= "\t</object>\n";
            $xmlContent .= $xmlObject;
        }
        $xmlContent .= "</model>\n";
        if ($this->manager->xpdo->getDebug() === true) {
           $this->manager->xpdo->log(xPDO::LOG_LEVEL_DEBUG, $xmlContent);
        }
        $file= fopen($schemaFile, 'wb');
        $written= fwrite($file, $xmlContent);
        fclose($file);
        return true;
    }
}
