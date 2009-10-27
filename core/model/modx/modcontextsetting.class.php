<?php
/**
 * Represents a context-specific configuration setting.
 *
 * These settings are loaded and will be merged with the already loaded system
 * level settings, then merged again with user level settings for authenticated
 * users.
 *
 * @package modx
 */
class modContextSetting extends xPDOObject {}