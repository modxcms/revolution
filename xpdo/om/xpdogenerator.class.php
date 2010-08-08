<?php
/*
 * Copyright 2006-2010 by  Jason Coward <xpdo@opengeek.com>
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
 * Class for reverse and forward engineering xPDO domain models.
 *
 * @package xpdo
 * @subpackage om
 */

/**
 * A service for reverse and forward engineering xPDO domain models.
 *
 * This service utilizes an xPDOManager instance to generate class stub and
 * meta-data map files from a provided vanilla XML schema of a database
 * structure.  It can also reverse-engineer XML schemas from an existing
 * database.
 *
 * @package xpdo
 * @subpackage om
 */
abstract class xPDOGenerator {
    /**
     * @var xPDOManager $manager A reference to the xPDOManager using this
     * generator.
     */
    public $manager= null;
    /**
     * @var xPDOSchemaManager $schemaManager
     */
    public $schemaManager= null;
    /**
     * @var xmlParser $xmlParser
     */
    public $xmlParser= null;
    /**
     * @var string $outputDir The absolute path to output the class and map
     * files to.
     */
    public $outputDir= '';
    /**
     * @var string $schemaFile An absolute path to the schema file.
     */
    public $schemaFile= '';
    /**
     * @var string $schemaContent The stored content of the newly-created schema
     * file.
     */
    public $schemaContent= '';
    /**
     * @var string $classTemplate The class template string to build the class
     * files from.
     */
    public $classTemplate= '';
    /**
     * @var string $platformTemplate The class platform template string to build
     * the class platform files from.
     */
    public $platformTemplate= '';
    /**
     * @var string $mapHeader The map header string to build the map files from.
     */
    public $mapHeader= '';
    /**
     * @var string $mapFooter The map footer string to build the map files from.
     */
    public $mapFooter= '';
    /**
     * @var array $model The stored model array.
     */
    public $model= array ();
    /**
     * @var array $classes The stored classes array.
     */
    public $classes= array ();
    /**
     * @var array $map The stored map array.
     */
    public $map= array ();

    /**
     * @var string $className A placeholder for the current class name.
     */
    public $className= '';
    /**
     * @var string $fieldKey A placeholder for the current field key.
     */
    public $fieldKey= '';
    /**
     * @var string $indexName A placeholder for the current index name.
     */
    public $indexName= '';

    /**
     * Constructor
     *
     * @access protected
     * @param xPDOManager &$manager A reference to a valid xPDOManager instance.
     * @return xPDOGenerator
     */
    public function __construct(& $manager) {
        $this->manager= & $manager;
    }

    /**
     * Formats a class name to a specific value, stripping the prefix if
     * specified.
     *
     * @access public
     * @param string $string The name to format.
     * @param string $prefix If specified, will strip the prefix out of the
     * first argument.
     * @param boolean $prefixRequired If true, will return a blank string if the
     * prefix specified is not found.
     * @return string The formatting string.
     */
    public function getTableName($string, $prefix= '', $prefixRequired= false) {
        if (!empty($prefix) && strpos($string, $prefix) === 0) {
            $string= substr($string, strlen($prefix));
        }
        elseif ($prefixRequired) {
            $string= '';
        }
        return $string;
    }

    /**
     * Gets a class name from a table name by splitting the string by _ and
     * capitalizing each token.
     *
     * @access public
     * @param string $string The table name to format.
     * @return string The formatted string.
     */
    public function getClassName($string) {
        if (is_string($string) && $strArray= explode('_', $string)) {
            $return= '';
            while (list($k, $v)= each($strArray)) {
                $return.= strtoupper(substr($v, 0, 1)) . substr($v, 1) . '';
            }
            $string= $return;
        }
        return trim($string);
    }

    /**
     * Format the passed default value as an XML attribute.
     *
     * Override this in different PDO driver implementations if necessary.
     *
     * @access public
     * @param string $value The value to encapsulate in the default tag.
     * @return string The parsed XML string
     */
    public function getDefault($value) {
        $return= '';
        if ($value !== null) {
            $return= ' default="'.$value.'"';
        }
        return $return;
    }

    /**
     * Format the passed database index value as an XML attribute.
     *
     * @abstract Implement this for specific PDO driver implementations.
     * @access public
     * @param string $index The DB representation string of the index
     * @return string The formatted XML attribute string
     */
    abstract public function getIndex($index);

    /**
     * Parses an XPDO XML schema and generates classes and map files from it.
     *
     * @param string $schemaFile The name of the XML file representing the
     * schema.
     * @param string $outputDir The directory in which to generate the class and
     * map files into.
     * @param boolean $compile Create compiled copies of the classes and maps from the schema.
     * @return boolean True on success, false on failure.
     */
    public function parseSchema($schemaFile, $outputDir= '', $compile= false) {
        $this->schemaFile= $schemaFile;
        $this->classTemplate= $this->getClassTemplate();
        if (!is_file($schemaFile)) {
            $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not find specified XML schema file {$schemaFile}");
            return false;
        } else {
            $fileContent= @ file($schemaFile);
            $this->schemaContent= implode('', $fileContent);
        }

        /* Create the parser and set handlers. */
        $this->xmlParser= xml_parser_create('UTF-8');

        xml_set_object($this->xmlParser, $this);
        xml_parser_set_option($this->xmlParser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($this->xmlParser, XML_OPTION_TARGET_ENCODING, 'UTF-8');
        xml_set_element_handler($this->xmlParser, '_handleOpenElement', '_handleCloseElement');
        xml_set_character_data_handler($this->xmlParser, "_handleCData");

        /* Parse it. */
        if (!xml_parse($this->xmlParser, $this->schemaContent)) {
            $ln= xml_get_current_line_number($this->xmlParser);
            $msg= xml_error_string(xml_get_error_code($this->xmlParser));
            $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error parsing XML schema on line $ln: $msg");
            return false;
        }

        /* Free up the parser and clear memory */
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
        if ($compile) $this->compile($path, $this->model, $this->classes, $this->maps);
        unset($this->model, $this->classes, $this->map);
        return true;
    }

    /**
     * Handles formatting of the open XML element.
     *
     * @access protected
     * @param xmlParser &$parser
     * @param string &$element
     * @param array &$attributes
     */
    protected function _handleOpenElement(& $parser, & $element, & $attributes) {
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
                            if (isset ($this->model['package'])) {
                                $this->map[$this->className]['package']= $this->model['package'];
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
                $dbtype = 'varchar';
                while (list ($attrName, $attrValue)= each($attributes)) {
                    switch ($attrName) {
                        case 'key' :
                            $this->fieldKey= "{$attrValue}";
                            $this->map[$this->className]['fields'][$this->fieldKey]= null;
                            $this->map[$this->className]['fieldMeta'][$this->fieldKey]= array ();
                            break;
                        case 'default' :
                            $attrValue = ($attrValue === 'NULL' ? null : $attrValue);
                            switch ($this->manager->getPhpType($dbtype)) {
                                case 'integer':
                                case 'boolean':
                                case 'bit':
                                    $attrValue = (integer) $attrValue;
                                    break;
                                case 'float':
                                case 'numeric':
                                    $attrValue = (float) $attrValue;
                                    break;
                                default:
                                    break;
                            }
                            $this->map[$this->className]['fields'][$this->fieldKey]= $attrValue;
                            $this->map[$this->className]['fieldMeta'][$this->fieldKey]['default']= $attrValue;
                            break;
                        case 'null' :
                            $attrValue = ($attrValue && $attrValue !== 'false' ? true : false);
                        default :
                            if ($attrName == 'dbtype') $dbtype = $attrValue;
                            $this->map[$this->className]['fieldMeta'][$this->fieldKey][$attrName]= $attrValue;
                            break;
                    }
                }
                break;
            case 'index' :
                $node= array ();
                while (list ($attrName, $attrValue)= each($attributes)) {
                    switch ($attrName) {
                        case 'name':
                            $this->indexName= $attrValue;
                            break;
                        case 'primary':
                        case 'unique':
                        case 'fulltext':
                            $attrValue = (empty($attrValue) || $attrValue === 'false' ? false : true);
                        default:
                            $node[$attrName] = $attrValue;
                            break;
                    }
                }
                if ($node) {
                    $node['columns']= array();
                    $this->map[$this->className]['indexes'][$this->indexName]= $node;
                }
                break;
            case 'column' :
                $key = '';
                $node = array ();
                while (list($attrName, $attrValue)= each($attributes)) {
                    switch ($attrName) {
                        case 'key':
                            $key= $attrValue;
                            break;
                        case 'null':
                            $attrValue = (empty($attrValue) || $attrValue === 'false' ? false : true);
                        default:
                            $node[$attrName]= $attrValue;
                            break;
                    }
                }
                if ($key) {
                    $this->map[$this->className]['indexes'][$this->indexName]['columns'][$key]= $node;
                }
                break;
            case 'aggregate' :
                $alias= '';
                $node= array ();
                while (list ($attrName, $attrValue)= each($attributes)) {
                    switch ($attrName) {
                        case 'alias' :
                            $alias= "{$attrValue}";
                            break;
                        default :
                            $node[$attrName]= $attrValue;
                            break;
                    }
                }
                if ($alias && $node) {
                    $this->map[$this->className]['aggregates'][$alias]= $node;
                }
                break;
            case 'composite' :
                $alias= '';
                $node= array ();
                while (list ($attrName, $attrValue)= each($attributes)) {
                    switch ($attrName) {
                        case 'alias' :
                            $alias= "{$attrValue}";
                            break;
                        default :
                            $node[$attrName]= $attrValue;
                            break;
                    }
                }
                if ($alias && $node) {
                    $this->map[$this->className]['composites'][$alias]= $node;
                }
                break;
            case 'validation' :
                $node= array ();
                while (list ($attrName, $attrValue)= each($attributes)) {
                    $node[$attrName]= $attrValue;
                }
                if ($node) {
                    $node['rules']= array();
                    $this->map[$this->className]['validation']= $node;
                }
                break;
            case 'rule' :
                $field= '';
                $name= '';
                $node= array ();
                while (list ($attrName, $attrValue)= each($attributes)) {
                    switch ($attrName) {
                        case 'field' :
                            $field= "{$attrValue}";
                            break;
                        case 'name' :
                            $name= "{$attrValue}";
                            break;
                        default :
                            $node[$attrName]= $attrValue;
                            break;
                    }
                }
                if ($field && $name && $node) {
                    $this->map[$this->className]['validation']['rules'][$field][$name]= $node;
                }
                break;
        }
    }

    /**
     * Handles the closing of XML tags.
     *
     * @access protected
     */
    protected function _handleCloseElement(& $parser, & $element) {}

    /**
     * Handles the XML CDATA tags
     *
     * @access protected
     */
    protected function _handleCData(& $parser, & $data) {}


    /**
     * Write the generated class files to the specified path.
     *
     * @access public
     * @param string $path An absolute path to write the generated class files
     * to.
     */
    public function outputClasses($path) {
        $newClassGeneration= false;
        $newPlatformGeneration= false;
        $platform= $this->model['platform'];
        if (!is_dir($path)) {
            $newClassGeneration= true;
            mkdir($path, 0777);
        }
        $ppath= $path;
        $ppath .= $platform;
        if (!is_dir($ppath)) {
            $newPlatformGeneration= true;
            mkdir($ppath, 0777);
        }
        $model= $this->model;
        if (isset($this->model['phpdoc-package'])) {
            $model['phpdoc-package']= '@package ' . $this->model['phpdoc-package'];
            if (isset($this->model['phpdoc-subpackage']) && !empty($this->model['phpdoc-subpackage'])) {
                $model['phpdoc-subpackage']= '@subpackage ' . $this->model['phpdoc-subpackage'] . '.' . $this->model['platform'];
            } else {
                $model['phpdoc-subpackage']= '@subpackage ' . $this->model['platform'];
            }
        } else {
            $basePos= strpos($this->model['package'], '.');
            $package= $basePos
                ? substr($this->model['package'], 0, $basePos)
                : $this->model['package'];
            $subpackage= $basePos
                ? substr($this->model['package'], $basePos + 1)
                : '';
            $model['phpdoc-package']= '@package ' . $package;
            if ($subpackage) $model['phpdoc-subpackage']= '@subpackage ' . $subpackage;
        }
        foreach ($this->classes as $className => $classDef) {
            $newClass= false;
            $classDef['class']= $className;
            $classDef['class-lowercase']= strtolower($className);
            $classDef= array_merge($model, $classDef);
            $replaceVars= array ();
            foreach ($classDef as $varKey => $varValue) {
                if (is_scalar($varValue)) $replaceVars["[+{$varKey}+]"]= $varValue;
            }
            $fileContent= str_replace(array_keys($replaceVars), array_values($replaceVars), $this->classTemplate);
            if (is_dir($path)) {
                $fileName= $path . '/' . strtolower($className) . '.class.php';
                if (!file_exists($fileName)) {
                    if ($file= @ fopen($fileName, 'wb')) {
                        if (!fwrite($file, $fileContent)) {
                            $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not write to file: {$fileName}");
                        }
                        $newClass= true;
                        @fclose($file);
                    } else {
                        $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not open or create file: {$fileName}");
                    }
                } else {
                    $newClass= false;
                    $this->manager->xpdo->log(xPDO::LOG_LEVEL_INFO, "Skipping {$fileName}; file already exists.\nMove existing class files to regenerate them.");
                }
            } else {
                $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not open or create dir: {$path}");
            }
            $fileContent= str_replace(array_keys($replaceVars), array_values($replaceVars), $this->getClassPlatformTemplate($platform));
            if (is_dir($ppath)) {
                $fileName= $ppath . '/' . strtolower($className) . '.class.php';
                if (!file_exists($fileName)) {
                    if ($file= @ fopen($fileName, 'wb')) {
                        if (!fwrite($file, $fileContent)) {
                            $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not write to file: {$fileName}");
                        }
                        @fclose($file);
                    } else {
                        $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not open or create file: {$fileName}");
                    }
                } else {
                    $this->manager->xpdo->log(xPDO::LOG_LEVEL_INFO, "Skipping {$fileName}; file already exists.\nMove existing class files to regenerate them.");
                    if ($newClassGeneration || $newClass) $this->manager->xpdo->log(xPDO::LOG_LEVEL_WARN, "IMPORTANT: {$fileName} already exists but you appear to have generated classes with an older xPDO version.  You need to edit your class definition in this file to extend {$className} rather than {$classDef['extends']}.");
                }
            } else {
                $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not open or create dir: {$path}");
            }
        }
    }

    /**
     * Write the generated class maps to the specified path.
     *
     * @access public
     * @param string $path An absolute path to write the generated maps to.
     */
    public function outputMaps($path) {
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }
        $path .= $this->model['platform'];
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }
        $model= $this->model;
        if (isset($this->model['phpdoc-package'])) {
            $model['phpdoc-package']= '@package ' . $this->model['phpdoc-package'];
            if (isset($this->model['phpdoc-subpackage']) && !empty($this->model['phpdoc-subpackage'])) {
                $model['phpdoc-subpackage']= '@subpackage ' . $this->model['phpdoc-subpackage'] . '.' . $this->model['platform'];
            } else {
                $model['phpdoc-subpackage']= '@subpackage ' . $this->model['platform'];
            }
        } else {
            $basePos= strpos($this->model['package'], '.');
            $package= $basePos
                ? substr($this->model['package'], 0, $basePos)
                : $this->model['package'];
            $subpackage= $basePos
                ? substr($this->model['package'], $basePos + 1) . '.' . $this->model['platform']
                : $this->model['platform'];
            $model['phpdoc-package']= '@package ' . $package;
            $model['phpdoc-subpackage']= '@subpackage ' . $subpackage;
        }
        foreach ($this->map as $className => $map) {
            $lcClassName= strtolower($className);
            $fileName= $path . '/' . strtolower($className) . '.map.inc.php';
            $vars= array_merge($model, $map);
            $replaceVars= array ();
            foreach ($vars as $varKey => $varValue) {
                if (is_scalar($varValue)) $replaceVars["[+{$varKey}+]"]= $varValue;
            }
            $fileContent= str_replace(array_keys($replaceVars), array_values($replaceVars), $this->getMapHeader());
            $fileContent.= "\n\$xpdo_meta_map['$className']= " . var_export($map, true) . ";\n";
            $fileContent.= str_replace(array_keys($replaceVars), array_values($replaceVars), $this->getMapFooter());
            if (is_dir($path)) {
                if ($file= @ fopen($fileName, 'wb')) {
                    if (!fwrite($file, $fileContent)) {
                        $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not write to file: {$fileName}");
                    }
                    fclose($file);
                } else {
                    $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not open or create file: {$fileName}");
                }
            } else {
                $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not open or create dir: {$path}");
            }
        }
    }

    /**
     * Compile the packages into a single file for quicker loading.
     *
     * @abstract
     * @access public
     * @param string $path The absolute path to compile into.
     * @return boolean True if the compiling went successfully.
     */
    abstract public function compile($path= '');

    /**
     * Return the class template for the class files.
     *
     * @access public
     * @return string The class template.
     */
    public function getClassTemplate() {
        if ($this->classTemplate) return $this->classTemplate;
        $template= <<<EOD
<?php
class [+class+] extends [+extends+] {}
?>
EOD;
        return $template;
    }

    /**
     * Return the class platform template for the class files.
     *
     * @access public
     * @return string The class platform template.
     */
    public function getClassPlatformTemplate($platform) {
        if ($this->platformTemplate) return $this->platformTemplate;
        $template= <<<EOD
<?php
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\\\', '/') . '/[+class-lowercase+].class.php');
class [+class+]_$platform extends [+class+] {}
?>
EOD;
        return $template;
    }

    /**
     * Gets the map header template.
     *
     * @access public
     * @return string The map header template.
     */
    public function getMapHeader() {
        if ($this->mapHeader) return $this->mapHeader;
        $header= <<<EOD
<?php
EOD;
        return $header;
    }

    /**
     * Gets the map footer template.
     *
     * @access public
     * @return string The map footer template.
     */
    public function getMapFooter() {
        if ($this->mapFooter) return $this->mapFooter;
        return '';
    }
}
