<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Forms\Set;

use MODX\Revolution\modFormCustomizationSet;
use MODX\Revolution\Processors\Model\ExportProcessor;

/**
 * Export a form customization set.
 * @package MODX\Revolution\Processors\Security\Forms\Set
 */
class Export extends ExportProcessor
{
    public $objectType = 'set';
    public $classKey = modFormCustomizationSet::class;
    public $permission = 'customize_forms';
    public $languageTopics = ['formcustomization'];
    public $data = []; // global store of object data (fields, tabs, tvs)

    public function prepareXml()
    {
        // fetch object data and store it globally for all methods that need it
        $this->data = $this->object->getData();

        // fetch related template object (optional) to get it's name
        $template = $this->object->getOne('Template');

        // write the xml data
        $this->xml->startElement('set');
        $this->xml->writeElement('action', $this->object->get('action'));
        $this->xml->writeElement('template', (!empty($template) ? $template->get('templatename') : ''));
        $this->xml->writeElement('description', $this->object->get('description'));
        $this->xml->writeElement('constraint_field', $this->object->get('constraint_field'));
        $this->xml->writeElement('constraint', $this->object->get('constraint'));
        $this->xml->writeElement('active', $this->object->get('active'));

        $this->addFields();
        $this->addTabs();
        $this->addTVs();

        $this->xml->endElement(); // end set
    }

    public function addFields()
    {

        $this->xml->startElement('fields');
        foreach ($this->data['fields'] as $field) {
            $this->xml->startElement('field');
            $this->xml->writeElement('name', $field['name']);
            $this->xml->writeElement('visible', $field['visible']);
            $this->xml->writeElement('label', $field['label']);
            $this->xml->writeElement('default_value', $field['default_value']);
            $this->xml->endElement(); // end field
        }
        $this->xml->endElement(); // end fields
    }

    public function addTabs()
    {

        $this->xml->startElement('tabs');
        foreach ($this->data['tabs'] as $tab) {
            $this->xml->startElement('tab');
            $this->xml->writeElement('name', $tab['name']);
            $this->xml->writeElement('visible', $tab['visible']);
            $this->xml->writeElement('label', $tab['label']);
            $this->xml->endElement(); // end tab
        }
        $this->xml->endElement(); // end tabs
    }

    public function addTVs()
    {

        $this->xml->startElement('tvs');
        foreach ($this->data['tvs'] as $tv) {
            $this->xml->startElement('tv');
            $this->xml->writeElement('name', $tv['name']);
            $this->xml->writeElement('visible', $tv['visible']);
            $this->xml->writeElement('label', $tv['label']);
            $this->xml->writeElement('default_value', $tv['default_value']);
            $this->xml->writeElement('tab', $tv['tab']);
            $this->xml->writeElement('tab_rank', $tv['rank']);
            $this->xml->endElement(); // end tv
        }
        $this->xml->endElement(); // end tvs
    }

}
