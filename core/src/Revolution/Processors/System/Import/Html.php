<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Import;

use MODX\Revolution\modDocument;

/**
 * @package MODX\Revolution\Processors\System\Import
 */
class Html extends Index
{
    public $classKey = modDocument::class;
    public $allowedFiles = ['html', 'htm', 'xml'];
}
