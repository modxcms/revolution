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

                /* assign custom action topics to smarty, so can load custom topics for each page */
                $this->modx->lexicon->load('action');
                $topics = explode(',',$this->action['lang_topics']);
                foreach ($topics as $topic) { $this->modx->lexicon->load($topic); }
                $this->modx->smarty->assign('_lang_topics',$this->action['lang_topics']);
                $this->modx->smarty->assign('_lang',$this->modx->lexicon->fetch());
                $this->modx->smarty->assign('_ctx',$this->modx->context->get('key'));

                $this->registerBaseScripts();
                $this->registerActionDomRules($action);

                $this->body = '';

                $f = $this->prepareNamespacePath();
                $f = $this->getControllerFilename($f);

                if (file_exists($f)) {
                    $this->modx->invokeEvent('OnBeforeManagerPageInit',array(
                        'action' => $this->action,
                        'filename' => $f,
                    ));

                    $cbody = include $f;
                } else {
                    $cbody = 'Could not find action file at: '.$f;
                }

                /* reset path to core modx path for header/footer */
                $this->modx->smarty->setTemplatePath($modx->getOption('manager_path') . 'templates/' . $this->modx->getOption('manager_theme',null,'default') . '/');

                /* load header */
                if ($this->action['haslayout']) {
                    $this->body .= include $this->modx->getOption('manager_path') . 'controllers/header.php';
                }

                /* assign later to allow for css/js registering */
                if (is_array($cbody)) {
                    $this->modx->smarty->assign('_e', $cbody);
                    $cbody = $this->modx->smarty->fetch('error.tpl');
                }
                $this->body .= $cbody;

                if ($this->action['haslayout']) {
                    $this->body .= include_once $this->modx->getOption('manager_path').'controllers/footer.php';
                }


            } else {
                $this->body = $this->modx->error->failure('No action with ID '.$action.' found.');
            }
        } else {
            $logoutLink = '[ <a href="' . $modx->getOption('connectors_url') . 'security/logout.php" title="' . $this->modx->lexicon('logout') . '">' . $this->modx->lexicon('logout') . '</a> ]';
            $this->body = $this->modx->error->failure($this->modx->lexicon('permission_denied') . " <small>{$logoutLink}</small>");
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
        $c->where(array(
            'action' => $action,
        ));
        $c->andCondition(array(
            'Access.principal_class' => 'modUserGroup',
            'Access.principal IN ('.implode(',',$userGroups).')',
        ),null,2);
        $c->orCondition(array(
            'Access.principal IS NULL',
        ),null,2);
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
    public function registerBaseScripts() {
        $managerUrl = $this->modx->getOption('manager_url');

        $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.localization.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/util/utilities.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/util/switchbutton.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.form.handler.js');

        $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.component.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.actionbuttons.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.msg.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.panel.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.tabs.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.window.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.tree.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.combo.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.grid.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.grid.local.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.console.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.portal.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/modx.treedrop.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/windows.js');

        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/resource/modx.tree.resource.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/element/modx.tree.element.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/system/modx.tree.directory.js');
        $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.layout.js');

        $this->modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-layout"
                ,accordionPanels: MODx.accordionPanels || []
            });
        });
        </script>');
    }

    /**
     * Prepares the Namespace Path for usage
     *
     * @access protected
     * @return string The formatted Namespace path
     */
    protected function prepareNamespacePath() {
        /* set context url and path */
        $this->modx->config['namespace_path'] = $this->action['namespace_path'];

        /* find context path */
        if (!isset($this->action['namespace']) || $this->action['namespace'] == 'core') {
            $f = $this->action['namespace_path'].'controllers/'.$this->action['controller'];

        } else { /* if a custom 3rd party path */
            $f = $this->action['namespace_path'].$this->action['controller'];
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

}