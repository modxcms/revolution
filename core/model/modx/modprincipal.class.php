<?php
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
     * user class uses the MODx ABAC (Attribute-Based Access Control) security
     * model}
     *
     * @abstract
     * @access protected
     * @param string|array $targets The target modAccess classes to load
     * attributes from.
     * @param string $context Context to check within, defaults to current
     * context.
     * @param boolean $reload If true, the attributes will be reloaded and
     * the session updated.
     */
    public function loadAttributes($target, $context = '', $reload = false) {
        $this->_attributes = array();
    }

    public function getAttributes($targets = array(), $context = '', $reload = false) {
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (is_null($targets) || empty($targets))
            $targets = array('modAccessContext', 'modAccessResourceGroup', 'modAccessCategory');
        if (is_array($targets)) {
            foreach ($targets as $target) {
                $this->loadAttributes($target, $context, $reload);
            }
        }
        elseif (is_string($targets)) {
            $this->loadAttributes($targets, $context, $reload);
        }
        return $this->_attributes[$context];
    }
}