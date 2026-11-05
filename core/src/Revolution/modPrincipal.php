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
     * Resolve the principal_class value stored on ACL rows for this object.
     *
     * Users store a class_key (including derivatives). Groups and other principals
     * use the package class name, not the mysql driver subclass.
     *
     * @return string
     */
    protected function getPrincipalClassName(): string
    {
        $classKey = (string)$this->get('class_key');
        if ($classKey !== '') {
            return $classKey;
        }

        $class = !empty($this->_class) ? $this->_class : static::class;
        if (str_contains($class, '\\mysql\\')) {
            $class = str_replace('\\mysql\\', '\\', $class);
        }

        return $class;
    }

    /**
     * Delete ACL rows for this principal from every concrete modAccess table.
     *
     * modAccess is abstract and has no table, so xPDO cannot cascade through it.
     * Uses getDescendants(modAccess) so extension packages that register extra ACL
     * classes are included. Does not use principal_targets (that setting controls
     * attribute loading, not storage cleanup).
     *
     * @return bool False if any table cleanup fails
     */
    protected function removePrincipalAcls(): bool
    {
        $principalId = (int)$this->get('id');
        if ($principalId < 1) {
            return true;
        }

        $principalClass = $this->getPrincipalClassName();
        $targets = $this->xpdo->getDescendants(modAccess::class);
        $success = true;

        foreach ($targets as $target) {
            if (empty($this->xpdo->getTableName($target))) {
                continue;
            }
            $fields = $this->xpdo->getFields($target);
            if (
                !is_array($fields)
                || !array_key_exists('principal_class', $fields)
                || !array_key_exists('principal', $fields)
            ) {
                continue;
            }

            $removed = $this->xpdo->removeCollection($target, [
                'principal_class' => $principalClass,
                'principal' => $principalId,
            ]);
            if ($removed === false) {
                $success = false;
                $this->xpdo->log(
                    xPDO::LOG_LEVEL_ERROR,
                    sprintf(
                        '[modPrincipal] Failed removing ACL rows from %s for %s #%d',
                        $target,
                        $principalClass,
                        $principalId
                    )
                );
            }
        }

        return $success;
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
            $defaultTargets = implode(',', [
                modAccessContext::class,
                modAccessResourceGroup::class,
                modAccessCategory::class,
                \MODX\Revolution\Sources\modAccessMediaSource::class,
                modAccessNamespace::class,
            ]);
            $targets = explode(',', $this->xpdo->getOption('principal_targets', null, $defaultTargets));
            $targets = array_map('trim', $targets);
        }
        $this->loadAttributes($targets, $context, $reload);

        return $this->_attributes[$context];
    }
}
