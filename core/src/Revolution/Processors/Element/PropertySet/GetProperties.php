<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\PropertySet;


/**
 * Gets properties for a property set
 *
 * @package MODX\Revolution\Processors\Element\PropertySet
 */
class GetProperties extends Get
{
    /** @var string The ID of an element in modElementPropertySet key */
    public $elementKey = 'element';
    /** @var string The class of an element in modElementPropertySet key */
    public $element_class = 'element_class';

    /**
     * No need to do something here
     */
    public function beforeCleanup()
    {
    }

    /**
     * Output properties instead of property set
     *
     * @return array|string
     */
    public function cleanup()
    {
        return $this->success('', $this->props);
    }
}
