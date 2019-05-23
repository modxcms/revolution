<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution;

/**
 * Utility abstract class for usage by Extras that has a subrequest handler that does auto-routing by the &action
 * REQUEST parameter. You must extend this class in your Extra to use it.
 *
 * @package MODX\Revolution
 */
abstract class modExtraManagerController extends modManagerController
{
    /**
     * Define the default controller action for this namespace
     *
     * @static
     * @return string A default controller action
     */
    public static function getDefaultController()
    {
        return 'index';
    }

    /**
     * Get an instance of this extra controller
     *
     * @static
     *
     * @param modX   $modx      A reference to the modX object
     * @param string $className The string className that is being requested to load
     * @param array  $config    An array of configuration options built from the modAction object
     *
     * @return modManagerController A newly created modManagerController instance
     */
    public static function getInstanceDeprecated(modX &$modx, $className, array $config = [])
    {
        $action = call_user_func([$className, 'getDefaultController']);
        if (isset($_REQUEST['action'])) {
            $action = str_replace(['../', './', '.', '-', '@'], '', $_REQUEST['action']);
        }
        $className = self::getControllerClassName($action, $config['namespace']);
        $classPath = $config['namespace_path'] . 'controllers/' . $action . '.class.php';
        require_once $classPath;
        /** @var modManagerController $controller */
        $controller = new $className($modx, $config);

        return $controller;
    }

    /**
     * Return the class name of a controller given the action
     *
     * @static
     *
     * @param string $action    The action name, eg: "home" or "create"
     * @param string $namespace The namespace of the Exra
     * @param string $postFix   The string to postfix to the class name
     *
     * @return string A full class name of the controller class
     */
    public static function getControllerClassName($action, $namespace = '', $postFix = 'ManagerController')
    {
        $className = explode('/', $action);
        $o = [];
        foreach ($className as $k) {
            $o[] = ucfirst(str_replace(['.', '_', '-'], '', $k));
        }

        return ucfirst($namespace) . implode('', $o) . $postFix;
    }

    /**
     * Do any page-specific logic and/or processing here
     *
     * @param array $scriptProperties
     *
     * @return void
     */
    public function process(array $scriptProperties = [])
    {
    }

    /**
     * The page title for this controller
     *
     * @return string The string title of the page
     */
    public function getPageTitle()
    {
        return '';
    }

    /**
     * Loads any page-specific CSS/JS for the controller
     *
     * @return void
     */
    public function loadCustomCssJs()
    {
    }

    /**
     * Specify the location of the template file
     *
     * @return string The absolute path to the template file
     */
    public function getTemplateFile()
    {
        return '';
    }

    /**
     * Check whether the active user has access to view this page
     *
     * @return bool True if the user passes permission checks
     */
    public function checkPermissions()
    {
        return true;
    }
}
