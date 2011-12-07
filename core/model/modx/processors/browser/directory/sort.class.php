<?php
/**
 * Moves a file/directory.
 *
 * @var modX $this->modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
class modBrowserFolderSortProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('directory_update');
    }
    public function getLanguageTopics() {
        return array('file');
    }
    public function process() {
        $from = $this->getProperty('from');
        $to = $this->getProperty('to');
        if (empty($from)) return $this->failure($this->modx->lexicon('file_folder_err_ns'));
        if (empty($to)) return $this->failure($this->modx->lexicon('file_folder_err_ns'));
        $source = $this->getProperty('source',1);

        /** @var modMediaSource $source */
        $this->modx->loadClass('sources.modMediaSource');
        $source = modMediaSource::getDefaultSource($this->modx,$source);
        if (!$source->getWorkingContext()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $source->setRequestProperties($this->getProperties());
        $source->initialize();
        $this->modx->setPlaceholder('mediasource.res_id',$this->getProperty('res_id'));
        $success = $source->moveObject($from,$to);
        if (!$success) {
            $errors = $source->getErrors();
            foreach ($errors as $k => $msg) {
                $this->addFieldError($k,$msg);
            }
            return $this->failure($this->modx->error->message);
        }
        return $this->success();
    }
}
return 'modBrowserFolderSortProcessor';