<?php
/*
 * MODX Revolution
 * 
 * Copyright 2006-2012 by MODX, LLC.
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
     * @param array $filesfound A reference to an array of file locations
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
     * @param $extension The extension of the file
     * @return string The content-type of the file
     */
    public function getFileContentType($extension) {
        if (!$contentType= $this->modx->getObject('modContentType', "file_extensions LIKE '%{$extension}%'")) {
            $this->log("Could not find content type for extension '$extension'; using <tt>text/plain</tt>.");
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
//        $this->modx->log(MODX_LOG_LEVEL_ERROR, $message);
    }
}