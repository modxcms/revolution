<?php
/**
 * Display phpinfo()
 *
 * @package modx
 * @subpackage processors.system
 */
class modSystemPhpInfoProcessor extends modProcessor {
    public function process() {
        echo '<div style="font-size: 1.3em;">';
        phpinfo();
        echo '</div>';
        @session_write_close();
        die();
    }
}
return 'modSystemPhpInfoProcessor';