<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;
use xPDO\xPDO;

/**
 * Represents a person or system that will access modX.
 *
 * {@internal Implement a derivative to define the behavior and attributes of
 * an actual user or system that is intended to access modX or a modX service.}
 *
 * @abstract
 * @package MODX\Revolution
 */
abstract class modPrincipal extends xPDOSimpleObject
{
    /** @var modX|xPDO $xpdo */
    public $xpdo;

    /**
     * Stores a collection of key-value pairs identifying policy authority.
     *
     * @var array
     * @access protected
     */
    protected $_attributes = [];

    /**
     * Overrides xPDOObject::remove to delete ACL records before removing the principal.
     * modAccess is abstract and has no table; xPDO cannot cascade-delete it, so we delete
     * from each concrete principal_target table by principal_class and principal.
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors = [])
    {
        $principalClass = get_class($this);
        $principalId = $this->get('id');
        $defaultTargets = implode(',', [
            modAccessContext::class,
            modAccessResourceGroup::class,
            modAccessCategory::class,
            \MODX\Revolution\Sources\modAccessMediaSource::class,
            modAccessNamespace::class,
        ]);
        $targets = explode(',', $this->xpdo->getOption('principal_targets', null, $defaultTargets));
        array_walk($targets, 'trim');

        foreach ($targets as $target) {
            $fields = $this->xpdo->getFields($target);
            $hasPrincipalFields = is_array($fields)
                && array_key_exists('principal_class', $fields)
                && array_key_exists('principal', $fields);
            if (!$hasPrincipalFields) {
                continue;
            }
            $tableName = $this->xpdo->getTableName($target);
            if (empty($tableName)) {
                continue;
            }
            $principalClassField = $this->xpdo->escape('principal_class');
            $principalField = $this->xpdo->escape('principal');
            $quotedClass = $this->xpdo->quote($principalClass);
            $principalIdInt = (int) $principalId;
            $sql = "DELETE FROM {$tableName} WHERE {$principalClassField} = {$quotedClass} "
                . "AND {$principalField} = {$principalIdInt}";
            $this->xpdo->query($sql);
        }

        return parent::remove($ancestors);
    }

    /**
     * Load attributes of the principal that define access to secured objects.
     *
     * {@internal Implement this function in derivatives to control how your
     * user class uses the MODX ABAC (Attribute-Based Access Control) security
     * model}
     *
     * @abstract
     *
     * @param array   $target  The target modAccess classes to load attributes from.
     * @param string  $context Context to check within, defaults to current  context.
     * @param boolean $reload  If true, the attributes will be reloaded and the session updated.
     */
    public function loadAttributes($target, $context = '', $reload = false)
    {
        $this->_attributes = [];
    }

    /**
     * Get the attributes for this principal.
     *
     * @param array   $targets An array of target modAccess classes to load.
     * @param string  $context The context to check within. Defaults to active context.
     * @param boolean $reload  If true, the attributes will be reloaded and the session updated.
     *
     * @return array An array of attributes on the principal
     */
    public function getAttributes($targets = [], $context = '', $reload = false)
    {
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (!is_array($targets) || empty($targets)) {
            $defaultPrincipalTargets = 'MODX\\Revolution\\modAccessContext,'
                . 'MODX\\Revolution\\modAccessResourceGroup,MODX\\Revolution\\modAccessCategory,'
                . 'MODX\\Revolution\\Sources\\modAccessMediaSource,MODX\\Revolution\\modAccessNamespace';
            $targets = explode(',', $this->xpdo->getOption('principal_targets', null, $defaultPrincipalTargets));
            array_walk($targets, 'trim');
        }
        $this->loadAttributes($targets, $context, $reload);

        return $this->_attributes[$context];
    }
}
