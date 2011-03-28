<?php
/*
 * MODX Revolution
 * 
 * Copyright 2006-2011 by MODX, LLC.
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
 * Provides common functions for importing content into a modX repository.
 *
 * @package modx
 * @subpackage import
 */
class modImport {
    var $modx = null;
    var $results = array();
    var $properties = array ();

    function modImport(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        $this->modx = & $modx;
    }

    function getFiles(& $filesfound, $directory, $listing= array (), $count= 0) {
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

    function getFileContent($file) {
        // get the file
        if (@ $handle= fopen($file, "r")) {
            $buffer= "";
            while (!feof($handle)) {
                $buffer .= fgets($handle, 4096);
            }
            fclose($handle);
        } else {
            $this->log($this->modx->lexicon('import_site_failed') . " Could not retrieve content from file '$file'.");
        }
        return $buffer;
    }

    function getFileContentType($extension) {
        if (!$contentType= $this->modx->getObject('modContentType', "file_extensions LIKE '%{$extension}%'")) {
            $this->log("Could not find content type for extension '$extension'; using <tt>text/plain</tt>.");
            $contentType= $this->modx->getObject('modContentType', array('mime_type' => 'text/plain'));
        }
        return $contentType;
    }

    function log($message) {
        $this->results[] = $message;
//        $this->modx->log(MODX_LOG_LEVEL_ERROR, $message);
    }
}
?>