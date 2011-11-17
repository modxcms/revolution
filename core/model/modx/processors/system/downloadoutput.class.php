<?php
/**
 * Output data to a file for downloading
 *
 * @package modx
 * @subpackage processors.system
 */
class modSystemDownloadOutputProcessor extends modProcessor {
    public function process() {
        if ($this->getProperty('download')) {
            $output = $this->download();
        } else {
            $output = $this->cache();
        }
        return $output;
    }

    /**
     * Download the output to the browser
     * 
     * @return string
     */
    public function download() {
        $dl = $this->getProperty('download');
        $dl = str_replace(array('../','..','config'),'',$dl);
        $dl = ltrim($dl,'/');

        $f = $this->modx->getOption('core_path').$dl;
        $o = $this->modx->cacheManager->get($dl);
        if (!$o) return '';

        $this->modx->cacheManager->delete($dl);

        $bn = basename($f);
        @session_write_close();
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=\"{$bn}-".date('Y-m-d Hi').".txt\"");
        return $o;
    }

    /**
     * Cache the data stored
     * 
     * @return array|string
     */
    public function cache() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->failure($this->modx->lexicon('invalid_data'));

        $data = strip_tags($data,'<br><span><hr><li>');
        $data = str_replace(array('<li>','<hr>','<br>','<span>','<?php','<?','?>'),"\r\n",$data);
        $data = strip_tags($data);
        $o = "/*
* MODX Console Output
*
* @date ".date('Y-m-d H:i:s')."
*/
".$data."
/* EOF */
";

        /* setup filenames and write to file */
        $file = 'export/console/output';
        $fileName = $this->modx->getOption('core_path').$file;
        if (file_exists($fileName)) $this->modx->cacheManager->delete($fileName);
        $success = $this->modx->cacheManager->set($file,$o);
        return $success ? $this->success($file) : $this->failure($this->modx->lexicon('cache_err_write'));
    }
}
return 'modSystemDownloadOutputProcessor';