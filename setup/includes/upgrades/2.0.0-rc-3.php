<?php
/**
 * Specific upgrades for Revolution 2.0.0-rc-3
 *
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(


);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);
