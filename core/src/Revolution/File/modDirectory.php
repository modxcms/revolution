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


use DirectoryIterator;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Representation of a directory
 *
 * @package MODX\Revolution\File
 */
class modDirectory extends modFileSystemResource
{
    /**
     * Actually creates the new directory on the file system.
     *
     * @param string $mode Optional. The permissions of the new directory.
     *
     * @return boolean True if successful
     */
    public function create($mode = '')
    {
        $mode = $this->parseMode($mode);
        if (empty($mode)) {
            $mode = octdec($this->fileHandler->modx->getOption('new_folder_permissions', null, '0775'));
        }
        if ($this->exists()) {
            return false;
        }

        return $this->fileHandler->modx->cacheManager->writeTree($this->path, [
            'new_folder_permissions' => $mode,
        ]);
    }

    /**
     * @see modFileSystemResource::parseMode
     *
     * @param string $mode
     *
     * @return boolean
     */
    protected function parseMode($mode = '')
    {
        if (empty($mode)) {
            $mode = $this->fileHandler->context->getOption('new_folder_permissions', '0755',
                $this->fileHandler->config);
        }

        return parent::parseMode($mode);
    }

    /**
     * Removes the directory from the file system, recursively removing
     * subdirectories and files.
     *
     * @param array $options Options for removal.
     *
     * @return boolean True if successful
     */
    public function remove($options = [])
    {
        if ($this->path == '/') {
            return false;
        }

        $options = array_merge([
            'deleteTop' => true,
            'skipDirs' => false,
            'extensions' => [],
        ], $options);

        $this->fileHandler->modx->getCacheManager();

        return $this->fileHandler->modx->cacheManager->deleteTree($this->path, $options);
    }

    /**
     * Iterates over a modDirectory object and returns an array of all containing files and optionally directories,
     * can run recursive, filter by file extension(s) or filenames and sort the resulting list with the specified sort options
     * an anonymous callback function can be passed to modify the output on the fly, by default an array of paths is returned
     *
     * @param array $options Options for iterating the directory.
     *
     * @option boolean recursive If subdirectories should be scanned as well
     * @option boolean sort If the resulting array should be sorted
     * @option string sortdir What sort order should be applied: SORT_ASC|SORT_DESC
     * @optoin string sortflag What sort flag should be applied: SORT_REGULAR, SORT_NATURAL, SORT_NUMERIC etc
     * @option boolean skiphidden If hidden directories and files should be ignored, defaults to true
     * @option boolean skipdirs If directories should be skipped in the resulting array, defaults to true
     * @option string|array skip Comma separated list or array of filenames (including extension) that should be ignored
     * @option string|array extensions Comma separated list or array of file extensions to filter files by
     * @option boolean|function callback Anonymous function to modify each output item, $item will be passed as argument
     *
     * @return array
     */
    public function getList($options = [])
    {
        $options = array_merge([
            'recursive' => false,
            'sort' => false,
            'sortdir' => SORT_ASC,
            'sortflag' => SORT_REGULAR,
            'skiphidden' => true,
            'skipdirs' => true,
            'skip' => [],
            'extensions' => [],
            'callback' => false,
        ], $options);

        $items = [];

        $mb = $this->fileHandler->modx->getOption('use_multibyte', null, false);
        $mbencoding = $this->fileHandler->modx->getOption('modx_charset', null, 'UTF-8');
        $extensions = !is_array($options['extensions']) ? explode(',', $options['extensions']) : $options['extensions'];
        $skip = !is_array($options['skip']) ? explode(',', $options['skip']) : $options['skip'];
        $iterator = $options['recursive'] ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path,
            FilesystemIterator::CURRENT_AS_SELF)) : new DirectoryIterator($this->path);

        foreach ($iterator as $item) {
            $skipfile = !empty($skip) ? in_array($item->getFilename(), $skip) : false;
            $ishidden = false;

            if ($options['skiphidden']) {
                // check for hidden folder, also hide with visible ones inside
                // but don't skip weird filenames like "...and-there-was-silence.avi"
                if ($item->isDot() || preg_match('/(\/\.\w+|\\\.\w+)/', $item->getPath())) {
                    continue;
                }
                // check for hidden file (probably works only on UNIX filesystems)
                $ishidden = preg_match('/^(\.\w+)/i', $item->getFilename());
            } else {
                if (!$options['skipdirs']) {
                    // always exclude . and .. directory navigators, only relevant when including folders
                    $ishidden = $item->isDot();
                }
            }

            if (($item->isFile() || $item->isDir() && !$options['skipdirs']) && !$ishidden && !$skipfile) {
                $additem = true;

                if (!empty($options['extensions'])) {
                    // if min PHP version is 5.3.6 we can use $item->getExtension()
                    $extension = pathinfo($item->getPathname(), PATHINFO_EXTENSION);
                    $extension = $mb ? mb_strtolower($extension, $mbencoding) : strtolower($extension);

                    if (!in_array($extension, $extensions)) {
                        $additem = false;
                    }
                }

                if (!$additem) {
                    continue;
                } else {
                    if (is_callable($options['callback'])) {
                        $callback = call_user_func($options['callback'], $item);

                        if (!empty($callback)) {
                            $items[] = $callback;
                        }
                    } else {
                        $items[] = $item->isDir() ? $item->getPathname() . DIRECTORY_SEPARATOR : $item->getPathname();
                    }
                }
            }
        }

        if (!empty($options['sort'])) {
            array_multisort($items, $options['sortdir'], $options['sortflag'], $items);
        }

        return $items;
    }
}
