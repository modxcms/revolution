<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System;

use MODX\Revolution\File\modFileHandler;
use MODX\Revolution\Processors\Processor;

/**
 * Output data to a file for downloading
 * @package MODX\Revolution\Processors\System
 */
class DownloadOutput extends Processor
{
    /**
     * @return array|string
     */
    public function process()
    {
        $cookieName = $this->getProperty('cookieName');
        if ($cookieName) {
            setcookie($cookieName, 'true', time() + 10, '/');
        }

        return $this->download();
    }

    /**
     * Create a temporary file object and serve as a download to the browser
     *
     * @return bool|string
     */
    public function download()
    {
        $data = $this->getProperty('data');

        if (empty($data)) {
            return $this->failure($this->modx->lexicon('invalid_data'));
        }

        $data = strip_tags($data, '<br><span><hr><li>');
        $data = str_replace(['<li>', '<hr>', '<br>', '<span>', '<?php', '<?', '?>'], "\r\n", $data);
        $data = strip_tags($data);
        $output = "/*\r\n* MODX Console Output\r\n*\r\n* @date " . date('Y-m-d H:i:s') . "\r\n*/\r\n" . $data . "\r\n/* EOF */\r\n";

        /** @var modFileHandler $fileHandler */
        $fileHandler = $this->modx->getService('fileHandler', modFileHandler::class);

        $fileName = 'output-' . date('Y-m-d_H-i') . '.txt';

        $fileobj = $fileHandler->make($this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'export/console/' . $fileName);

        $fileobj->setContent($output);
        $fileobj->download([
            'mimetype' => 'text/plain'
        ]);

        return true;
    }
}
