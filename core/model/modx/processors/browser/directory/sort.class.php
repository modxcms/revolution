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
        $from = preg_replace('/[\.]{2,}/', '', htmlspecialchars($from));
        $to = $this->getProperty('to');
        $to = preg_replace('/[\.]{2,}/', '', htmlspecialchars($to));
        $point = $this->getProperty('point','append');
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
        if (!$source->checkPolicy('save')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $success = $source->moveObject($from,$to,$point);
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
