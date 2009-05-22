<?php
/**
 * Handles all upgrades related to Revolution 2.0.0-alpha-2
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

/* add table structure changes here for upgrades to previous Revolution installations */