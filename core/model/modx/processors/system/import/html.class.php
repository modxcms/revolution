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
 * @package modx
 * @subpackage processors.system.import
 */

include_once dirname(__FILE__) . '/index.class.php';
class modSystemImportHtmlProcessor extends modSystemImportIndexProcessor {

    public $classKey = 'modDocument';
    public $allowedFiles = array(
        'html',
        'htm',
        'xml'
    );
}

return 'modSystemImportHtmlProcessor';
