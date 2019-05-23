<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;

/**
 * A class map for storing class relationships.
 *
 * @property string $class        The name of the class
 * @property string $parent_class The parent class this class extends
 * @property string $name_field   The name of the unique field to grab as a title for this field
 * @property string $path         The path to the class file
 * @property string $lexicon      Any lexicon items to load for this class
 *
 * @deprecated Use $modx->getDescendants as of 2.2.
 *
 * @package    MODX\Revolution
 */
class modClassMap extends xPDOSimpleObject
{
}
