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
 * A modResource derivative the represents a symbolic link.
 *
 * {@inheritdoc}
 *
 * @package modx
 * @extends modResource
 */
class modSymLink extends modResource implements modResourceInterface {
    /**
     * Overrides modResource::__construct to set the class key for this Resource type
     * @param xPDO $xpdo A reference to the xPDO|modX instance
     */
    function __construct(xPDO & $xpdo) {
        parent :: __construct($xpdo);
        $this->set('type', 'reference');
        $this->set('class_key', 'modSymLink');
        $this->showInContextMenu = true;
    }

    /**
     * Process the modSymLink and forward to the specified resource.
     *
     * {@inheritDoc}
     */
    public function process() {
        $this->_content= $this->get('content');
        if (empty ($this->_content) || $this->get('id') == $this->_content) {
            $this->xpdo->sendErrorPage();
        }
        if (is_numeric($this->_content)) {
            $this->_output= intval($this->_content);
        } else {
            $this->xpdo->getParser();
            $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
            $this->xpdo->parser->processElementTags($this->_tag, $this->_content, true, true, '[[', ']]', array(), $maxIterations);
        }
        if (is_numeric($this->_content)) {
            $this->_output= intval($this->_content);
        } else {
            $this->_output= $this->_content;
        }
        $forwardOptions = array('merge' => $this->xpdo->getOption('symlink_merge_fields', null, true));
        $this->xpdo->sendForward($this->_output, $forwardOptions);
    }

    /**
     * Gets the manager controller path for the Symlink
     * @static
     * @param xPDO $modx A reference to the modX instance
     * @return string
     */
    public static function getControllerPath(xPDO &$modx) {
        $path = modResource::getControllerPath($modx);
        return $path.'symlink/';
    }

    /**
     * Use this in your extended Resource class to display the text for the context menu item, if showInContextMenu is
     * set to true.
     * @return array
     */
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('symlink'),
            'text_create_here' => $this->xpdo->lexicon('symlink_create_here'),
        );
    }

    /**
     * Use this in your extended Resource class to return a translatable name for the Resource Type.
     * @return string
     */
    public function getResourceTypeName() {
        return $this->xpdo->lexicon('symlink');
    }
}
