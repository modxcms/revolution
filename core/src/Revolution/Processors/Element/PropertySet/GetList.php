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


use MODX\Revolution\modElementPropertySet;
use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modPropertySet;
use xPDO\Om\xPDOQuery;

/**
 * Grabs a list of property sets.
 *
 * @property integer $elementId   (optional) If set, will only grab property sets for
 * that element. Will also add a 'default' property set with the element's
 * default properties.
 * @property string  $elementType (optional) The class key of the prior-mentioned
 * element.
 * @property integer $start       (optional) The record to start at. Defaults to 0.
 * @property integer $limit       (optional) The number of records to limit to. Defaults
 * to 10.
 * @property string  $sort        (optional) The column to sort by. Defaults to name.
 * @property string  $dir         (optional) The direction of the sort. Defaults to ASC.
 *
 * @package MODX\Revolution\Processors\Element\PropertySet
 */
class GetList extends GetListProcessor
{
    public $classKey = modPropertySet::class;
    public $objectType = 'propertyset';
    public $permission = 'view_propertyset';
    public $languageTopics = ['propertyset'];

    public $elementId;
    public $elementType;
    public $showNotAssociated;

    /**
     * {@inheritdoc}
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $this->elementId = $this->getProperty('elementId', false);
        $this->elementType = $this->getProperty('elementType', false);

        $c->leftJoin(modElementPropertySet::class, 'Elements', [
            'Elements.element_class' => $this->elementType,
            'Elements.element' => $this->elementId,
            'Elements.property_set = modPropertySet.id',
        ]);

        $this->showNotAssociated = (bool)$this->getProperty('showNotAssociated', false);
        $showAssociated = (bool)$this->getProperty('showAssociated', false);

        if ($this->showNotAssociated) {
            $c->where([
                'Elements.property_set' => null,
            ]);
        } else {
            if ($showAssociated) {
                $c->where([
                    'Elements.property_set:!=' => null,
                ]);
            }
        }

        return $c;
    }

    /**
     * Filter the query by the valueField of MODx.combo.PropertySet to get the initially value displayed right
     *
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $id = $this->getProperty('id', '');
        if (!empty($id)) {
            $c->where([
                $c->getAlias() . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ]);
        }

        return $c;
    }

    /**
     * If limiting to an Element, get default properties
     *
     * @param array $list
     *
     * @return array
     */
    public function beforeIteration(array $list)
    {
        if ($this->elementId && $this->elementType && !$this->showNotAssociated) {
            $properties = [];
            $element = $this->modx->getObject($this->elementType, $this->elementId);
            if ($element) {
                $properties = $element->get('properties');
                if (!is_array($properties)) {
                    $properties = [];
                }
            }
            $list[] = [
                'id' => 0,
                'name' => $this->modx->lexicon('default'),
                'description' => '',
                'properties' => $properties,
            ];
        }

        return $list;
    }
}
