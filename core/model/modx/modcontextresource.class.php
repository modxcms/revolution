<?php
/**
 * @package modx
 */
/**
 * Represents a relationship between a modContext and a modResource.
 *
 * @property string $context_key The key of the Context
 * @property int $resource The ID of the related Resource
 * 
 * @package modx
 * @todo Work this relationship into use in the manager and the logic of each
 * {@link modResource::process()} implementation.
 */
class modContextResource extends xPDOObject {}