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
 * Represents a person or system that will access modX.
 *
 * {@internal Implement a derivative to define the behavior and attributes of
 * an actual user or system that is intended to access modX or a modX service.}
 *
 * @abstract
 * @package modx
 */
class modPrincipal extends xPDOSimpleObject {
    /**
     * Stores a collection of key-value pairs identifying policy authority.
     * @var array
     * @access protected
     */
    protected $_attributes = null;

    /**
     * Load attributes of the principal that define access to secured objects.
     *
     * {@internal Implement this function in derivatives to control how your
     * user class uses the MODX ABAC (Attribute-Based Access Control) security
     * model}
     *
     * @abstract
     * @access protected
     * @param string $target The target modAccess classes to load attributes from.
     * @param string $context Context to check within, defaults to current  context.
     * @param boolean $reload If true, the attributes will be reloaded and the session updated.
     */
    public function loadAttributes($target, $context = '', $reload = false) {
        $this->_attributes = array();
    }

    /**
     * Get the attributes for this principal.
     *
     * @param array $targets An array of target modAccess classes to load.
     * @param string $context The context to check within. Defaults to active context.
     * @param boolean $reload If true, the attributes will be reloaded and the session updated.
     * @return array An array of attributes on the principal
     */
    public function getAttributes($targets = array(), $context = '', $reload = false) {
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (!is_array($targets) || empty($targets)) {
            $targets = explode(',', $this->xpdo->getOption('principal_targets', null, 'modAccessContext,modAccessResourceGroup,modAccessCategory,sources.modAccessMediaSource,modAccessNamespace'));
            array_walk($targets, 'trim');
        }
        $this->loadAttributes($targets, $context, $reload);
        return $this->_attributes[$context];
    }
}
