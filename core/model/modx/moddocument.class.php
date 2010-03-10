<?php
/**
 * {@inheritdoc}
 *
 * This modResource derivative represents a traditional web document that stores
 * it's primary content in the modX database container.
 *
 * @todo Determine if this class is unnecessary; modResource represents this
 * default web document and nothing unique is done in this class currently,
 * other than changing the default class_key.
 *
 * @package modx
 */
class modDocument extends modResource {
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->set('class_key','modDocument');
    }
}