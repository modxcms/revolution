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
 * Represents a database-based registry message.
 *
 * @param int $topic The topic this message belongs to
 * @param string $id The ID of the message
 * @param datetime $created The time this message was created
 * @param datetime $valid The time this message was validated
 * @param int $accessed The UNIX timestamp when this message was accessed
 * @param int $expires The UNIX timestamp when this message will automatically expire
 * @param string $payload The payload of this message
 * @param boolean Whether or not to kill the message
 *
 * @see modDbRegisterQueue
 * @see modDbRegisterTopic
 *
 * @package modx
 * @subpackage registry.db
 */
class modDbRegisterMessage extends xPDOObject {}
