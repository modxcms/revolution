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
 * @package modx
 * @subpackage import
 */
/**
 * Provides common functions for importing content into a modX repository.
 *
 * @package modx
 * @subpackage import
 */
class modImport {
    /**
     * @var modX A reference to the modX instance
     */
    public $modx = null;
    /**
     * @var array A collection of results in an array
     */
    public $results = array();
    /**
     * @var array A collection of properties that are being used in this import
     */
    public $properties = array ();

    /**
     * @param modX $modx A reference to the modX instance
     */
    function __construct(& $modx) {
        $this->modx = & $modx;
    }

    /**
     * @param int $filesfound A reference to an array of file locations
     * @param string $directory The directory to import from
     * @param array $listing A listing of imported files
     * @param int $count The current count iteration
     * @return array
     */
    public function getFiles(& $filesfound, $directory, $listing= array (), $count= 0) {
        if ($directory[-1] !== '/') {
            $directory.= '/';
        }
        $dummy= $count;
        if (@ $handle= opendir($directory)) {
            while ($file= readdir($handle)) {
                if ($file == '.' || $file == '..' || strpos($file, '.') === 0)
                    continue;
                else
                    if ($h= @ opendir($directory . $file . '/')) {
                        @ closedir($h);
                        $count= -1;
                        $listing["$file"]= $this->getFiles($filesfound, $directory . $file, array (), $count +1);
                    } else {
                        $listing[$dummy]= $file;
                        $dummy= $dummy +1;
                        $filesfound++;
                    }
            }
        } else {
            $this->log($this->modx->lexicon('import_site_failed') . " Could not open '$directory'.");
        }
        @ closedir($handle);
        return ($listing);
    }

    /**
     * Gets the buffered content of a file
     * @param string $file An absolute path to the file
     * @return string
     */
    public function getFileContent($file) {
        // get the file
        $buffer= '';
        if (@ $handle= fopen($file, "r")) {
            while (!feof($handle)) {
                $buffer .= fgets($handle, 4096);
            }
            fclose($handle);
        } else {
            $this->log($this->modx->lexicon('import_site_failed') . " Could not retrieve content from file '$file'.");
        }
        return $buffer;
    }

    /**
     * Gets the content type of a file
     * @param string $extension The extension of the file
     * @return string The content-type of the file
     */
    public function getFileContentType($extension) {
        if (!$contentType= $this->modx->getObject('modContentType', array('file_extensions:LIKE' => '%'.$extension.'%'))) {
            $this->log("Could not find content type for extension '$extension'; using <samp>text/plain</samp>.");
            $contentType= $this->modx->getObject('modContentType', array('mime_type' => 'text/plain'));
        }
        return $contentType;
    }

    /**
     * Log a message to the results array
     * @param string $message A string message to log
     * @return void
     */
    public function log($message) {
        $this->results[] = $message;
    }
}
