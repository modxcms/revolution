<?php
/**
 * Defines an access control policy between a principal and a modMenu.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modAccessMenu extends modAccess {
    function modAccessMenu(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>