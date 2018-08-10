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
 * Restricts or grants access to certain functionality. Grouped by Access
 * Policy Template.
 *
 * @property int $template The Access Policy Template this Permission belongs to
 * @property string $name The key of this Permission
 * @property string $description The description informing what this Permission allows
 * @property boolean $value Whether or not this permission is allowed or denied
 *
 * @package modx
 */
class modAccessPermission extends xPDOSimpleObject {}
