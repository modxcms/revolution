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
 * {@inheritdoc}
 *
 * This modResource derivative represents a traditional web document that stores
 * it's primary content in the modX database container.
 *
 * @todo Determine if this class is unnecessary; modResource represents this
 * default web document and nothing unique is done in this class currently,
 * other than changing the default class_key.
 *
 * @package modx
 */
class modDocument extends modResource {
    /**
     * Overrides modResource::__construct to set the class key for this Resource type
     * @param xPDO $xpdo A reference to the xPDO|modX instance
     */
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->set('class_key','modDocument');
        $this->showInContextMenu = true;
    }
    /**
     * Use this in your extended Resource class to display the text for the context menu item, if showInContextMenu is
     * set to true.
     * @return array
     */
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('document'),
            'text_create_here' => $this->xpdo->lexicon('document_create_here'),
        );
    }
}
