<?php
/**
 * Handles all advanced build-specific checks
 *
 * @package setup
 * @subpackage tests
 */
class modInstallTestAdvanced extends modInstallTest {
    function modInstallTestAdvanced(&$install) {
        $this->__construct($install);
    }
    function __construct(&$install) {
        $this->install =& $install;
    }

    function run($mode = MODX_INSTALL_MODE_NEW) {
        $this->results = parent::run($mode);

        $this->checkZipMemLimit();

        return $this->results;
    }

    /**
     * Check memory limit, to make sure it is set at least to 64M for zip status
     */
    function checkZipMemLimit() {
        $success = false;
        $ml = ini_get('memory_limit');
        $bytes = $this->return_bytes($ml);

        if ($bytes < 67108864) { /* 64M = 67108864 */
            $success = @ini_set('memory_limit','64M');
            $success = $success !== false ? true : false;
        } else {
            $success = true;
        }

        $this->results['zip_memory_limit']['msg'] = '<p>'.$this->install->lexicon['test_zip_memory_limit'].' ';
        if ($success) {
            $this->results['zip_memory_limit']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['zip_memory_limit']['class'] = 'testPassed';
        } else {
            $s = '<span class="notok">'.$this->install->lexicon['failed'].'</span>';
            $s .= '<div class="notes"><p>'.sprintf($this->install->lexicon['test_zip_memory_limit_fail'],$ml).'</p></div>';
            $s .= '</p>';
            $this->results['zip_memory_limit']['msg'] .= $s;
            $this->results['zip_memory_limit']['class'] = 'testFailed';
        }
    }
}