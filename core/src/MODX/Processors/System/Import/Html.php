<?php

namespace MODX\Processors\System\Import;

/**
 * @package modx
 * @subpackage processors.system.import
 */

class Html extends Index
{

    public $classKey = 'modDocument';
    public $allowedFiles = [
        'html',
        'htm',
        'xml',
    ];
}
