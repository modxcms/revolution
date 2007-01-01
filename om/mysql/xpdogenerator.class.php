<?php
/*
 * OpenExpedio (xPDO)
 * Copyright (C) 2006 Jason Coward <xpdo@opengeek.com>
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

/**
 * Classes for generating xPDOObject classes and maps from an XML schema.
 * 
 * @package xpdo.om.mysql
 */

/**
 * An extension for generating {@link xPDOObject} class and map files.
 * 
 * An extension to an {@link xPDOManager} instance that can generate class
 * stub and meta-data map files from a provided vanilla XML schema of a database
 * structure.
 */
class xPDOGenerator {
    var $manager= null;
    var $schemaManager= null;

    var $xmlParser= null;
    var $outputDir= '';
    var $schemaFile= '';
    var $schemaContent= '';
    var $classTemplate= '';
    var $model= array ();
    var $classes= array ();
    var $map= array ();
    
    var $dbtypes= array ();

    var $className= '';
    var $fieldKey= '';

    function xPDOGenerator(& $manager) {
        $this->manager= & $manager;
        $this->dbtypes['integer']= array('INT','INTEGER','TINYINT','BOOLEAN','DECIMAL','DEC','NUMERIC','FLOAT','DOUBLE','DOUBLE PRECISION','REAL','SMALLINT','MEDIUMINT','BIGINT','BIT');
        $this->dbtypes['string']= array('CHAR','VARCHAR','BINARY','VARBINARY','TINYTEXT','TEXT','MEDIUMTEXT','LONGTEXT','ENUM','SET','DATE','DATETIME','TIMESTAMP','TIME','YEAR');
        $this->dbtypes['blob']= array('TINYBLOB','BLOB','MEDIUMBLOB','LONGBLOB');
//        $this->dbtypes['timestamp']= array();
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
     * @return boolean True on success, false on failure.
     * @todo Implement writeSchema() for reverse-engineering existing databases.
     */
    function writeSchema($schemaFile, $package= '', $baseClass= '', $tablePrefix= '') {
        if (empty ($package))
            $package= $this->manager->xpdo->package;
        if (empty ($baseClass))
            $baseClass= 'xPDOObject';
        if (empty ($tablePrefix))
            $tablePrefix= $this->manager->xpdo->config['table_prefix'];
        $xmlContent= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xmlContent .= "<model package=\"{$package}\" baseClass=\"{$baseClass}\" platform=\"mysql\" defaultEngine=\"MyISAM\">\n";
        //read list of tables
        $dbname= $this->manager->xpdo->config['dbname'];
        $tablesStmt= $this->manager->xpdo->prepare("SHOW TABLES FROM {$dbname}");
        $tablesStmt->execute();
        $tables= $tablesStmt->fetchAll(PDO_FETCH_NUM);
        if ($this->manager->xpdo->getDebug() === true) $this->manager->xpdo->_log(XPDO_LOG_LEVEL_DEBUG, print_r($tables, true));
        foreach ($tables as $table) {
            $xmlObject= '';
            $xmlFields= '';
            $tableName= $this->getTableName($table[0], $tablePrefix);
            $class= $this->getClassName($tableName);
            $extends= $baseClass;
            $fieldsStmt= $this->manager->xpdo->prepare("SHOW COLUMNS FROM `{$table[0]}`");
            $fieldsStmt->execute();
            $fields= $fieldsStmt->fetchAll(PDO_FETCH_ASSOC);
            if ($this->manager->xpdo->getDebug() === true) $this->manager->xpdo->_log(XPDO_LOG_LEVEL_DEBUG, print_r($fields, true));
            foreach ($fields as $field) {
                $Field= '';
                $Type= '';
                $Null= '';
                $Key= '';
                $Default= '';
                $Extra= '';
                extract($field, EXTR_OVERWRITE);
                $Type= explode(' ', $Type, 2);
                $precisionPos= strpos($Type[0], '(');
                $dbType= $precisionPos? substr($Type[0], 0, $precisionPos): $Type[0];
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
                        }
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
           $this->manager->xpdo->_log(XPDO_LOG_LEVEL_DEBUG, $xmlContent);
        }
        $file= fopen($schemaFile, 'wb');
        $written= fwrite($file, $xmlContent);
        fclose($file);
        return true;
    }

    function getTableName($string, $prefix= '') {
        if (strpos($string, $prefix) === 0) {
            $string= substr($string, strlen($prefix));
        }
        return $string;
    }

    function getClassName($string) {
        if (is_string($string) && $strArray= explode('_', $string)) {
            $return= '';
            while (list($k, $v)= each($strArray)) {
                $return.= strtoupper(substr($v, 0, 1)) . substr($v, 1) . ''; 
            }
            $string= $return;
        }
        return trim($string); 
    }
    
    function getPhpType($dbtype) {
        $dbtype= strtoupper($dbtype);
        foreach ($this->dbtypes as $key => $type) {
            if (in_array($dbtype, $type)) {
                $phptype= $key;
                break;
            }
        }
        return $phptype;
    }
    function getDefault($value) {
        $return= '';
        if ($value !== null) {
            $return= ' default="'.trim($value).'"';
        }
        return $return;
    }
    function getIndex($index) {
        switch ($index) {
        	case 'PRI':
        		$index= 'pk';
        		break;
        
        	case 'UNI':
        		$index= 'unique';
        		break;
        
        	case 'MUL':
        		$index= 'index';
        		break;
        
        	default:
        		break;
        }
        if (!empty ($index)) {
            $index= ' index="' . $index . '"';
        }
        return $index;
    }

    /**
     * Parses an XPDO XML schema and generates classes and map files from it.
     * 
     * @param string $schemaFile The name of the XML file representing the
     * schema.
     * @param string $outputDir The directory in which to generate the class and
     * map files into.
     * @return boolean True on success, false on failure.
     */
    function parseSchema($schemaFile, $outputDir= '') {
        $this->schemaFile= $schemaFile;
        $this->classTemplate= $this->getClassTemplate();
        if (!is_file($schemaFile)) {
            $this->manager->xpdo->_log(XPDO_LOG_LEVEL_ERROR, "Could not find specified XML schema file {$schemaFile}");
            return false;
        } else {
            $fileContent= @ file($schemaFile);
            $this->schemaContent= implode('', $fileContent);
        }

        // Create the parser and set handlers.
        $this->xmlParser= xml_parser_create('UTF-8');

        xml_set_object($this->xmlParser, $this);
        xml_parser_set_option($this->xmlParser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($this->xmlParser, XML_OPTION_TARGET_ENCODING, 'UTF-8');
        xml_set_element_handler($this->xmlParser, '_handleOpenElement', '_handleCloseElement');
        xml_set_character_data_handler($this->xmlParser, "_handleCData");

        // Parse it.
        if (!xml_parse($this->xmlParser, $this->schemaContent)) {
            $ln= xml_get_current_line_number($this->xmlParser);
            $msg= xml_error_string(xml_get_error_code($this->xmlParser));
            $this->manager->xpdo->_log(XPDO_LOG_LEVEL_ERROR, "Error parsing XML schema on line $ln: $msg");
            return false;
        }

        // Free up the parser and clear memory
        xml_parser_free($this->xmlParser);
        unset ($this->xmlParser);

        $om_path= XPDO_CORE_PATH . 'om/';
        $path= !empty ($outputDir) ? $outputDir : $om_path;
        if (isset ($this->model['package']) && strlen($this->model['package']) > 0) {
            $path .= strtr($this->model['package'], '.', '/');
            $path .= '/';
        }
        $this->outputClasses($path);
        $this->outputMaps($path);
        return true;
    }

    function _handleOpenElement(& $parser, & $element, & $attributes) {
        $element= strtolower($element);
        switch ($element) {
            case 'model' :
                while (list ($attrName, $attrValue)= each($attributes)) {
                    $this->model[$attrName]= $attrValue;
                }
                break;
            case 'object' :
                while (list ($attrName, $attrValue)= each($attributes)) {
                    switch ($attrName) {
                        case 'class' :
                            $this->className= "{$attrValue}";
                            if (!isset ($this->classes[$this->className])) {
                                $this->classes[$this->className]= array ();
                                $this->map[$this->className]= array ();
                                $this->classes[$this->className]['extends']= $this->model['baseClass'];
                            }
                            break;
                        case 'table' :
                            $this->map[$this->className]['table']= $attrValue;
                            break;
                        case 'extends' :
                            $this->classes[$this->className]['extends']= $attrValue;
                            break;
                    }
                }
                break;
            case 'field' :
                while (list ($attrName, $attrValue)= each($attributes)) {
                    switch ($attrName) {
                        case 'key' :
                            $this->fieldKey= "{$attrValue}";
                            $this->map[$this->className]['fields'][$this->fieldKey]= null;
                            $this->map[$this->className]['fieldMeta'][$this->fieldKey]= array ();
                            break;
                        case 'default' :
                            $this->map[$this->className]['fields'][$this->fieldKey]= $attrValue;
                            break;
                        default :
                            $this->map[$this->className]['fieldMeta'][$this->fieldKey][$attrName]= $attrValue;
                            break;
                    }
                }
                break;
            case 'aggregate' :
                $fkclass= '';
                $key= '';
                while (list ($attrName, $attrValue)= each($attributes)) {
                    switch ($attrName) {
                        case 'class' :
                            $fkclass= "{$attrValue}";
                            if (!isset ($this->map[$this->className]['aggregates'][$fkclass])) {
                                $this->map[$this->className]['aggregates'][$fkclass]= array ();
                            }
                            break;
                        case 'key' :
                            $key= "{$attrValue}";
                            $this->map[$this->className]['aggregates'][$fkclass][$key]= array ();
                            break;
                        default :
                            $this->map[$this->className]['aggregates'][$fkclass][$key][$attrName]= $attrValue;
                            break;
                    }
                }
                break;
            case 'composite' :
                $fkclass= '';
                $key= '';
                while (list ($attrName, $attrValue)= each($attributes)) {
                    switch ($attrName) {
                        case 'class' :
                            $fkclass= "{$attrValue}";
                            if (!isset ($this->map[$this->className]['composites'][$fkclass])) {
                                $this->map[$this->className]['composites'][$fkclass]= array ();
                            }
                            break;
                        case 'key' :
                            $key= "{$attrValue}";
                            $this->map[$this->className]['composites'][$fkclass][$key]= array ();
                            break;
                        default :
                            $this->map[$this->className]['composites'][$fkclass][$key][$attrName]= $attrValue;
                            break;
                    }
                }
                break;
        }
    }

    function _handleCloseElement(& $parser, & $element) {}

    function _handleCData(& $parser, & $data) {}

    function outputClasses($path) {
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
        $path .= $this->model['platform'];
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
        foreach ($this->classes as $className => $classDef) {
            $keys= array ();
            $classDef['class']= $className;
            foreach (array_keys($classDef) as $defKey) {
                $keys[]= "[+{$defKey}+]";
            }
            $fileContent= str_replace($keys, array_values($classDef), $this->classTemplate);
            if (is_dir($path)) {
                $fileName= $path . '/' . strtolower($className) . '.class.php';
                if (!file_exists($fileName)) {
                    if ($file= @ fopen($fileName, 'wb')) {
                        if (!fwrite($file, $fileContent)) {
                            $this->manager->xpdo->_log(XPDO_LOG_LEVEL_ERROR, "Could not write to file: {$fileName}");
                        }
                        fclose($file);
                    } else {
                        $this->manager->xpdo->_log(XPDO_LOG_LEVEL_ERROR, "Could not open or create file: {$fileName}");
                    }
                } else {
                    $this->manager->xpdo->_log(XPDO_LOG_LEVEL_INFO, "Skipping {$fileName}; file already exists.\nMove existing class files to regenerate them.");
                }
            } else {
                $this->manager->xpdo->_log(XPDO_LOG_LEVEL_ERROR, "Could open or create dir {$path}");
            }
        }
    }

    function outputMaps($path) {
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
        $path .= $this->model['platform'];
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
        foreach ($this->map as $className => $map) {
            $lcClassName= strtolower($className);
            $fileName= $path . '/' . strtolower($className) . '.map.inc.php';
            $fileContent= "<?php\n\$xpdo_meta_map['$className']= " . var_export($map, true) . ";\n\$xpdo_meta_map['$lcClassName']= & \$xpdo_meta_map['$className'];\n?>";
            if (is_dir($path)) {
                if ($file= @ fopen($fileName, 'wb')) {
                    if (!fwrite($file, $fileContent)) {
                        $this->manager->xpdo->_log(XPDO_LOG_LEVEL_ERROR, "Could not write to file: {$fileName}");
                    }
                    fclose($file);
                } else {
                    $this->manager->xpdo->_log(XPDO_LOG_LEVEL_ERROR, "Could not open or create file: {$fileName}");
                }
            } else {
                $this->manager->xpdo->_log(XPDO_LOG_LEVEL_ERROR, "Could open or create dir {$path}");
            }
        }
    }

    function getClassTemplate() {
        $template= '<?php' . "\n";
        $template .=<<<EOD
class [+class+] extends [+extends+] {
   function [+class+](& \$xpdo) {
       parent :: __construct(\$xpdo);
   }
}

EOD;
        $template .= '?>';
        return $template;
    }
}
