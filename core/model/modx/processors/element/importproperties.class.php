<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Import properties from a file
 *
 * @package modx
 * @subpackage processors.element
 */
class modElementImportPropertiesProcessor extends modProcessor {
    public $file = array();

    public function checkPermissions() {
        return $this->modx->hasPermission('view_propertyset');
    }
    public function getLanguageTopics() {
        return array('propertyset','element');
    }

    public function initialize() {
        $this->file = $this->getProperty('file');
        /* verify file exists */
        if (empty($this->file)) return $this->modx->lexicon('properties_import_err_upload');
        if (empty($this->file) || !empty($this->file['error'])) return $this->modx->lexicon('properties_import_err_upload');
        return true;
    }

    public function process() {
        $o = @file_get_contents($this->file['tmp_name']);
        if (empty($o)) {
            return $this->failure($this->modx->lexicon('properties_import_err_upload'));
        }

        $properties = $this->modx->fromJSON($o);
        if (empty($properties) || !is_array($properties)) {
            return $this->failure($this->modx->lexicon('properties_import_err_invalid'));
        }

        $data = array();
        foreach ($properties as $property) {
            $property['desc'] = empty($property['desc']) ? '' : $property['desc'];
            if (!empty($property['lexicon'])) {
                $this->modx->lexicon->load($property['lexicon']);
            }


            /* backwards compat */
            if (empty($property['desc'])) {
                $property['desc'] = empty($property['description']) ? '' : $property['description'];
            }

            $property['desc'] = modX :: replaceReserved(
                $property['desc'],
                array(
                    "\\n" => '',
                    '\"' => '&quot;',
                    "'" => '"',
                    '<' => "&lt;",
                    '>' => "&gt;",
                    '[' => '&#91;',
                    ']' => '&#93;')
            );
            $property['desc_trans'] = $this->modx->lexicon($property['desc']);
            $property['value'] = modX :: replaceReserved(
                $property['value'],
                array('<' => "&lt;", '>' => "&gt;")
            );
            $data[] = array(
                $property['name'],
                $property['desc'],
                $property['xtype'],
                $property['options'],
                $property['value'],
                !empty($property['lexicon']) ? $property['lexicon'] : '',
                false, /* overridden set to false */
                $property['desc_trans'],
                !empty($property['area']) ? $property['area'] : '',
            );
        }
        return $this->success('',$data);
    }
}
return 'modElementImportPropertiesProcessor';
