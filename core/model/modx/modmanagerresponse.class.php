<?php
/**
 * modManagerResponse
 *
 * @package modx
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

    /**
     * Overrides modResponse::outputContent to provide mgr-context specific
     * response.
     *
     * @param array $options
     */
    public function outputContent(array $options = array()) {
        $action = '';
        if (!isset($this->modx->request) || !isset($this->modx->request->action)) {
            $this->body = $this->modx->error->failure($this->modx->lexicon('action_err_ns'));
        } else {
            $action = (integer) $this->modx->request->action;
        }

        $theme = $this->modx->getOption('manager_theme',null,'default');
        $this->modx->lexicon->load('dashboard','topmenu','file','action');
        if ($action == 0 || !isset($this->modx->actionMap[$action])) {
            /** @var modAction $action */
            $action = $this->modx->getObject('modAction',array(
                'namespace' => 'core',
                'controller' => 'welcome',
            ));
            $action = $action->get('id');
        }

        $this->action = $this->modx->actionMap[$action];
        if (empty($this->action)) $this->action = array();
        $isLoggedIn = $this->modx->user->isAuthenticated('mgr');
        if (!$isLoggedIn) {
            $alternateLogin = $this->modx->getOption('manager_login_url_alternate',null,'');
            if (!empty($alternateLogin)) {
                $this->modx->sendRedirect($alternateLogin);
                return '';
            }
            $this->action['namespace'] = 'core';
            $this->action['namespace_name'] = 'core';
            $this->action['namespace_path'] = $this->modx->getOption('manager_path',null,MODX_MANAGER_PATH);
            $this->action['namespace_assets_path'] = $this->modx->getOption('assets_path',null,MODX_ASSETS_PATH);
            $this->action['lang_topics'] = 'login';
            $this->action['controller'] = 'security/login';
        } else if (!$this->modx->hasPermission('frames')) {
            $this->action['namespace'] = 'core';
            $this->action['namespace_name'] = 'core';
            $this->action['namespace_path'] = $this->modx->getOption('manager_path',null,MODX_MANAGER_PATH);
            $this->action['namespace_assets_path'] = $this->modx->getOption('assets_path',null,MODX_ASSETS_PATH);
            $this->action['lang_topics'] = 'login';
            $this->action['controller'] = 'security/logout';
        }

        if ($isLoggedIn && !$this->checkForMenuPermissions($action)) {
            $this->body = $this->modx->error->failure($this->modx->lexicon('access_denied'));
            
        } else {
            require_once MODX_CORE_PATH.'model/modx/modmanagercontroller.class.php';

            /* first attempt to get new class format file introduced in 2.2+ */
            $paths = $this->getNamespacePath($theme);
            $f = $this->action['controller'];
            $className = $this->getControllerClassName();
            if (!class_exists($className)) {
                $classFile = strtolower($f).'.class.php';

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
            try {
                $c = new $className($this->modx,$this->action);
                /* this line allows controller derivatives to decide what instance they want to return (say, for derivative class_key types) */
                $this->modx->controller = call_user_func_array(array($c,'getInstance'),array($this->modx,$className,$this->action));
                $this->modx->controller->setProperties(array_merge($_GET,$_POST));
                $this->modx->controller->initialize();
            } catch (Exception $e) {
                die($e->getMessage());
            }

            $this->body = $this->modx->controller->render();
        }
        
        if (empty($this->body)) {
            $this->body = $this->modx->error->failure($this->modx->lexicon('action_err_ns'));
        }
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
     * If this action has a menu item, ensure user has access to menu
     * @param string $action
     * @return bool
     */
    public function checkForMenuPermissions($action) {
        $canAccess = true;
        /** @var modMenu $menu */
        $menu = $this->modx->getObject('modMenu',array(
            'action' => $action,
        ));
        if ($menu) {
            $permissions = $menu->get('permissions');
            if (!empty($permissions)) {
                $permissions = explode(',',$permissions);
                foreach ($permissions as $permission) {
                    if (!$this->modx->hasPermission($permission)) {
                        $canAccess = false;
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
        /* find context path */
        if (isset($this->action['namespace']) && $this->action['namespace'] != 'core') {
            /* if a custom 3rd party path */
            $paths[] = $this->action['namespace_path'].trim($theme,'/');
            if ($theme != 'default') {
                $paths[] = $this->action['namespace_path'].'default/';
            }
            $paths[] = $this->action['namespace_path'];

        } else {
            $paths[] = $this->action['namespace_path'].'controllers/'.trim($theme,'/').'/';
            if ($theme != 'default') {
                $paths[] = $this->action['namespace_path'].'controllers/default/';
            }
            $paths[] = $this->action['namespace_path'].'controllers/';
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