<?php

/**
 * Unpacks archives, currently only zip
 *
 * @package modx
 * @subpackage processors.system.filesys.file
 */
require_once dirname(__DIR__) . '/browser.class.php';

class modUnpackProcessor extends modBrowserProcessor
{
    public $permission = 'file_unpack';
    public $policy = 'view';
    public $languageTopics = ['file'];


    /**
     * @return array|bool|mixed|string
     */
    public function process()
    {
        $file = $this->sanitize($this->getProperty('file'));
        try {
            if ($data = $this->source->getMetadata($file)) {
                $base = $this->source->getBasePath();
                $target = explode(DIRECTORY_SEPARATOR, trim($data['path'], DIRECTORY_SEPARATOR));
                array_pop($target);
                $target = implode(DIRECTORY_SEPARATOR, $target);

                /** @noinspection PhpParamsInspection */
                if ($archive = new \xPDO\Compression\xPDOZip($this->modx, $base . $data['path'])) {
                    if (!$archive->unpack($this->source->postfixSlash($base . $target))) {
                        return $this->failure($this->modx->lexicon('file_err_unzip'));
                    }
                }
            } else {
                return $this->failure($this->modx->lexicon('file_err_open') . $this->getProperty('file'));
            }
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }

        return $this->success();
    }
}

return 'modUnpackProcessor';
