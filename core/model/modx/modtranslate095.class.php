<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Utility class for assisting in migrating content from older MODX releases.
 *
 * @package modx
 */
class modTranslate095 {
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx= null;
    /**
     * The parsing engine for interpreting Evolution-style tags
     * @var modParser095
     */
    public $parser= null;

    /**
     * Initializes the class and sets up a translation map
     * @param modX $modx A reference to the modX instance
     */
    function __construct(modX &$modx) {
        $this->modx = &$modx;
        $this->preTranslationSearch= array('[*','[~','[+','[!');
        $this->preTranslationReplace= array('#trans->[*','#trans->[~','#trans->[+','#trans->[!');
        $this->tagTranslation= array (
            '[[++' => array ('[(', ')]', '++'),
            '[[$' => array ('{{', '}}', '$'),
            '[[*' => array ('#trans->[*', '*]', '*'),
            '[[~' => array ('#trans->[~', '~]', '~'),
            '[[+' => array ('#trans->[+', '+]', '+'),
            '[[!' => array ('#trans->[!', '!]', '!'),
        );
    }

    /**
     * Gets the parsing engine
     *
     * @return modParser095
     */
    public function getParser() {
        if (!is_object($this->parser) || !($this->parser instanceof modParser095)) {
            $this->parser= & $this->modx->getService('parser095', 'modParser095');
        }
        return $this->parser;
    }

    /**
     * Translate the site into Revolution-style tags
     *
     * @param boolean $save Whether or not to actually save the content changed
     * @param null $classes An array of classes and fields to translate
     * @param array $files An array of files to attempt to translate
     * @param boolean|string $toFile If true, will write the file to the specified log
     * @return void
     */
    public function translateSite($save= false, $classes= null, $files= array (), $toFile= false) {
        $parser = $this->getParser();
        $parser->tagTranslation = $this->tagTranslation;
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
                        $content= $resource->get($field);
                        if ($content) {
                            echo "[BEGIN TRANSLATING FIELD] {$field}\n";
                            $content = str_replace($this->preTranslationSearch, $this->preTranslationReplace, $content);
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

    /**
     * Translate specific files
     *
     * @param boolean $save If true, will save the translation
     * @param array $files An array of files to translate
     * @return string
     */
    public function translateFiles($save= false, $files= array()) {
        $output= '';
        if (is_array($files) && !empty ($files)) {
            $parser= $this->getParser();
            $parser->tagTranslation=$this->tagTranslation;
            $cacheManager= $this->modx->getCacheManager();

            $output .= "Processing files: " . print_r($files, true) . "\n\n\n";
            ob_start();
            foreach ($files as $file) {
                if (file_exists($file)) {
                    echo "[BEGIN TRANSLATING FILE] {$file}\n";
                    $content= file_get_contents($file);
                    if ($content !== false) {
                        $content=str_replace($this->preTranslationSearch,$this->preTranslationReplace,$content);
                        while ($parser->translate($content, array(), true)) {}
                        if ($save) $cacheManager->writeFile($file, $content);
                    }
                    echo "[END TRANSLATING FILE] {$file}\n";
                }
            }
            $output .= ob_get_contents();
            ob_end_clean();
        }
        return $output;
    }
}
