<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Sources;

/**
 * The interface used for defining common methods for Media Source drivers
 *
 * @package MODX\Revolution\Sources
 */
interface modMediaSourceInterface
{
    /**
     * Initialize the source, preparing it for usage.
     *
     * @return boolean
     */
    public function initialize();

    /**
     * Return an array of containers at this current level in the container structure. Used for the tree
     * navigation on the files tree.
     *
     * @abstract
     *
     * @param string $path
     *
     * @return array
     */
    public function getContainerList($path);

    /**
     * Return a detailed list of objects in a specific path. Used for thumbnails in the Browser.
     *
     * @param string $path
     *
     * @return array
     */
    public function getObjectsInContainer($path);

    /**
     * Create a container at the passed location with the passed name
     *
     * @abstract
     *
     * @param string $name
     * @param string $parentContainer
     *
     * @return boolean
     */
    public function createContainer($name, $parentContainer);

    /**
     * Remove the specified container
     *
     * @abstract
     *
     * @param string $path
     *
     * @return boolean
     */
    public function removeContainer($path);

    /**
     * Rename a container
     *
     * @abstract
     *
     * @param string $oldPath
     * @param string $newName
     *
     * @return boolean
     */
    public function renameContainer($oldPath, $newName);

    /**
     * Upload objects to a specific container
     *
     * @abstract
     *
     * @param string $container
     * @param array  $objects
     *
     * @return boolean
     */
    public function uploadObjectsToContainer($container, array $objects = []);

    /**
     * Get the contents of an object
     *
     * @abstract
     *
     * @param string $objectPath
     *
     * @return boolean
     */
    public function getObjectContents($objectPath);

    /**
     * Update the contents of a specific object
     *
     * @abstract
     *
     * @param string $objectPath
     * @param string $content
     *
     * @return boolean
     */
    public function updateObject($objectPath, $content);

    /**
     * Create an object from a path
     *
     * @param string $objectPath
     * @param string $name
     * @param string $content
     *
     * @return boolean|string
     */
    public function createObject($objectPath, $name, $content);

    /**
     * Remove an object
     *
     * @abstract
     *
     * @param string $objectPath
     *
     * @return boolean
     */
    public function removeObject($objectPath);

    /**
     * Rename a file/object
     *
     * @abstract
     *
     * @param string $oldPath
     * @param string $newName
     *
     * @return bool
     */
    public function renameObject($oldPath, $newName);

    /**
     * Get the openTo path for this source, used with TV input types and Static Elements/Resources
     *
     * @param string $value
     * @param array  $parameters
     *
     * @return string
     */
    public function getOpenTo($value, array $parameters = []);

    /**
     * Get the base path for this source. Only applicable to sources that are streams, used for determining
     * the base path with Static objects.
     *
     * @param string $object An optional file to find the base path with
     *
     * @return string
     */
    public function getBasePath($object = '');

    /**
     * Get the base URL for this source. Only applicable to sources that are streams; used for determining the base
     * URL with Static objects and downloading objects.
     *
     * @abstract
     *
     * @param string $object
     *
     * @return void
     */
    public function getBaseUrl($object = '');

    /**
     * Get the URL for an object in this source. Only applicable to sources that are streams; used for determining
     * the base URL with Static objects and downloading objects.
     *
     * @abstract
     *
     * @param string $object
     *
     * @return void
     */
    public function getObjectUrl($object = '');

    /**
     * Move a file or folder to a specific location
     *
     * @param string $from  The location to move from
     * @param string $to    The location to move to
     * @param string $point The type of move; append, above, below
     *
     * @return boolean
     */
    public function moveObject($from, $to, $point = 'append');

    /**
     * Get the name of this source type, ie, "File System"
     *
     * @return string
     */
    public function getTypeName();

    /**
     * Get a short description of this source type
     *
     * @return string
     */
    public function getTypeDescription();

    /**
     * Get the default properties for this source. Override this in your custom source driver to provide custom
     * properties for your source type.
     *
     * @return array
     */
    public function getDefaultProperties();
}
