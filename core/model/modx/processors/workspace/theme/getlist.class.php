<?php
/**
 * Grabs a list of manager themes
 *
 * @package modx
 * @subpackage processors.workspace.theme
 */

class managerThemeGetListProcessor extends modObjectProcessor {
    public $permission = 'settings';
    public function process() {
        $themePath = $this->modx->config['manager_path'] . 'templates/';
        $themes = array();

        $dir = new DirectoryIterator($themePath);
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $themes[] = array('theme' => $fileinfo->getFilename());
            }
        }

        return $this->outputArray($themes,count($themes));
    }
}
return 'managerThemeGetListProcessor';
