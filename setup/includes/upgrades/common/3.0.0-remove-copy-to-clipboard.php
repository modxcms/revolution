<?php
/**
 * Common upgrade script to clean up the copy to clipboard flash file.
 *
 * @var modX
 *
 * @package setup
 */

$managerPath = $modx->getOption('manager_path', null, MODX_MANAGER_PATH);
$fileToRemove = $managerPath.'assets/modext/_clipboard.swf';

if (file_exists($fileToRemove) === true) {
    if (unlink($fileToRemove) === true) {
        $this->runner->addResult(modInstallRunner::RESULT_SUCCESS,'<p class="ok">'.$this->install->lexicon('clipboard_flash_file_unlink_success').'</p>');
    } else {
        $this->runner->addResult(modInstallRunner::RESULT_FAILURE,'<p class="notok">'.$this->install->lexicon('clipboard_flash_file_unlink_failed').'</p>');
    }
} else {
        $this->runner->addResult(modInstallRunner::RESULT_SUCCESS,'<p class="ok">'.$this->install->lexicon('clipboard_flash_file_missing').'</p>');
}
