<?php
/*
 * MODx Revolution
 *
 * Copyright 2006, 2007, 2008, 2009 by the MODx Team.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * Utility class for assisting in migrating content from older MODx releases.
 *
 * @package modx
 */
class modTranslate095 {
    var $modx= null;
    var $parser= null;

    function modTranslate095(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        $this->modx= & $modx;
    }

    function getParser() {
        if (!is_object($this->parser) || !is_a($this->parser, 'modParser095')) {
            $this->parser= & $this->modx->getService('parser095', 'modParser095');
        }
        return $this->parser;
    }

    function translateSite($save= false, $classes= null, $files= array (), $toFile= false) {
        $parser= $this->getParser();
        if ($classes === null) {
            $classes= array (
                'modResource' => array ('content', 'pagetitle', 'longtitle', 'description', 'menutitle', 'introtext'),
                'modTemplate' => array ('content'),
                'modChunk' => array ('snippet'),
                'modSnippet' => array ('snippet'),
                'modPlugin' => array ('plugincode'),
                'modTemplateVar' => array ('default_text'),
                'modTemplateVarResource' => array ('value'),
                'modSystemSetting' => array ('value')
            );
        }
        ob_start();

        echo "Processing classes: " . print_r($classes, true) . "\n\n\n";

        foreach ($classes as $className => $fields) {
            $resources= $this->modx->getCollection($className);
            if ($resources) {
                foreach ($resources as $resource) {
                    foreach ($fields as $field) {
                        if ($content= $resource->get($field)) {
                            echo "[BEGIN TRANSLATING FIELD] {$field}\n";
                            while ($parser->translate($content, array(), true)) {
                                $resource->set($field, $content);
                            }
                            echo "[END TRANSLATING FIELD] {$field}\n\n";
                        }
                    }
                    if ($save) {
                        $resource->save();
                    }
                }
            }
        }

        if (!empty ($files)) {
            echo $this->translateFiles($save, $files);
        }

        $log= ob_get_contents();
        ob_end_clean();
        if ($toFile) {
            $cacheManager= $this->modx->getCacheManager();
            $cacheManager->writeFile($toFile, $log);
        } else {
            echo $log;
        }
    }

    function translateFiles($save= false, $files= array()) {
        $output= '';
        if (is_array($files) && !empty ($files)) {
            $parser= $this->getParser();
            $cacheManager= $this->modx->getCacheManager();

            $output.= "Processing files: " . print_r($files, true) . "\n\n\n";
            ob_start();
            foreach ($files as $file) {
                if (file_exists($file)) {
                    echo "[BEGIN TRANSLATING FILE] {$file}\n";
                    $content= file_get_contents($file);
                    if ($content !== false) {
                        while ($parser->translate($content, array(), true)) {}
                        if ($save) $cacheManager->writeFile($file, $content);
                    }
                    echo "[END TRANSLATING FILE] {$file}\n";
                }
            }
            $output.= ob_get_contents();
            ob_end_clean();
        }
        return $output;
    }
}