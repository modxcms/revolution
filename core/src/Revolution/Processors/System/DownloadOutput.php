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
        if ($this->getProperty('download')) {
            $output = $this->download();
        } else {
            $output = $this->cache();
        }
        return $output;
    }

    /**
     * Download the output to the browser
     * @return string
     */
    public function download()
    {
        $dl = $this->getProperty('download');
        $dl = str_replace(['../', '..', 'config'], '', $dl);
        $dl = ltrim($dl, '/');

        $f = $this->modx->getOption('core_path') . $dl;
        $o = $this->modx->cacheManager->get($dl);
        if (!$o) {
            return '';
        }

        $this->modx->cacheManager->delete($dl);

        $bn = basename($f);
        @session_write_close();
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename="' . $bn . '-' . date('Y-m-d Hi') . '.txt"');
        return $o;
    }

    /**
     * Cache the data stored
     * @return array|string
     */
    public function cache()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->failure($this->modx->lexicon('invalid_data'));
        }

        $data = strip_tags($data, '<br><span><hr><li>');
        $data = str_replace(['<li>', '<hr>', '<br>', '<span>', '<?php', '<?', '?>'], "\r\n", $data);
        $data = strip_tags($data);
        $o = '/*
* MODX Console Output
*
* @date ' . date('Y-m-d H:i:s') . '
*/
' . $data . '
/* EOF */
';

        /* setup filenames and write to file */
        $file = 'export/console/output';
        $fileName = $this->modx->getOption('core_path') . $file;
        if (file_exists($fileName)) {
            $this->modx->cacheManager->delete($fileName);
        }
        $success = $this->modx->cacheManager->set($file, $o);
        return $success ? $this->success($file) : $this->failure($this->modx->lexicon('cache_err_write'));
    }
}
