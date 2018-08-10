<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Represents a database-based registry queue.
 *
 * @property string $name The key name of the queue
 * @property array $options An array of configuration options for the queue
 *
 * @see modDbRegisterTopic
 * @see modDbRegisterMessage
 *
 * @package modx
 * @subpackage registry.db
 */
class modDbRegisterQueue extends xPDOSimpleObject {}
