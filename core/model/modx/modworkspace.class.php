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
 * Workspaces are isolated packaging environments. They are currently not used in MODX.
 *
 * @property string $name The name of the Workspace
 * @property string $path The absolute path of the Workspace
 * @property timestamp $created The time this Workspace was created on
 * @property boolean $active Whether or not this Workspace is active
 * @property array $attributes An array of attributes for this Workspace
 *
 * @package modx
 */
class modWorkspace extends xPDOSimpleObject {

    /**
     * Overrides xPDOObject::save to set the createdon date.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag= null) {
        if ($this->_new && !$this->get('created')) {
            $this->set('created', strftime('%Y-%m-%d %H:%M:%S'));
        }
        $saved= parent :: save($cacheFlag);
        return $saved;
    }

    /**
     * Overrides xPDOObject::get() to replace path settings.
     *
     * {@inheritdoc}
     */
    public function get($k, $format = null, $formatTemplate= null) {
        $result= parent :: get($k, $format, $formatTemplate);
        if ($k === 'path' && strpos($result, '{') !== false) {
            $replacements = array();
            foreach ($this->xpdo->config as $key => $value) {
                $_pos = strrpos($key, '_');
                if ($_pos > 0 && (substr($key, $_pos + 1) === 'path')) {
                    $replacements['{' . $key . '}'] = $value;
                }
            }
            $result = str_replace(array_keys($replacements), array_values($replacements), $result);
        }
        return $result;
    }
}
