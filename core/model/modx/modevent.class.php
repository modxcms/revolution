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
 * Represents a system or user-defined event that can be invoked.
 *
 * @property string $name The name of the Event
 * @property boolean $service Whether or not this is a service event
 * @property string $groupname The group of the event
 *
 * @package modx
 * @todo Remove deprecated variables, delegating to the plugins themselves which
 * will allow chained and dependent execution of sequenced plugins or even sets
 * of nested plugins
 */
class modEvent extends xPDOObject {}
