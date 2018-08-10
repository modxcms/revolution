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
