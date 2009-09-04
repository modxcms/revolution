<?php
/**
 * Defines an access control policy between a principal and a modResourceGroup.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modAccessResourceGroup extends modAccess {
    function modAccessResourceGroup(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>