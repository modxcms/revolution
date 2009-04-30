<?php
/**
 * Defines an access control policy between a principal and a modResource.
 *
 * {@inheritdoc}
 *
 * @package modx
 * @subpackage mysql
 */
class modAccessResource extends modAccess {
    function modAccessResource(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>