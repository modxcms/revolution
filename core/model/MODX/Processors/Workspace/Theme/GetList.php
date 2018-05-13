<?php

namespace MODX\Processors\Workspace\Theme;

use MODX\Processors\modObjectProcessor;

/**
 * Grabs a list of manager themes
 *
 * @package modx
 * @subpackage processors.workspace.theme
 */
class GetList extends modObjectProcessor
{
    public $permission = 'settings';


    public function process()
    {
        $themePath = $this->modx->config['manager_path'] . 'templates/';
        $themes = [];

        $dir = new \DirectoryIterator($themePath);
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $themes[] = ['theme' => $fileinfo->getFilename()];
            }
        }

        return $this->outputArray($themes, count($themes));
    }
}
