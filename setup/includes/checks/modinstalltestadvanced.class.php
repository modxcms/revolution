<?php
/**
 * Handles all advanced build-specific checks
 *
 * @package setup
 * @subpackage tests
 */
class modInstallTestAdvanced extends modInstallTest {

    public function run($mode = modInstall::MODE_NEW) {
        $this->results = parent::run($mode);

        $this->_checkZipMemLimit();
        $this->_checkAdvPaths();

        return $this->results;
    }

    /**
     * Check memory limit, to make sure it is set at least to 64M for zip status
     */
    protected function _checkZipMemLimit() {
        $success = false;
        $ml = ini_get('memory_limit');
        $bytes = $this->_returnBytes($ml);

        if ($bytes < 25165824) { /* 24M = 25165824, 64M = 67108864 */
            $success = @ini_set('memory_limit','128M');
            $success = $success !== false ? true : false;
        } else {
            $success = true;
        }

        $this->title('zip_memory_limit',$this->install->lexicon('test_zip_memory_limit').' ');
        if ($success) {
            $this->pass('zip_memory_limit',$this->install->lexicon('test_memory_limit_success',array('memory' => $ml)));
        } else {
            $this->fail('zip_memory_limit','',$this->install->lexicon('test_zip_memory_limit_fail',array('memory' => $ml)));
        }
    }

    /**
     * Check paths for writability
     */
    protected function _checkAdvPaths() {
        /* web_path */
        $this->title('context_web_writable',$this->install->lexicon('test_directory_writable',array('dir' => $this->install->settings->get('context_web_path'))));
        $webDir = $this->install->settings->get('context_web_path');
        if (!$this->is_writable2($webDir)) {
            $this->fail('context_web_writable');
        } else {
            $this->pass('context_web_writable');
        }

        /* mgr_path */
        $this->title('context_mgr_writable',$this->install->lexicon('test_directory_writable',array('dir' => $this->install->settings->get('context_mgr_path'))));
        $mgrDir = dirname($this->install->settings->get('context_mgr_path'));
        if (!$this->is_writable2($mgrDir)) {
            $this->fail('context_mgr_writable');
        } else {
            $this->pass('context_mgr_writable');
        }

        /* connectors_path */
        $this->title('context_connectors_writable',$this->install->lexicon('test_directory_writable',array('dir' => $this->install->settings->get('context_connectors_path'))));
        $conDir = dirname($this->install->settings->get('context_connectors_path'));
        if (!$this->is_writable2($conDir)) {
            $this->fail('context_connectors_writable');
        } else {
            $this->pass('context_connectors_writable');
        }
    }
}