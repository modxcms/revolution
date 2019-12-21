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


use Exception;
use MODX\Revolution\Controllers\Error;
use MODX\Revolution\Controllers\Exceptions\AccessDeniedException;
use MODX\Revolution\Controllers\Exceptions\NotFoundException;

/**
 * Encapsulates an HTTP response from the MODX manager.
 *
 * {@inheritdoc}
 *
 * @package MODX\Revolution
 */
class modManagerResponse extends modResponse
{
    protected $route = 'index';
    protected $namespace = 'core';
    protected $namespaces = [];

    private $_requiresPermission;

    protected function _loadNamespaces()
    {
        $loaded = false;
        $namespaces = $this->modx->call(modNamespace::class, 'loadCache', [&$this->modx]);
        if ($namespaces) {
            $this->namespaces = $namespaces;
            $loaded = true;
        }

        return $loaded;
    }

    /**
     * @param array $options
     *
     * @return mixed|string
     */
    public function outputContent(array $options = [])
    {
        // Grab the namespace
        $this->_loadNamespaces();
        $this->namespace = (string)$this->modx->request->namespace;
        if (!array_key_exists($this->namespace, $this->namespaces)) {
            $this->namespace = 'core';
        }

        // Grab the route (action)
        $this->route = (string)$this->modx->request->action;
        if (empty($this->route)) {
            $this->route = $this->namespace === 'core' ? 'welcome' : 'index';
        }

        $this->modx->lexicon->load('dashboard', 'topmenu', 'file', 'action');

        // Only authenticated users are allowed to use a **Manager** Controller
        if (!$this->modx->user->isAuthenticated('mgr')) {
            $this->namespace = 'core';
            $this->route = 'security/login';

            // Support single sign on solutions
            $alternateLogin = $this->modx->getOption('manager_login_url_alternate', null, '');
            if (!empty($alternateLogin)) {
                $this->modx->sendRedirect($alternateLogin);
                return '';
            }
        }


        try {
            if ($this->route !== 'security/login') {
                if (!$this->modx->hasPermission('frames')) {
                    $this->modx->user->endSession();
                    throw new AccessDeniedException('You don\'t have permission to access the manager.');
                }

                $this->checkForMenuPermissions($this->route);
            }

            $className = $this->getControllerClassName($this->route);

            /** @var modManagerController $controller */
            $controller = $className::getInstance($this->modx, $className, [
                'namespace' => $this->namespace,
                'action' => $this->route,
            ]);
            $controller->setProperties(array_merge($_GET,$_POST));
            $controller->initialize();

            if (!$controller->checkPermissions()) {
                throw new AccessDeniedException('Not allowed to access this controller.');
            }

            $this->modx->controller = $controller;

            $this->body = $this->modx->controller->render();
            return $this->send();
        }
        catch (NotFoundException $e) {
            $controller = new Error($this->modx, [
                'message' => $this->modx->lexicon('action_err_ns'),
                'errors' => [
                    $e->getMessage()
                ]
            ]);
            $this->body = $controller->render();
            return $this->send();
        }
        catch (AccessDeniedException $e) {
            $message = $this->modx->lexicon('access_denied');
            if ($this->_requiresPermission) {
                $message .= ' (' . $message . ')';
            }

            $controller = new Error($this->modx, [
                'message' => $message,
                'errors' => [
                    $e->getMessage()
                ]
            ]);

            $this->body = $controller->render();
            return $this->send();
        }
    }

    private static function isControllerClass(string $className): bool
    {
        return class_exists($className) && is_subclass_of($className, modManagerController::class, true);
    }

    /**
     * Send the response to the client
     */
    public function send()
    {
        if (is_array($this->body)) {
            $this->modx->smarty->assign('_e', $this->body);
            if (!file_exists($this->modx->smarty->template_dir . 'error.tpl')) {
                $templatePath = $this->modx->getOption('manager_path') . 'templates/default/';
                $this->modx->smarty->setTemplatePath($templatePath);
            }
            echo $this->modx->smarty->fetch('error.tpl');
        } else {
            echo $this->body;
        }
        @session_write_close();
        exit();
    }

    /**
     * If this action has a menu item, ensure user has access to menu
     *
     * @param string $action
     *
     * @return bool
     * @throws AccessDeniedException
     */
    public function checkForMenuPermissions($action): bool
    {
        /** @var modMenu $menu */
        $menu = $this->modx->getObject(modMenu::class, [
            'action' => $action,
            'namespace' => $this->namespace,
        ]);
        if ($menu) {
            $permissions = $menu->get('permissions');
            if (!empty($permissions)) {
                $permissions = explode(',', $permissions);
                foreach ($permissions as $permission) {
                    if (!$this->modx->hasPermission($permission)) {
                        throw new AccessDeniedException($permission . ' permission is required');
                    }
                }
            }
        }

        return true;
    }

    /**
     * Gets the controller class name from the active action
     *
     * @param string $action
     * @return string
     * @throws NotFoundException
     */
    public function getControllerClassName(string $action): string
    {
        // Check for an autoloadable controller (3.0+)
        $name = '\\MODX\\Revolution\\Controllers\\' . $action;
        if (static::isControllerClass($name)) {
            return $name;
        }

        $theme = $this->modx->getOption('manager_theme', null, 'default');
        $paths = $this->getNamespacePath($theme);

        $name = $this->namespace !== 'core' ? ucfirst($this->namespace) : '';
        $name .= $action . 'ManagerController';
        $name = explode('/', $name);
        $o = [];
        foreach ($name as $k) {
            $o[] = ucfirst(str_replace(['.', '_', '-'], '', $k));
        }
        $name = implode('', $o);

        if (static::isControllerClass($name)) {
            return $name;
        }

        // Look for the controller based on the action name provided
        $filename = strtolower($action) . '.class.php';
        $fullPath = null;
        foreach ($paths as $controllersPath) {
            if (file_exists($controllersPath . $filename)) {
                $fullPath = $controllersPath . $filename;
                break;
            }

            if (file_exists($controllersPath . strtolower($action) . '/index.class.php')) {
                $fullPath = $controllersPath . strtolower($action) . '/index.class.php';
                break;
            }
        }

        // If the file exists, require it, while discarding any content from it
        if (file_exists($fullPath) && is_readable($fullPath)) {
            ob_start();
            require_once $fullPath;
            ob_end_clean();
        }

        // Available now?
        if (static::isControllerClass($name)) {
            return $name;
        }

        throw new NotFoundException();
    }

    /**
     * Get the appropriate path to the controllers directory for the active Namespace.
     *
     * @param string $theme
     *
     * @return array An array of paths to the Namespace's controllers directory.
     */
    public function getNamespacePath($theme = 'default')
    {
        $namespace = array_key_exists($this->namespace, $this->namespaces) ? $this->namespaces[$this->namespace] : $this->namespaces['core'];
        /* find context path */
        if (isset($namespace['name']) && $namespace['name'] !== 'core') {
            $paths[] = $namespace['path'] . 'controllers/' . trim($theme, '/') . '/';
            if ($theme !== 'default') {
                $paths[] = $namespace['path'] . 'controllers/default/';
            }
            $paths[] = $namespace['path'] . 'controllers/';

            /* deprecated old usage */
            $paths[] = $namespace['path'] . trim($theme, '/');
            if ($theme !== 'default') {
                $paths[] = $namespace['path'] . 'default/';
            }
            $paths[] = $namespace['path'];

        } else {
            $paths[] = $namespace['path'] . 'controllers/' . trim($theme, '/') . '/';
            if ($theme !== 'default') {
                $paths[] = $namespace['path'] . 'controllers/default/';
            }
            $paths[] = $namespace['path'] . 'controllers/';
        }

        return $paths;

    }

    /**
     * Adds a lexicon topic to this page's language topics to load. Will load
     * the topic as well.
     *
     * @param string $topic The topic to load, in standard namespace:topic format
     *
     * @return boolean True if successful
     */
    public function addLangTopic($topic)
    {
        $this->modx->lexicon->load($topic);
        $topics = $this->getLangTopics();
        $topics[] = $topic;

        return $this->setLangTopics($topics);
    }

    /**
     * Adds a lexicon topic to this page's language topics to load
     *
     * @return array An array of topics
     */
    public function getLangTopics()
    {
        $topics = $this->modx->smarty->get_template_vars('_lang_topics');

        return explode(',', $topics);
    }

    /**
     * Sets the language topics for this page
     *
     * @param array $topics The array of topics to set
     *
     * @return boolean True if successful
     */
    public function setLangTopics(array $topics = [])
    {
        if (!is_array($topics) || empty($topics)) {
            return false;
        }

        $topics = array_unique($topics);
        $topics = implode(',', $topics);

        return $this->modx->smarty->assign('_lang_topics', $topics);
    }
}
