<?php
/**
 * Export properties and output url to download to browser
 *
 * @package modx
 * @subpackage processors.element
 */
class modElementExportPropertiesProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('view_propertyset');
    }
    public function getLanguageTopics() {
        return array('propertyset','element');
    }

    public function process() {
        $download = $this->getProperty('download');
        if (!empty($download)) {
            $o = $this->download($download);
        } else {
            $o = $this->export();
        }
        return $o;
    }

    /**
     * Download the file
     * 
     * @param string $file
     * @return bool|string
     */
    public function download($file) {
        $fileName = $this->modx->getOption('core_path').'export/properties/'.$file;
        if (!is_file($fileName)) return '';
        $output = file_get_contents($fileName);
        if (empty($output)) return '';

        $id = $this->getProperty('id');
        if (empty($id)) {
            $name = 'default';
        } else {
            /** @var modPropertySet $propertySet */
            $propertySet = $this->modx->getObject('modPropertySet',$id);
            if (empty($propertySet)) {
                $name = 'unknown';
            } else {
                $name = $propertySet->get('name');
                $name = strtolower(str_replace(' ','-',$name));
            }
        }
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename="'.$name.'.properties.js"');
        return $output;
    }

    /**
     * Export the properties into a temporary export file
     * 
     * @return mixed
     */
    public function export() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->failure($this->modx->lexicon('propertyset_err_ns'));

        $f = 'export.js';
        $fileName = $this->modx->getOption('core_path').'export/properties/'.$f;

        /** @var modCacheManager $cacheManager */
        $cacheManager = $this->modx->getCacheManager();
        $cacheManager->writeFile($fileName,$data);

        return $this->success($f);
    }
}
return 'modElementExportPropertiesProcessor';