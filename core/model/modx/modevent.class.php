<?php
/**
 * modEvent
 *
 * @package modx
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