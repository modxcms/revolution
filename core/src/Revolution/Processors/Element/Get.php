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

use MODX\Revolution\Processors\Model\GetProcessor;

/**
 * Abstract class for Get Element processors. To be extended for each derivative element type.
 *
 * @abstract
 *
 * @package MODX\Revolution\Processors\Element
 */
abstract class Get extends GetProcessor
{
    protected $hasStaticFile = false;

    public function initialize()
    {
        // Intitializing parent first, as we need the Element object created before moving forward
        if (parent::initialize()) {
            $className = array_pop(explode('\\', $this->classKey));
            /*
                There is at least one other Element type (modPropertySet) where static files
                do not apply, so here we determine whether static processing will be needed
                based on the Element being created.
            */
            $hasStaticContentOption = in_array(
                $className,
                ['modChunk', 'modPlugin', 'modSnippet', 'modTemplate', 'modTemplateVar']
            );
            if ($hasStaticContentOption && intval($this->getProperty('static', 0)) === 1) {
                $file = $this->getProperty('static_file');
                if (!empty($file)) {
                    $this->hasStaticFile = true;
                    $mediaSourceId = (int)$this->getProperty('source');
                    // When file media source is set to "None"
                    if ($mediaSourceId === 0) {
                        $this->object->ignoreMediaSource = true;
                        if (strpos($file, '/') === 0) {
                            $this->object->staticPathIsAbsolute = true;
                        }
                    }
                    // When there is an assigned media source
                    if ($mediaSourceId > 0) {
                        $this->object->ignoreMediaSource = false;
                        $this->setProperty('static_file', ltrim($file, DIRECTORY_SEPARATOR));
                    }

                    $this->object->staticElementMediaSourceId = $mediaSourceId;
                    $this->object->isStaticElementFile = true;

                    if ($this->object->getSource()) {
                        // Stop if error fetching media source
                        if ($this->object->_source->hasErrors()) {
                            $this->addFieldError('static_file', reset($this->object->_source->getErrors()));
                            return false;
                        }
                    }
                    $this->object->relayStaticPropertiesToMediaSource([
                        'isStaticElementFile',
                        'ignoreMediaSource',
                        'staticPathIsAbsolute'
                    ]);
                }
            }
            return true;
        }
    }

    /**
     * Used for adding custom data in derivative types
     *
     * @return void
     */
    public function beforeOutput()
    {
        $this->getElementProperties();
    }

    /**
     * Get the properties of the element
     *
     * @return array
     */
    public function getElementProperties()
    {
        $properties = $this->object->get('properties');
        if (!is_array($properties)) {
            $properties = [];
        }

        /* process data */
        $data = [];
        foreach ($properties as $property) {
            $data[] = [
                $property['name'],
                $property['desc'],
                !empty($property['type']) ? $property['type'] : 'textfield',
                !empty($property['options']) ? $property['options'] : [],
                $property['value'],
                !empty($property['lexicon']) ? $property['lexicon'] : '',
                false, /* overridden set to false */
                $property['desc_trans'],
                !empty($property['area']) ? $property['area'] : '',
            ];
        }

        $this->object->set('data', '(' . $this->modx->toJSON($data) . ')');

        return $data;
    }
}
