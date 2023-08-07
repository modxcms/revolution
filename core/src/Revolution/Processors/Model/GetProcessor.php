<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Model;


use MODX\Revolution\modAccessibleObject;
use MODX\Revolution\Processors\ModelProcessor;

/**
 * A utility abstract class for defining get-based processors
 *
 * @abstract
 *
 * @package MODX\Revolution
 */
abstract class GetProcessor extends ModelProcessor
{
    /** @var boolean $checkViewPermission If set to true, will check the view permission on modAccessibleObjects */
    public $checkViewPermission = true;

    /**
     * {@inheritDoc}
     * @return boolean|string
     */
    public function initialize()
    {
        $primaryKey = $this->getProperty($this->primaryKeyField, false);
        if (empty($primaryKey)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }
        $this->object = $this->modx->getObject($this->classKey, $primaryKey);
        if (empty($this->object)) {
            return $this->modx->lexicon($this->objectType . '_err_nfs', [$this->primaryKeyField => $primaryKey]);
        }

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
