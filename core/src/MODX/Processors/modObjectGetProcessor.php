<?php

namespace MODX\Processors;

use MODX\modAccessibleObject;

/**
 * A utility abstract class for defining get-based processors
 *
 * @abstract
 */
abstract class modObjectGetProcessor extends modObjectProcessor
{
    /** @var boolean $checkViewPermission If set to true, will check the view permission on modAccessibleObjects */
    public $checkViewPermission = true;


    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $primaryKey = $this->getProperty($this->primaryKeyField, false);
        if (empty($primaryKey)) return $this->modx->lexicon($this->objectType . '_err_ns');
        $this->object = $this->modx->getObject($this->classKey, $primaryKey);
        if (empty($this->object)) return $this->modx->lexicon($this->objectType . '_err_nfs', [$this->primaryKeyField => $primaryKey]);

        if ($this->checkViewPermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('view')) {
            return $this->modx->lexicon('access_denied');
        }

        return parent::initialize();
    }


    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $this->beforeOutput();

        return $this->cleanup();
    }


    /**
     * Return the response
     *
     * @return array
     */
    public function cleanup()
    {
        return $this->success('', $this->object->toArray());
    }


    /**
     * Used for adding custom data in derivative types
     *
     * @return void
     */
    public function beforeOutput()
    {
    }
}