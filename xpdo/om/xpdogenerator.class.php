<?php
/*
 * Copyright 2010-2014 by MODX, LLC.
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
 * @abstract
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
     * @var string $metaTemplate The class platform template string to build
     * the meta class map files from.
     */
    public $metaTemplate= '';
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
     * @var SimpleXMLElement
     */
    public $schema= null;

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
     * Requires SimpleXML for parsing an XML schema.
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
        }

        $this->schema = new SimpleXMLElement($schemaFile, 0, true);
        if (isset($this->schema)) {
            foreach ($this->schema->attributes() as $attributeKey => $attribute) {
                /** @var SimpleXMLElement $attribute */
                $this->model[$attributeKey] = (string) $attribute;
            }
            if (isset($this->schema->object)) {
                foreach ($this->schema->object as $object) {
                    /** @var SimpleXMLElement $object */
                    $class = (string) $object['class'];
                    $extends = isset($object['extends']) ? (string) $object['extends'] : $this->model['baseClass'];
                    $this->classes[$class] = array('extends' => $extends);
                    $this->map[$class] = array(
                        'package' => $this->model['package'],
                        'version' => $this->model['version']
                    );
                    foreach ($object->attributes() as $objAttrKey => $objAttr) {
                        if ($objAttrKey == 'class') continue;
                        $this->map[$class][$objAttrKey]= (string) $objAttr;
                    }
                    
                    $engine = (string) $object['engine'];
                    if (!empty($engine)) {
                        $this->map[$class]['tableMeta'] = array('engine' => $engine);
                    }
                    
                    $this->map[$class]['fields']= array();
                    $this->map[$class]['fieldMeta']= array();
                    if (isset($object->field)) {
                        foreach ($object->field as $field) {
                            $key = (string) $field['key'];
                            $dbtype = (string) $field['dbtype'];
                            $defaultType = $this->manager->xpdo->driver->getPhpType($dbtype);
                            $this->map[$class]['fields'][$key]= null;
                            $this->map[$class]['fieldMeta'][$key]= array();
                            foreach ($field->attributes() as $fldAttrKey => $fldAttr) {
                                $fldAttrValue = (string) $fldAttr;
                                switch ($fldAttrKey) {
                                    case 'key':
                                        continue 2;
                                    case 'default':
                                        if ($fldAttrValue === 'NULL') {
                                            $fldAttrValue = null;
                                        }
                                        switch ($defaultType) {
                                            case 'integer':
                                            case 'boolean':
                                            case 'bit':
                                                $fldAttrValue = (integer) $fldAttrValue;
                                                break;
                                            case 'float':
                                            case 'numeric':
                                                $fldAttrValue = (float) $fldAttrValue;
                                                break;
                                            default:
                                                break;
                                        }
                                        $this->map[$class]['fields'][$key]= $fldAttrValue;
                                        break;
                                    case 'null':
                                        $fldAttrValue = (!empty($fldAttrValue) && strtolower($fldAttrValue) !== 'false') ? true : false;
                                        break;
                                    default:
                                        break;
                                }
                                $this->map[$class]['fieldMeta'][$key][$fldAttrKey]= $fldAttrValue;
                            }
                        }
                    }
                    if (isset($object->alias)) {
                        $this->map[$class]['fieldAliases'] = array();
                        foreach ($object->alias as $alias) {
                            $aliasKey = (string) $alias['key'];
                            $aliasNode = array();
                            foreach ($alias->attributes() as $attrName => $attr) {
                                $attrValue = (string) $attr;
                                switch ($attrName) {
                                    case 'key':
                                        continue 2;
                                    case 'field':
                                        $aliasNode = $attrValue;
                                        break;
                                    default:
                                        break;
                                }
                            }
                            if (!empty($aliasKey) && !empty($aliasNode)) {
                                $this->map[$class]['fieldAliases'][$aliasKey] = $aliasNode;
                            }
                        }
                    }
                    if (isset($object->index)) {
                        $this->map[$class]['indexes'] = array();
                        foreach ($object->index as $index) {
                            $indexNode = array();
                            $indexName = (string) $index['name'];
                            foreach ($index->attributes() as $attrName => $attr) {
                                $attrValue = (string) $attr;
                                switch ($attrName) {
                                    case 'name':
                                        continue 2;
                                    case 'primary':
                                    case 'unique':
                                    case 'fulltext':
                                        $attrValue = (empty($attrValue) || $attrValue === 'false' ? false : true);
                                    default:
                                        $indexNode[$attrName] = $attrValue;
                                        break;
                                }
                            }
                            if (!empty($indexNode) && isset($index->column)) {
                                $indexNode['columns']= array();
                                foreach ($index->column as $column) {
                                    $columnKey = (string) $column['key'];
                                    $indexNode['columns'][$columnKey] = array();
                                    foreach ($column->attributes() as $attrName => $attr) {
                                        $attrValue = (string) $attr;
                                        switch ($attrName) {
                                            case 'key':
                                                continue 2;
                                            case 'null':
                                                $attrValue = (empty($attrValue) || $attrValue === 'false' ? false : true);
                                            default:
                                                $indexNode['columns'][$columnKey][$attrName]= $attrValue;
                                                break;
                                        }
                                    }
                                }
                                if (!empty($indexNode['columns'])) {
                                    $this->map[$class]['indexes'][$indexName]= $indexNode;
                                }
                            }
                        }
                    }
                    if (isset($object->composite)) {
                        $this->map[$class]['composites'] = array();
                        foreach ($object->composite as $composite) {
                            $compositeNode = array();
                            $compositeAlias = (string) $composite['alias'];
                            foreach ($composite->attributes() as $attrName => $attr) {
                                $attrValue = (string) $attr;
                                switch ($attrName) {
                                    case 'alias' :
                                        continue 2;
                                    case 'criteria' :
                                        $attrValue = $this->manager->xpdo->fromJSON(urldecode($attrValue));
                                    default :
                                        $compositeNode[$attrName]= $attrValue;
                                        break;
                                }
                            }
                            if (!empty($compositeNode)) {
                                if (isset($composite->criteria)) {
                                    /** @var SimpleXMLElement $criteria */
                                    foreach ($composite->criteria as $criteria) {
                                        $criteriaTarget = (string) $criteria['target'];
                                        $expression = (string) $criteria;
                                        if (!empty($expression)) {
                                            $expression = $this->manager->xpdo->fromJSON($expression);
                                            if (!empty($expression)) {
                                                if (!isset($compositeNode['criteria'])) $compositeNode['criteria'] = array();
                                                if (!isset($compositeNode['criteria'][$criteriaTarget])) $compositeNode['criteria'][$criteriaTarget] = array();
                                                $compositeNode['criteria'][$criteriaTarget] = array_merge($compositeNode['criteria'][$criteriaTarget], (array) $expression);
                                            }
                                        }
                                    }
                                }
                                $this->map[$class]['composites'][$compositeAlias] = $compositeNode;
                            }
                        }
                    }
                    if (isset($object->aggregate)) {
                        $this->map[$class]['aggregates'] = array();
                        foreach ($object->aggregate as $aggregate) {
                            $aggregateNode = array();
                            $aggregateAlias = (string) $aggregate['alias'];
                            foreach ($aggregate->attributes() as $attrName => $attr) {
                                $attrValue = (string) $attr;
                                switch ($attrName) {
                                    case 'alias' :
                                        continue 2;
                                    case 'criteria' :
                                        $attrValue = $this->manager->xpdo->fromJSON(urldecode($attrValue));
                                    default :
                                        $aggregateNode[$attrName]= $attrValue;
                                        break;
                                }
                            }
                            if (!empty($aggregateNode)) {
                                if (isset($aggregate->criteria)) {
                                    /** @var SimpleXMLElement $criteria */
                                    foreach ($aggregate->criteria as $criteria) {
                                        $criteriaTarget = (string) $criteria['target'];
                                        $expression = (string) $criteria;
                                        if (!empty($expression)) {
                                            $expression = $this->manager->xpdo->fromJSON($expression);
                                            if (!empty($expression)) {
                                                if (!isset($aggregateNode['criteria'])) $aggregateNode['criteria'] = array();
                                                if (!isset($aggregateNode['criteria'][$criteriaTarget])) $aggregateNode['criteria'][$criteriaTarget] = array();
                                                $aggregateNode['criteria'][$criteriaTarget] = array_merge($aggregateNode['criteria'][$criteriaTarget], (array) $expression);
                                            }
                                        }
                                    }
                                }
                                $this->map[$class]['aggregates'][$aggregateAlias] = $aggregateNode;
                            }
                        }
                    }
                    if (isset($object->validation)) {
                        $this->map[$class]['validation'] = array();
                        $validation = $object->validation[0];
                        $validationNode = array();
                        foreach ($validation->attributes() as $attrName => $attr) {
                            $validationNode[$attrName]= (string) $attr;
                        }
                        if (isset($validation->rule)) {
                            $validationNode['rules'] = array();
                            foreach ($validation->rule as $rule) {
                                $ruleNode = array();
                                $field= (string) $rule['field'];
                                $name= (string) $rule['name'];
                                foreach ($rule->attributes() as $attrName => $attr) {
                                    $attrValue = (string) $attr;
                                    switch ($attrName) {
                                        case 'field' :
                                        case 'name' :
                                            continue 2;
                                        default :
                                            $ruleNode[$attrName]= $attrValue;
                                            break;
                                    }
                                }
                                if (!empty($field) && !empty($name) && !empty($ruleNode)) {
                                    $validationNode['rules'][$field][$name]= $ruleNode;
                                }
                            }
                            if (!empty($validationNode['rules'])) {
                                $this->map[$class]['validation'] = $validationNode;
                            }
                        }
                    }
                }
            } else {
                $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Schema {$schemaFile} contains no valid object elements.");
            }
        } else {
            $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not read schema from {$schemaFile}.");
        }

        $om_path= XPDO_CORE_PATH . 'om/';
        $path= !empty ($outputDir) ? $outputDir : $om_path;
        if (isset ($this->model['package']) && strlen($this->model['package']) > 0) {
            $path .= strtr($this->model['package'], '.', '/');
            $path .= '/';
        }
        $this->outputMeta($path);
        $this->outputClasses($path);
        $this->outputMaps($path);
        if ($compile) $this->compile($path, $this->model, $this->classes, $this->maps);
        unset($this->model, $this->classes, $this->map);
        return true;
    }

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
            if ($this->manager->xpdo->getCacheManager()) {
                if (!$this->manager->xpdo->cacheManager->writeTree($path)) {
                    $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not create model directory at {$path}");
                    return false;
                }
            }
        }
        $ppath= $path;
        $ppath .= $platform;
        if (!is_dir($ppath)) {
            $newPlatformGeneration= true;
            if ($this->manager->xpdo->getCacheManager()) {
                if (!$this->manager->xpdo->cacheManager->writeTree($ppath)) {
                    $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not create platform subdirectory {$ppath}");
                    return false;
                }
            }
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
                $fileName= $path . strtolower($className) . '.class.php';
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
     * Write the generated meta map to the specified path.
     *
     * @param string $path An absolute path to write the generated maps to.
     * @return bool
     */
    public function outputMeta($path) {
        if (!is_dir($path)) {
            if ($this->manager->xpdo->getCacheManager()) {
                if (!$this->manager->xpdo->cacheManager->writeTree($path)) {
                    $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not create model directory at {$path}");
                    return false;
                }
            }
        }
        $placeholders = array();

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
        $placeholders = array_merge($placeholders,$model);

        $classMap = array();
//        $skipClasses = array('xPDOObject','xPDOSimpleObject');
        foreach ($this->classes as $className => $meta) {
            if (!isset($meta['extends'])) {
                $meta['extends'] = 'xPDOObject';
            }
            if (!isset($classMap[$meta['extends']])) {
                $classMap[$meta['extends']] = array();
            }
            $classMap[$meta['extends']][] = $className;
        }
        if ($this->manager->xpdo->getCacheManager()) {
            $placeholders['map'] = var_export($classMap,true);
            $replaceVars = array();
            foreach ($placeholders as $varKey => $varValue) {
                if (is_scalar($varValue)) $replaceVars["[+{$varKey}+]"]= $varValue;
            }
            $fileContent= str_replace(array_keys($replaceVars), array_values($replaceVars), $this->getMetaTemplate());
            $this->manager->xpdo->cacheManager->writeFile("{$path}/metadata.{$model['platform']}.php",$fileContent);
        }
        return true;
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
require_once (dirname(dirname(__FILE__)) . '/[+class-lowercase+].class.php');
class [+class+]_$platform extends [+class+] {}
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


    /**
     * Gets the meta template.
     *
     * @access public
     * @return string The meta template.
     */
    public function getMetaTemplate() {
        if ($this->metaTemplate) return $this->metaTemplate;
        $tpl= <<<EOD
<?php
\n\$xpdo_meta_map = [+map+];
EOD;
        return $tpl;
    }
}
