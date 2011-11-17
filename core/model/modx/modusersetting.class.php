<?php
/**
 * @package modx
 */
/**
 * Represents a user setting which overrides system and context settings.
 *
 *
 * @param int $user The ID of the User
 * @property string $key The key of the Setting
 * @property string $value The value of the Setting
 * @property string $xtype The xtype that is used to render the Setting input in the manager
 * @property string $namespace The Namespace of the setting
 * @property string $area The area of the Setting
 * @property timestamp $editedon The last edited on time of this Setting
 * @package modx
 */
class modUserSetting extends xPDOObject {}