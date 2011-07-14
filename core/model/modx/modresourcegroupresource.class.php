<?php
/**
 * @package modx
 */
/**
 * A many-to-many relationship between Resources and Resource Groups.
 *
 * @property int $document_group The ID of the Resource Group
 * @property int $document The ID of the Resource
 * @see modResource
 * @see modResourceGroup
 * @package modx
 */
class modResourceGroupResource extends xPDOSimpleObject {}