<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once MODX_CORE_PATH . 'model/modx/modresponse.class.php';
/**
 * Encapsulates an HTTP response from the MODX manager.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modManagerResponse extends modResponse {
    /** @var array A cached array of the current modAction object */
    public $action = array();

    public $namespace = 'core';
    public $namespaces = array();

    protected function _loadNamespaces() {
        $loaded = false;
        $cache = $this->modx->call('modNamespace','loadCache',array(&$this->modx));
        if ($cache) {
            $this->namespaces = $cache;
            $loaded = true;
        }
        return $loaded;
    }

    /**
     * @param array $options
     * @return mixed|string
     */
    public function outputContent(array $options = array()) {
        $route = $this->modx->request->action;
        $this->namespace = $this->modx->request->namespace;
        if (empty($route)) {
            $route = $this->namespace == 'core' ? 'welcome' : 'index';
        }
        $this->modx->lexicon->load('dashboard','topmenu','file','action');
        $this->_loadNamespaces();

        if (!array_key_exists($this->namespace,$this->namespaces)) {
            $this->namespace = 'core';
            $this->action = array();
        } else {
            $namespace = $this->namespaces[$this->namespace];
            $this->action['namespace'] = $this->namespace;
            $this->action['namespace_name'] = $namespace['name'];
            $this->action['namespace_path'] = $namespace['path'];
            $this->action['namespace_assets_path'] = $namespace['assets_path'];
            $this->action['lang_topics'] = '';
            $this->action['controller'] = $route;
        }

        $isDeprecated = false;
        /* handle 2.2< controllers */
        if (intval($route) > 0) {
            $this->modx->request->loadActionMap();
            $this->action = !empty($this->modx->actionMap[$route]) ? $this->modx->actionMap[$route] : array();
            $this->namespace = !empty($this->action['namespace']) ? $this->action['namespace'] : 'core';
            $this->modx->deprecated('2.3.0', 'Support for modAction has been replaced with routing based on a namespace and action name. Please update the extra with the namespace ' . $this->namespace . ' to the routing based system.', 'modAction support');
            $isDeprecated = true;
        }

        $isLoggedIn = $this->validateAuthentication();
        if ($isLoggedIn && !$this->checkForMenuPermissions($route)) {
            $this->body = $this->modx->error->failure($this->modx->lexicon('access_denied'));
        } else {
            $this->modx->loadClass('modManagerController','',false,true);
            $className = $this->loadControllerClass(!$isDeprecated);
            $this->instantiateController($className,$isDeprecated ? 'getInstanceDeprecated' : 'getInstance');
            $this->body = $this->modx->controller->render();
        }
        if (empty($this->body)) {
            $this->body = $this->modx->error->failure($this->modx->lexicon('action_err_ns'));
        }
        return $this->send();
    }

    /**
     * Ensure the user has access to the manager
     * @return bool|string
     */
    public function validateAuthentication() {
        $isLoggedIn = $this->modx->user->isAuthenticated('mgr');
        if (!$isLoggedIn) {
            $alternateLogin = $this->modx->getOption('manager_login_url_alternate',null,'');
            if (!empty($alternateLogin)) {
                $this->modx->sendRedirect($alternateLogin);
                return '';
            }
            $this->namespace = 'core';
            $this->action['namespace'] = 'core';
            $this->action['namespace_name'] = 'core';
            $this->action['namespace_path'] = $this->modx->getOption('manager_path',null,MODX_MANAGER_PATH);
            $this->action['namespace_assets_path'] = $this->modx->getOption('assets_path',null,MODX_ASSETS_PATH);
            $this->action['lang_topics'] = 'login';
            $this->action['controller'] = 'security/login';
        } else if (!$this->modx->hasPermission('frames')) {
            $this->namespace = 'core';
            $this->action['namespace'] = 'core';
            $this->action['namespace_name'] = 'core';
            $this->action['namespace_path'] = $this->modx->getOption('manager_path',null,MODX_MANAGER_PATH);
            $this->action['namespace_assets_path'] = $this->modx->getOption('assets_path',null,MODX_ASSETS_PATH);
            $this->action['lang_topics'] = 'login';
            $this->action['controller'] = 'security/logout';
        }
        return $isLoggedIn;
    }

    /**
     * Send the response to the client
     */
    public function send() {
        if (is_array($this->body)) {
            $this->modx->smarty->assign('_e', $this->body);
            if (!file_exists($this->modx->smarty->template_dir.'error.tpl')) {
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
     * Include the correct controller class for the action
     *
     * @param bool $prefixNamespace Whether or not to prefix the Namespace name to the class. Default for 2.3+
     * controllers, set to false for 2.2< deprecated controllers.
     * @return string
     */
    public function loadControllerClass($prefixNamespace = true) {
        $theme = $this->modx->getOption('manager_theme',null,'default');
        $paths = $this->getNamespacePath($theme);
        $f = $this->action['controller'];
        $className = $this->getControllerClassName();
        if (!class_exists($className) && $this->namespace != 'core' && $prefixNamespace) {
            $className = ucfirst($this->namespace).$className;
        }
        if (!class_exists($className)) {
            $classFile = strtolower($f).'.class.php';
            $classPath = null;

            foreach ($paths as $controllersPath) {
                if (!file_exists($controllersPath.$classFile)) {
                    if (file_exists($controllersPath.strtolower($f).'/index.class.php')) {
                        $classPath = $controllersPath.strtolower($f).'/index.class.php';
                    }
                } else {
                    $classPath = $controllersPath.$classFile;
                    break;
                }
            }

            /* handle Revo <2.2 controllers */
            if (empty($classPath)) {
                $className = 'modManagerControllerDeprecated';
                $classPath = MODX_CORE_PATH.'model/modx/modmanagercontrollerdeprecated.class.php';
            }

            if (!file_exists($classPath)) {
                if (file_exists(strtolower($f).'/index.class.php')) {
                    $classPath = strtolower($f).'/index.class.php';
                } else { /* handle Revo <2.2 controllers */
                    $className = 'modManagerControllerDeprecated';
                    $classPath = MODX_CORE_PATH.'model/modx/modmanagercontrollerdeprecated.class.php';
                }
            }

            ob_start();
            require_once $classPath;
            ob_end_clean();
        }
        return $className;
    }

    public function instantiateController($className,$getInstanceMethod = 'getInstance') {
        try {
            $c = new $className($this->modx,$this->action);
            if (!($c instanceof modExtraManagerController) && $getInstanceMethod == 'getInstanceDeprecated') {
                $getInstanceMethod = 'getInstance';
            }
            /* this line allows controller derivatives to decide what instance they want to return (say, for derivative class_key types) */
            $this->modx->controller = call_user_func_array(array($c,$getInstanceMethod),array(&$this->modx,$className,$this->action));
            $this->modx->controller->setProperties($c instanceof SecurityLoginManagerController ? $_POST : array_merge($_GET,$_POST));
            $this->modx->controller->initialize();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $this->modx->controller;
    }

    /**
     * If this action has a menu item, ensure user has access to menu
     * @param string $action
     * @return bool
     */
    public function checkForMenuPermissions($action) {
        $canAccess = true;
        /** @var modMenu $menu */
        $menu = $this->modx->getObject('modMenu', array(
            'action' => $action,
            'namespace' => $this->namespace,
        ));
        if ($menu) {
            $permissions = $menu->get('permissions');
            if (!empty($permissions)) {
                $permissions = explode(',', $permissions);
                foreach ($permissions as $permission) {
                    if (!$this->modx->hasPermission($permission)) {
                        return false;
                    }
                }
            }
        }
        return $canAccess;
    }

    /**
     * Gets the controller class name from the active modAction object
     *
     * @return string
     */
    public function getControllerClassName() {
        $className = $this->action['controller'].(!empty($this->action['class_postfix']) ? $this->action['class_postfix'] : 'ManagerController');
        $className = explode('/',$className);
        $o = array();
        foreach ($className as $k) {
            $o[] = ucfirst(str_replace(array('.','_','-'),'',$k));
        }
        return implode('',$o);
    }

    /**
     * Get the appropriate path to the controllers directory for the active Namespace.
     *
     * @param string $theme
     * @return array An array of paths to the Namespace's controllers directory.
     */
    public function getNamespacePath($theme = 'default') {
        $namespace = array_key_exists($this->namespace,$this->namespaces) ? $this->namespaces[$this->namespace] : $this->namespaces['core'];
        /* find context path */
        if (isset($namespace['name']) && $namespace['name'] != 'core') {
            $paths[] = $namespace['path'].'controllers/'.trim($theme,'/').'/';
            if ($theme != 'default') {
                $paths[] = $namespace['path'].'controllers/default/';
            }
            $paths[] = $namespace['path'].'controllers/';

            /* deprecated old usage */
            $paths[] = $namespace['path'].trim($theme,'/');
            if ($theme != 'default') {
                $paths[] = $namespace['path'].'default/';
            }
            $paths[] = $namespace['path'];

        } else {
            $paths[] = $namespace['path'].'controllers/'.trim($theme,'/').'/';
            if ($theme != 'default') {
                $paths[] = $namespace['path'].'controllers/default/';
            }
            $paths[] = $namespace['path'].'controllers/';
        }
        return $paths;

    }

    /**
     * Adds a lexicon topic to this page's language topics to load. Will load
     * the topic as well.
     *
     * @param string $topic The topic to load, in standard namespace:topic format
     * @return boolean True if successful
     */
    public function addLangTopic($topic) {
        $this->modx->lexicon->load($topic);
        $topics = $this->getLangTopics();
        $topics[] = $topic;
        return $this->setLangTopics($topics);
    }

    /**
     * Adds a lexicon topic to this page's language topics to load
     *
     * @return boolean True if successful
     */
    public function getLangTopics() {
        $topics = $this->modx->smarty->get_template_vars('_lang_topics');
        return explode(',',$topics);
    }

    /**
     * Sets the language topics for this page
     *
     * @param array $topics The array of topics to set
     * @return boolean True if successful
     */
    public function setLangTopics(array $topics = array()) {
        if (!is_array($topics) || empty($topics)) return false;

        $topics = array_unique($topics);
        $topics = implode(',',$topics);
        return $this->modx->smarty->assign('_lang_topics',$topics);
    }
}
