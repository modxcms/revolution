<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * @package modx
 * @subpackage setup
 */
interface modInstallParser {
    public function render($tpl);
    public function set($key,$value);
}
