<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\File;


/**
 * File implementation of modFileSystemResource
 *
 * @package MODX\Revolution\File
 */
class modFile extends modFileSystemResource
{
    /**
     * @var string The content of the resource
     */
    protected $content = '';

    /**
     * @see modFileSystemResource.parseMode
     *
     * @param string $mode
     *
     * @return string
     */
    protected function parseMode($mode = '')
    {
        if (empty($mode)) {
            $mode = $this->fileHandler->context->getOption('new_file_permissions', '0644', $this->fileHandler->config);
        }

        return parent::parseMode($mode);
    }

    /**
     * Actually create the file on the file system
     *
     * @param string $content The content of the file to write
     * @param string $mode    The perms to write with the file
     *
     * @return boolean True if successful
     */
    public function create($content = '', $mode = 'w+')
    {
        if ($this->exists()) {
            return false;
        }
        $result = false;

        $fp = @fopen($this->path, 'w+');
        if ($fp) {
            @fwrite($fp, $content);
            @fclose($fp);

            $result = file_exists($this->path);
            if ($result) {
                $mode = $this->parseMode();
                if (empty($mode)) {
                    $mode = octdec($this->fileHandler->modx->getOption('new_file_permissions', null, '0644'));
                }
                @chmod($this->path, $mode);
            }
        }

        return $result;
    }

    /**
     * Temporarily set (but not save) the content of the file
     *
     * @param string $content The content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get the contents of the file
     *
     * @return string The contents of the file
     */
    public function getContents()
    {
        $content = @file_get_contents($this->path);

        if ($content === false) {
            $content = $this->content;
        }

        return $content;
    }

    /**
     * Alias for save()
     *
     * @see modDirectory::write
     *
     * @param string $content
     * @param string $mode
     *
     * @return boolean
     */
    public function write($content = null, $mode = 'w+')
    {
        return $this->save($content, $mode);
    }

    /**
     * Writes the content of the modFile object to the actual file.
     *
     * @param string $content Optional. If not using setContent, this will set
     *                        the content to write.
     * @param string $mode    The mode in which to write
     *
     * @return boolean The result of the fwrite
     */
    public function save($content = null, $mode = 'w+')
    {
        if ($content !== null) {
            $this->content = $content;
        }
        $result = false;

        $fp = @fopen($this->path, $mode);
        if ($fp) {
            $result = @fwrite($fp, $this->content);
            @fclose($fp);
        }

        return $result;
    }

    /**
     * Unpack a zip archive to a specified location.
     *
     * @uses compression.xPDOZip OR compression.PclZip
     *
     * @param string $this    ->getPath() An absolute file system location to a valid zip archive.
     * @param string $to      A file system location to extract the contents of the archive to.
     * @param array  $options an array of optional options, primarily for the xPDOZip class
     *
     * @return array|string|boolean An array of unpacked files, a string in case of cli functions or false on failure.
     */
    public function unpack($to = '', $options = [])
    {

        $results = false;

        if ($this->fileHandler->modx->getService('archive', 'compression.xPDOZip', XPDO_CORE_PATH, $this->path)) {
            $results = $this->fileHandler->modx->archive->unpack($to);
        }

        return $results;
    }

    /**
     * Gets the size of the file
     *
     * @return int The size of the file, in bytes
     */
    public function getSize()
    {
        $size = @filesize($this->path);

        if ($size === false) {
            if (function_exists('mb_strlen')) {
                $size = mb_strlen($this->content, '8bit');
            } else {
                $size = strlen($this->content);
            }
        }

        return $size;
    }

    /**
     * Gets the last accessed time of the file
     *
     * @param string $timeFormat The format, in strftime format, of the time
     *
     * @return string The formatted time
     */
    public function getLastAccessed($timeFormat = '%b %d, %Y %I:%M:%S %p')
    {
        return strftime($timeFormat, fileatime($this->path));
    }

    /**
     * Gets the last modified time of the file
     *
     * @param string $timeFormat The format, in strftime format, of the time
     *
     * @return string The formatted time
     */
    public function getLastModified($timeFormat = '%b %d, %Y %I:%M:%S %p')
    {
        return strftime($timeFormat, filemtime($this->path));
    }

    /**
     * Gets the file extension of the file
     *
     * @return string The file extension of the file
     */
    public function getExtension()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    /**
     * Gets the basename, or only the filename without the path, of the file
     *
     * @return string The basename of the file
     */
    public function getBaseName()
    {
        return ltrim(strrchr($this->path, '/'), '/');
    }

    /**
     * Sends the file as a download
     *
     * @param array $options Optional configuration options like mimetype and filename
     */
    public function download($options = [])
    {
        $options = array_merge([
            'mimetype' => 'application/octet-stream',
            'filename' => '"' . $this->getBasename() . '"',
        ], $options);

        $output = $this->getContents();

        header('Content-type: ' . $options['mimetype']);
        header('Content-Disposition: attachment; filename=' . $options['filename']);
        header('Content-Length: ' . $this->getSize());

        echo $output;
        die();
    }

    /**
     * Deletes the file from the filesystem
     *
     * @return boolean True if successful
     */
    public function remove()
    {
        if (!$this->exists()) {
            return false;
        }

        return @unlink($this->path);
    }
}
