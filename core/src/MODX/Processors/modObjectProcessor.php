<?php

namespace MODX\Processors;

use xPDO\Om\xPDOObject;
use MODX\modAccessibleObject;

/**
 * Base class for object-specific processors
 *
 * @abstract
 */
abstract class modObjectProcessor extends modProcessor
{
    /** @var xPDOObject|modAccessibleObject $object The object being grabbed */
    public $object;
    /** @var string $objectType The object "type", this will be used in various lexicon error strings */
    public $objectType = 'object';
    /** @var string $classKey The class key of the Object to iterate */
    public $classKey;
    /** @var string $primaryKeyField The primary key field to grab the object by */
    public $primaryKeyField = 'id';
    /** @var string $permission The Permission to use when checking against */
    public $permission = '';
    /** @var array $languageTopics An array of language topics to load */
    public $languageTopics = [];


    public function checkPermissions()
    {
        return !empty($this->permission) ? $this->modx->hasPermission($this->permission) : true;
    }


    public function getLanguageTopics()
    {
        return $this->languageTopics;
    }
}