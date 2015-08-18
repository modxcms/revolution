<?php
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
