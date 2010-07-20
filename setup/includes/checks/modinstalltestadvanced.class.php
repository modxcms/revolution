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

        $this->results['zip_memory_limit']['msg'] = '<p>'.$this->install->lexicon['test_zip_memory_limit'].' ';
        if ($success) {
            $this->results['zip_memory_limit']['msg'] .= '<span class="ok">'.sprintf($this->install->lexicon['test_memory_limit_success'],$ml).'</span></p>';
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
    protected function _checkAdvPaths() {
        /* web_path */
        $this->results['context_web_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],$this->install->settings->get('context_web_path'));
        $webDir = dirname($this->install->settings->get('context_web_path'));
        if (!$this->is_writable2($webDir)) {
            $this->results['context_web_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['context_web_writable']['class'] = 'testFailed';
        } else {
            $this->results['context_web_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['context_web_writable']['class'] = 'testPassed';
        }

        /* mgr_path */
        $this->results['context_mgr_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],$this->install->settings->get('context_mgr_path'));
        $mgrDir = dirname($this->install->settings->get('context_mgr_path'));
        if (!$this->is_writable2($mgrDir)) {
            $this->results['context_mgr_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['context_mgr_writable']['class'] = 'testFailed';
        } else {
            $this->results['context_mgr_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['context_mgr_writable']['class'] = 'testPassed';
        }

        /* connectors_path */
        $this->results['context_connectors_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],$this->install->settings->get('context_connectors_path'));
        $conDir = dirname($this->install->settings->get('context_connectors_path'));
        if (!$this->is_writable2($conDir)) {
            $this->results['context_connectors_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['context_connectors_writable']['class'] = 'testFailed';
        } else {
            $this->results['context_connectors_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['context_connectors_writable']['class'] = 'testPassed';
        }
    }
}