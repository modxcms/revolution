<?php

/**
 * Align top-menu access policy keys (#14498).
 *
 * - Error Log / Menus / Logout alignment
 * - Parent menus use dedicated keys (menu_media, menu_access, menu_system)
 * - Remove dead keys (about, credits, export_static, menu_security, menu_support, menu_tools)
 *
 * @var modX $modx
 * @package setup
 */

require_once __DIR__ . '/3.3.0-top-menu-access-policy.functions.php';

modxUpgrade330TopMenuAccessPolicy($modx);
