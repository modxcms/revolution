<?php
/**
 * Defines an access control policy between a principal and a modAction.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modAccessAction extends modAccess {
    function modAccessAction(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>