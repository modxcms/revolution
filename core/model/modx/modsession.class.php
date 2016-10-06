<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * @package modx
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use xPDO\Om\xPDOObject;

/**
 * Represents a client session managed by MODX.
 *
 * @property string $id The PHP session ID
 * @property int $access The last time this session was accessed
 * @property string $data The serialized data of this session
 * @see modSessionHandler
 * @package modx
 * @extends xPDOObject
 */
class modSession extends xPDOObject {}
