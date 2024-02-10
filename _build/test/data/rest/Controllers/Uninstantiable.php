<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
 * @subpackage data
 * @phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
 */

use MODX\Revolution\Rest\modRestController;

class modRestServiceTestUninstantiable extends modRestController
{
    private function __construct()
    {
    }
}
