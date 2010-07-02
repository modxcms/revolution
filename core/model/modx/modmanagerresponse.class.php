<?php
/**
 * modManagerResponse
 *
 * @package modx
 */
require_once MODX_CORE_PATH . 'model/modx/modresponse.class.php';
/**
 * Encapsulates an HTTP response from the MODx manager.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modManagerResponse extends modResponse {
    public $action = array();

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
            $action =& $this->modx->request->action;
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

                /* get template path */
                $templatePath = $this->modx->getOption('manager_path') . 'templates/'.$theme.'/';
                if (!file_exists($templatePath)) {
                    $templatePath = $this->modx->getOption('manager_path') . 'templates/default/';
                    $this->modx->config['manager_theme'] = 'default';
                    $this->modx->smarty->assign('_config',$this->modx->config);
                }

                /* assign custom action topics to smarty, so can load custom topics for each page */
                $this->modx->lexicon->load('action');
                $topics = explode(',',$this->action['lang_topics']);
                foreach ($topics as $topic) { $this->modx->lexicon->load($topic); }
                $this->modx->smarty->assign('_lang_topics',$this->action['lang_topics']);
                $this->modx->smarty->assign('_lang',$this->modx->lexicon->fetch());
                $this->modx->smarty->assign('_ctx',$this->modx->context->get('key'));

                $this->registerBaseScripts($this->action['haslayout'] ? true : false);

                $this->body = '';


                $f = $this->prepareNamespacePath($this->action['controller'],$theme);
                $f = $this->getControllerFilename($f);

                if (file_exists($f)) {
                    $this->modx->invokeEvent('OnBeforeManagerPageInit',array(
                        'action' => $this->action,
                        'filename' => $f,
                    ));
                    if (!empty($this->action['namespace']) && $this->action['namespace'] != 'core') {
                        $this->modx->smarty->setTemplatePath($this->action['namespace_path']);
                    }

                    $cbody = include $f;
                } else {
                    $cbody = 'Could not find action file at: '.$f;
                }

                $this->registerActionDomRules($action);
                $this->registerCssJs();

                /* reset path to core modx path for header/footer */
                $this->modx->smarty->setTemplatePath($templatePath);

                /* load header */
                $controllersPath = $this->modx->getOption('manager_path').'controllers/'.$theme.'/';
                if (!file_exists($controllersPath)) {
                    $controllersPath = $this->modx->getOption('manager_path').'controllers/default/';
                }
                if ($this->action['haslayout']) {
                    $this->body .= include_once $controllersPath.'header.php';
                }

                /* assign later to allow for css/js registering */
                if (is_array($cbody)) {
                    $this->modx->smarty->assign('_e', $cbody);
                    $cbody = $this->modx->smarty->fetch('error.tpl');
                }
                $this->body .= $cbody;

                /* load footer */
                if ($this->action['haslayout']) {
                    $this->body .= include_once $controllersPath.'footer.php';
                }

                
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
        exit();
    }

    /**
     * Register ActionDom rules that hide/show fields
     *
     * @access public
     * @param integer $action The ID of the modAction object
     */
    public function registerActionDomRules($action) {
        if (empty($action)) return false;

        /* now do action dom rules */
        $userGroups = $this->modx->user->getUserGroups();
        $c = $this->modx->newQuery('modActionDom');
        $c->leftJoin('modAccessActionDom','Access');
        $principalCol = $this->modx->getSelectColumns('modAccessActionDom','Access','',array('principal'));
        $c->where(array(
            'action' => $action,
            'active' => true,
            array(
                array(
                    'Access.principal_class:=' => 'modUserGroup',
                    $principalCol.' IN ('.implode(',',$userGroups).')',
                ),
                'OR:Access.principal:IS' => null,
            ),
        ));
        $domRules = $this->modx->getCollection('modActionDom',$c);
        $rules = array();
        foreach ($domRules as $rule) {
            $r = $rule->apply();
            if (!empty($r)) $rules[] = $r;
        }
        $ruleOutput = '';
        if (!empty($rules)) {
            $ruleOutput .= '<script type="text/javascript">Ext.onReady(function() {';
            $ruleOutput .= implode("\n",$rules);
            $ruleOutput .= '});</script>';
            $this->modx->regClientStartupHTMLBlock($ruleOutput);
        }
    }

    /**
     * Registers the core and base JS scripts
     *
     * @access public
     */
    public function registerBaseScripts($loadLayout = true) {
        $managerUrl = $this->modx->getOption('manager_url');
        if ($this->modx->getOption('concat_js',null,false)) {
            if ($this->modx->getOption('compress_js',null,false)) {
                $this->modx->regClientStartupScript($managerUrl.'assets/modext/modext-min.js');
            } else {
                $this->modx->regClientStartupScript($managerUrl.'assets/modext/modext.js');
            }
        } else {
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.localization.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/util/utilities.js');

            $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.component.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.panel.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.tabs.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.window.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.tree.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.combo.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.grid.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.console.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.portal.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/modx.treedrop.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/windows.js');

            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/resource/modx.tree.resource.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/element/modx.tree.element.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/system/modx.tree.directory.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.view.js');
        }

        if ($loadLayout) {
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.layout.js');
            $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">Ext.onReady(function() {
    MODx.load({xtype: "modx-layout",accordionPanels: MODx.accordionPanels || [],auth: "'.$this->modx->site_id.'"});
});</script>');
        }
    }

    /**
     * Prepares the Namespace Path for usage
     *
     * @access protected
     * @return string The formatted Namespace path
     */
    protected function prepareNamespacePath($controller,$theme = 'default') {
        /* set context url and path */
        $this->modx->config['namespace_path'] = $controller;

        /* find context path */
        if (isset($this->action['namespace']) && $this->action['namespace'] != 'core') {
            /* if a custom 3rd party path */
            $f = $this->action['namespace_path'].$controller;

        } else {
            $f = $this->action['namespace_path'].'controllers/'.$theme.'/'.$controller;
            /* if custom theme doesnt have controller, go to default theme */
            if (!file_exists($f.'.php')) {
                $f = $this->action['namespace_path'].'controllers/default/'.$controller;
            }
        }
        return $f;
    }

    /**
     * Gets the parsed controller filename and checks for its existence.
     *
     * @access protected
     * @param string $f The filename to parse.
     * @return mixed The parsed filename, or boolean false if invalid.
     */
    protected function getControllerFilename($f = '') {
        if (empty($f)) return false;

        /* if action is a directory, load base index.php */
        if (substr($f,strlen($f)-1,1) == '/') { $f .= 'index'; }
        /* append .php */
        if (file_exists($f.'.php')) {
            $f = $f.'.php';
        /* for actions that don't have trailing / but reference index */
        } elseif (file_exists($f.'/index.php')) {
            $f = $f.'/index.php';
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'Could not find action file at: '.$f);
            $f = $f.'.php';
        }
        return $f;
    }

    /**
     * Registers CSS/JS to manager interface
     */
    public function registerCssJs() {
        /* if true, use compressed JS */
        if ($this->modx->getOption('compress_js',null,false)) {
            foreach ($this->modx->sjscripts as &$scr) {
                $pos = strpos($scr,'.js');
                if ($pos) {
                    $newUrl = substr($scr,0,$pos).'-min'.substr($scr,$pos,strlen($scr));
                } else { continue; }
                $pos = strpos($newUrl,'modext/');
                if ($pos) {
                    $pos = $pos+7;
                    $newUrl = substr($newUrl,0,$pos).'build/'.substr($newUrl,$pos,strlen($newUrl));
                }

                $path = str_replace(array(
                    $this->modx->getOption('manager_url').'assets/modext/',
                    '<script type="text/javascript" src="',
                    '"></script>',
                ),'',$newUrl);

                if (file_exists($this->modx->getOption('manager_path').'assets/modext/'.$path)) {
                    $scr = $newUrl;
                }
            }
        }
        /* assign css/js to header */
        $this->modx->smarty->assign('cssjs',$this->modx->sjscripts);
    }

}