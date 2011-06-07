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
    public $action = array();

    public function getControllerClassName() {
        $className = $this->action['controller'].(!empty($this->action['class_postfix']) ? $this->action['class_postfix'] : 'ManagerController');
        $className = explode('/',$className);
        $o = array();
        foreach ($className as $k) {
            $o[] = ucfirst($k);
        }
        return implode('',$o);
    }

    /**
     * Overrides modResponse::outputContent to provide mgr-context specific
     * response.
     *
     * {@inheritdoc}
     */
    public function outputContent(array $options = array()) {
        $modx= & $this->modx;
        $error= & $this->modx->error;

        $action = '';
        if (!isset($this->modx->request) || !isset($this->modx->request->action)) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));
        } else {
            $action =& intval($this->modx->request->action);
        }

        $theme = $this->modx->getOption('manager_theme',null,'default');

        $this->modx->lexicon->load('dashboard','topmenu','file');
        if ($action == 0) {
            $action = $this->modx->getObject('modAction',array(
                'namespace' => 'core',
                'controller' => 'welcome',
            ));
            $action = $action->get('id');
        }

        if ($this->modx->hasPermission('frames')) {
            if (isset($this->modx->actionMap[$action])) {
                $this->action = $this->modx->actionMap[$action];
                require_once MODX_CORE_PATH.'model/modx/modmanagercontroller.class.php';

                /* first attempt to get new class format file introduced in 2.2+ */
                $path = $this->getNamespacePath();
                $f = $path.$this->action['controller'];
                $className = $this->getControllerClassName();
                $classPath = strtolower($f).'.class.php';
                if (!file_exists($classPath)) { /* handle Revo <2.2 controllers */
                    $className = 'modManagerControllerDeprecated';
                    $classPath = MODX_CORE_PATH.'model/modx/modmanagercontrollerdeprecated.class.php';
                }

                ob_start();
                require_once $classPath;
                ob_end_clean();
                $this->controller = new $className($this->modx,$this->action);
                $this->body = $this->controller->render();

            } else {
                $this->body = $this->modx->error->failure($modx->lexicon('action_err_nfs',array(
                    'id' => $action,
                )));
            }
        } else {
            /* doesnt have permissions to view manager */
            $this->modx->smarty->assign('_lang',$this->modx->lexicon->fetch());
            $this->modx->smarty->assign('_ctx',$this->modx->context->get('key'));

            $this->body = include_once $this->modx->getOption('manager_path').'controllers/'.$theme.'/security/logout.php';

        }
        if (empty($this->body)) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));
        }
        if (is_array($this->body)) {
            $this->modx->smarty->assign('_e', $this->body);
            echo $this->modx->smarty->fetch('error.tpl');
        } else {
            echo $this->body;
        }
        @session_write_close();
        exit();
    }
    
    public function getNamespacePath($theme = 'default') {
            
        /* find context path */
        if (isset($this->action['namespace']) && $this->action['namespace'] != 'core') {
            /* if a custom 3rd party path */
            $path = $this->action['namespace_path'];

        } else {
            $path = $this->action['namespace_path'].'controllers/'.trim($theme,'/').'/';
            /* if custom theme doesnt have controller, go to default theme */
            if (!is_dir($path)) {
                $path = $this->action['namespace_path'].'controllers/default/';
            }
        }
        return $path;

    }
}
