<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element;


use MODX\Revolution\Processors\Model\DuplicateProcessor;

/**
 * Abstract class for Duplicate Element processors. To be extended for each derivative element type.
 *
 * @abstract
 *
 * @package MODX\Revolution\Processors\Element
 */
class Duplicate extends DuplicateProcessor
{
    public function cleanup()
    {
        $fields = $this->newObject->get(['id', 'name', 'description', 'category', 'locked']);
        $fields['redirect'] = (boolean)$this->getProperty('redirect', false);

        return $this->success('', $fields);
    }

    public function afterSave()
    {
        if ($this->getProperty('clearCache')) {
            $this->modx->cacheManager->refresh();
        }

        return parent::afterSave();
    }
}
