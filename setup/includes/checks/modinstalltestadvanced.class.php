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
        $this->checkAdvPaths();

        return $this->results;
    }

    /**
     * Check memory limit, to make sure it is set at least to 64M for zip status
     */
    function checkZipMemLimit() {
        $success = false;
        $ml = ini_get('memory_limit');
        $bytes = $this->return_bytes($ml);

        if ($bytes < 25165824) { /* 24M = 25165824, 64M = 67108864 */
            $success = @ini_set('memory_limit','128M');
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

    /**
     * Check paths for writability
     */
    function checkAdvPaths() {
        /* web_path */
        $this->results['context_web_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],$this->install->config['web_path']);
        if (!$this->_inWritableContainer($this->install->config['web_path'])) {
            $this->results['context_web_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['context_web_writable']['class'] = 'testFailed';
        } else {
            $this->results['context_web_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['context_web_writable']['class'] = 'testPassed';
        }

        /* mgr_path */
        $this->results['context_mgr_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],$this->install->config['mgr_path']);
        if (!$this->_inWritableContainer($this->install->config['mgr_path'])) {
            $this->results['context_mgr_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['context_mgr_writable']['class'] = 'testFailed';
        } else {
            $this->results['context_mgr_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['context_mgr_writable']['class'] = 'testPassed';
        }

        /* connectors_path */
        $this->results['context_connectors_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],$this->install->config['connectors_path']);
        if (!$this->_inWritableContainer($this->install->config['connectors_path'])) {
            $this->results['context_connectors_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['context_connectors_writable']['class'] = 'testFailed';
        } else {
            $this->results['context_connectors_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['context_connectors_writable']['class'] = 'testPassed';
        }
    }
}