<?php

namespace MODX;

use xPDO\xPDO;

/**
 * Interface for implementation on derivative Resource types. Please define the following methods in your derivative
 * class to properly implement a Custom Resource Type in MODX.
 *
 * @see modResource
 * @interface
 * @package modx
 */
interface modResourceInterface
{
    /**
     * Determine the controller path for this Resource class. Return an absolute path.
     *
     * @static
     *
     * @param xPDO $modx A reference to the MODX object
     *
     * @return string The absolute path to the controller for this Resource class
     */
    public static function getControllerPath(xPDO &$modx);


    /**
     * Use this in your extended Resource class to display the text for the context menu item, if showInContextMenu is
     * set to true. Return in the following format:
     *
     * array(
     *  'text_create' => 'ResourceTypeName',
     *  'text_create_here' => 'Create ResourceTypeName Here',
     * );
     *
     * @return array
     */
    public function getContextMenuText();


    /**
     * Use this in your extended Resource class to return a translatable name for the Resource Type.
     *
     * @return string
     */
    public function getResourceTypeName();


    /**
     * Allows you to manipulate the tree node for a Resource before it is sent
     *
     * @abstract
     *
     * @param array $node
     */
    public function prepareTreeNode(array $node = []);
}